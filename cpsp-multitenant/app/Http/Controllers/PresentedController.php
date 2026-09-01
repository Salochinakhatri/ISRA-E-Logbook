<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\PresentedEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PresentedController extends Controller
{
    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $entries = PresentedEntry::where('user_id', $userId)
            ->when($program !== '', fn($q) => $q->where('program', $program))
            ->latest('created_at')
            ->get();

        return view('presented.index', compact('entries', 'program'));
    }

    public function create(Request $request): View
    {
        $program    = (string) $request->input('program', '');
        $formOld    = session()->pull('form_old', []);
        $formErrors = session()->pull('form_errors', []);

        return view('presented.create', compact('program', 'formOld', 'formErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $request->validate([
            'rec_date'  => ['required', 'string'],
            'rec_title' => ['required', 'string', 'max:500'],
            'rec_venue' => ['required', 'string', 'max:500'],
            'std_post'  => ['required', 'in:Yes,No'],
        ]);

        PresentedEntry::create([
            'user_id'      => $userId,
            'rec_date'     => $this->parseBritishDate($request->input('rec_date', '')),
            'rec_title'    => $request->input('rec_title'),
            'rec_venue'    => $request->input('rec_venue'),
            // cpsp1-style
            'conf_name'    => $request->input('conf_name'),
            // cpsp2-style
            'rec_type'     => (string) $request->input('rec_type', ''),
            'program'      => $program,
            'std_post'     => $request->input('std_post'),
            'entry_status' => $request->input('std_post') === 'Yes' ? 'Awaiting Approval' : 'Draft',
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('presented.index', $params)
            ->with('flash_ok', 'Presented entry saved successfully.');
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
