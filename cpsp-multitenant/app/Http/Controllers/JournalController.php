<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalController extends Controller
{
    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $entries = JournalEntry::where('user_id', $userId)
            ->when($program !== '', fn($q) => $q->where('program', $program))
            ->latest('created_at')
            ->get();

        return view('journal.index', compact('entries', 'program'));
    }

    public function create(Request $request): View
    {
        $program    = (string) $request->input('program', '');
        $formOld    = session()->pull('form_old', []);
        $formErrors = session()->pull('form_errors', []);

        return view('journal.create', compact('program', 'formOld', 'formErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $request->validate([
            'date_of_diss'    => ['required', 'string'],
            'fac_by'          => ['required', 'string', 'max:255'],
            'std_post'        => ['required', 'in:Yes,No'],
        ]);

        JournalEntry::create([
            'user_id'         => $userId,
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

    private function parseBritishDate(string $s): ?string
    {
        $s = trim($s);
        if ($s === '' || ! preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $s, $m)) {
            return null;
        }

        return $m[3] . '-' . $m[2] . '-' . $m[1];
    }
}
