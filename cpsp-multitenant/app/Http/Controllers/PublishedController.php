<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PublishedEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class PublishedController extends Controller
{
    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $entries = PublishedEntry::where('user_id', $userId)
            ->when($program !== '', fn($q) => $q->where('program', $program))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('published.index', compact('entries', 'program'));
    }

    public function create(Request $request): View
    {
        $program    = (string) $request->input('program', '');
        $formOld    = array_merge(session()->pull('form_old', []), session()->getOldInput());
        $formErrors = session()->pull('form_errors', []);

        return view('published.create', compact('program', 'formOld', 'formErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $validator = Validator::make($request->all(), [
            'pub_date'  => ['required', 'string'],
            'pub_title' => ['required', 'string', 'max:500'],
            'full_ref'  => ['required', 'string'],
            'std_post'  => ['required', 'in:Yes,No'],
        ], [
            'required' => 'The :attribute field is required.',
        ], [
            'pub_date'  => 'Published Date',
            'pub_title' => 'Title',
            'full_ref'  => 'Full Reference',
            'std_post'  => 'Send to Supervisor',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form_errors', array_merge(['Please fill all the required fields.'], $validator->errors()->all()))
                ->with('flash_err', 'Please fill all the required fields.');
        }

        $user = \App\Models\User::with('supervisor')->find($userId);
        $supervisorId = $user?->supervisor_id ?: (int) (\Illuminate\Support\Facades\DB::table('supervisor_trainee')->where('trainee_id', $userId)->value('supervisor_id') ?: 0) ?: null;

        PublishedEntry::create([
            'user_id'       => $userId,
            'supervisor_id' => $supervisorId,
            'pub_date'      => $this->parseBritishDate($request->input('pub_date', '')),
            'pub_title'     => $request->input('pub_title'),
            // cpsp1-style
            'full_ref'      => $request->input('full_ref'),
            // cpsp2-style
            'pub_journal'   => (string) $request->input('pub_journal', ''),
            'pub_authors'   => (string) $request->input('pub_authors', ''),
            'program'       => $program,
            'std_post'      => $request->input('std_post'),
            'entry_status'  => $request->input('std_post') === 'Yes' ? 'Awaiting Approval' : 'Draft',
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('published.index', $params)
            ->with('flash_ok', 'Published entry saved successfully.');
    }

    public function edit(Request $request, int $id): View|RedirectResponse
    {
        $userId = (int) session('user_id');
        $entry  = PublishedEntry::where('user_id', $userId)->findOrFail($id);

        if (! $this->isEntryEditable($entry->entry_status)) {
            return redirect()->route('published.index')
                ->with('flash_err', 'This entry cannot be edited because it is ' . ($entry->entry_status ?: 'locked') . '.');
        }

        $program = (string) ($request->input('program') ?? $entry->program ?? '');
        $entryDefaults = [
            'pub_date'    => $entry->pub_date ? $entry->pub_date->format('d-m-Y') : '',
            'pub_title'   => $entry->pub_title,
            'full_ref'    => $entry->full_ref,
            'pub_journal' => $entry->pub_journal,
            'pub_authors' => $entry->pub_authors,
            'std_post'    => $entry->entry_status === 'Discuss and Resubmit' ? 'Yes' : ($entry->std_post ?: 'No'),
        ];
        $formOld    = array_merge($entryDefaults, session()->pull('form_old', []), session()->getOldInput());
        $formErrors = session()->pull('form_errors', []);

        return view('published.create', compact('entry', 'program', 'formOld', 'formErrors'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $userId = (int) session('user_id');
        $entry  = PublishedEntry::where('user_id', $userId)->findOrFail($id);

        if (! $this->isEntryEditable($entry->entry_status)) {
            return redirect()->route('published.index')
                ->with('flash_err', 'This entry cannot be updated because it is ' . ($entry->entry_status ?: 'locked') . '.');
        }

        $program = (string) $request->input('program', $entry->program ?? '');

        $validator = Validator::make($request->all(), [
            'pub_date'  => ['required', 'string'],
            'pub_title' => ['required', 'string', 'max:500'],
            'full_ref'  => ['required', 'string'],
            'std_post'  => ['required', 'in:Yes,No'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $newStatus = $request->input('std_post') === 'Yes'
            ? 'Awaiting Approval'
            : ($entry->entry_status === 'Discuss and Resubmit' ? 'Discuss and Resubmit' : 'Draft');

        $entry->update([
            'pub_date'     => $this->parseBritishDate($request->input('pub_date', '')),
            'pub_title'    => $request->input('pub_title'),
            'full_ref'     => $request->input('full_ref'),
            'pub_journal'  => (string) $request->input('pub_journal', $entry->pub_journal ?? ''),
            'pub_authors'  => (string) $request->input('pub_authors', $entry->pub_authors ?? ''),
            'std_post'     => $request->input('std_post') === 'Yes' ? 'Yes' : ($entry->entry_status === 'Discuss and Resubmit' ? 'Yes' : 'No'),
            'entry_status' => $newStatus,
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('published.index', $params)
            ->with('flash_ok', 'Published entry updated successfully.');
    }

    private function isEntryEditable(?string $status): bool
    {
        $s = strtolower(trim((string) ($status ?? '')));

        return $s === '' || $s === 'draft' || $s === 'discuss and resubmit';
    }

    private function parseBritishDate(string $s): ?string
    {
        $s = trim($s);
        if ($s === '' || ! preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $s, $m)) {
            return null;
        }

        return $m[3] . '-' . $m[2] . '-' . $m[1];
    }
}
