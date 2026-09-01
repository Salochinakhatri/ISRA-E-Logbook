<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuggestionController extends Controller
{
    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $suggestions = Suggestion::where('user_id', $userId)
            ->when($program !== '', fn($q) => $q->where('program', $program))
            ->latest('created_at')
            ->get();

        return view('suggestions.index', compact('suggestions', 'program'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $request->validate([
            'suggestion_text' => ['required', 'string', 'min:10'],
        ]);

        Suggestion::create([
            'user_id'         => $userId,
            'suggestion_text' => $request->input('suggestion_text'),
            'program'         => $program,
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('suggestions.index', $params)
            ->with('flash_ok', 'Suggestion submitted successfully. Thank you!');
    }
}
