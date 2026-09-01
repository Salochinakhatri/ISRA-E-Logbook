<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PublishedEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublishedController extends Controller
{
    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $entries = PublishedEntry::where('user_id', $userId)
            ->when($program !== '', fn($q) => $q->where('program', $program))
            ->latest('created_at')
            ->get();

        return view('published.index', compact('entries', 'program'));
    }

    public function create(Request $request): View
    {
        $program    = (string) $request->input('program', '');
        $formOld    = session()->pull('form_old', []);
        $formErrors = session()->pull('form_errors', []);

        return view('published.create', compact('program', 'formOld', 'formErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $request->validate([
            'pub_date'  => ['required', 'string'],
            'pub_title' => ['required', 'string', 'max:500'],
            'std_post'  => ['required', 'in:Yes,No'],
        ]);

        PublishedEntry::create([
            'user_id'      => $userId,
            'pub_date'     => $this->parseBritishDate($request->input('pub_date', '')),
            'pub_title'    => $request->input('pub_title'),
            // cpsp1-style
            'full_ref'     => $request->input('full_ref'),
            // cpsp2-style
            'pub_journal'  => (string) $request->input('pub_journal', ''),
            'pub_authors'  => (string) $request->input('pub_authors', ''),
            'program'      => $program,
            'std_post'     => $request->input('std_post'),
            'entry_status' => $request->input('std_post') === 'Yes' ? 'Awaiting Approval' : 'Draft',
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('published.index', $params)
            ->with('flash_ok', 'Published entry saved successfully.');
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
