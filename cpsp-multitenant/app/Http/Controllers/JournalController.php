<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $entries = JournalEntry::where('user_id', $userId)
            ->when($program !== '', fn($q) => $q->where('program', $program))
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('journal.index', compact('entries', 'program'));
    }

    public function create(Request $request): View
    {
        $program    = (string) $request->input('program', '');
        $formOld    = array_merge(session()->pull('form_old', []), session()->getOldInput());
        $formErrors = session()->pull('form_errors', []);

        return view('journal.create', compact('program', 'formOld', 'formErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $validator = Validator::make($request->all(), [
            'date_of_diss'    => ['required', 'string'],
            'fac_by'          => ['required', 'string', 'max:255'],
            'std_post'        => ['required', 'in:Yes,No'],
        ], [
            'required' => 'The :attribute field is required.',
        ], [
            'date_of_diss' => 'Date of Discussion',
            'fac_by'       => 'Facilitated by',
            'std_post'     => 'Send to Supervisor',
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

        JournalEntry::create([
            'user_id'         => $userId,
            'supervisor_id'   => $supervisorId,
            'date_of_diss'    => $this->parseBritishDate($request->input('date_of_diss', '')),
            'fac_by'          => $request->input('fac_by'),
            // cpsp1-style: combined reference
            'ref_of_art_disc' => $request->input('ref_of_art_disc'),
            // cpsp2-style: topic + article
            'topic'           => (string) $request->input('topic', ''),
            'ref_article'     => (string) $request->input('ref_article', ''),
            'program'         => $program,
            'std_post'        => $request->input('std_post'),
            'entry_status'    => $request->input('std_post') === 'Yes' ? 'Awaiting Approval' : 'Draft',
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('journal.index', $params)
            ->with('flash_ok', 'Journal entry saved successfully.');
    }

    public function edit(Request $request, int $id): View|RedirectResponse
    {
        $userId = (int) session('user_id');
        $entry  = JournalEntry::where('user_id', $userId)->findOrFail($id);

        if (! $this->isEntryEditable($entry->entry_status)) {
            return redirect()->route('journal.index')
                ->with('flash_err', 'This entry cannot be edited because it is ' . ($entry->entry_status ?: 'locked') . '.');
        }

        $program = (string) ($request->input('program') ?? $entry->program ?? '');
        $entryDefaults = [
            'date_of_diss'    => $entry->date_of_diss ? $entry->date_of_diss->format('d-m-Y') : '',
            'fac_by'          => $entry->fac_by,
            'ref_of_art_disc' => $entry->ref_of_art_disc,
            'topic'           => $entry->topic,
            'ref_article'     => $entry->ref_article,
            'std_post'        => $entry->entry_status === 'Discuss and Resubmit' ? 'Yes' : ($entry->std_post ?: 'No'),
        ];
        $formOld    = array_merge($entryDefaults, session()->pull('form_old', []), session()->getOldInput());
        $formErrors = session()->pull('form_errors', []);

        return view('journal.create', compact('entry', 'program', 'formOld', 'formErrors'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $userId = (int) session('user_id');
        $entry  = JournalEntry::where('user_id', $userId)->findOrFail($id);

        if (! $this->isEntryEditable($entry->entry_status)) {
            return redirect()->route('journal.index')
                ->with('flash_err', 'This entry cannot be updated because it is ' . ($entry->entry_status ?: 'locked') . '.');
        }

        $program = (string) $request->input('program', $entry->program ?? '');

        $validator = Validator::make($request->all(), [
            'date_of_diss' => ['required', 'string'],
            'fac_by'       => ['required', 'string', 'max:255'],
            'std_post'     => ['required', 'in:Yes,No'],
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
            'date_of_diss'    => $this->parseBritishDate($request->input('date_of_diss', '')),
            'fac_by'          => $request->input('fac_by'),
            'ref_of_art_disc' => $request->input('ref_of_art_disc'),
            'topic'           => (string) $request->input('topic', $entry->topic ?? ''),
            'ref_article'     => (string) $request->input('ref_article', $entry->ref_article ?? ''),
            'std_post'        => $request->input('std_post') === 'Yes' ? 'Yes' : ($entry->entry_status === 'Discuss and Resubmit' ? 'Yes' : 'No'),
            'entry_status'    => $newStatus,
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('journal.index', $params)
            ->with('flash_ok', 'Journal entry updated successfully.');
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
