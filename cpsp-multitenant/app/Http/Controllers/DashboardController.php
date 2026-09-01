<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\PresentedEntry;
use App\Models\PublishedEntry;
use App\Models\RotationalEntry;
use App\Models\Suggestion;
use App\Models\TrainingEntry;
use App\Models\User;
use App\Services\TenantManager;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private TenantManager $tenantManager) {}

    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        $userId = (int) session('user_id');
        $user   = User::with('userType', 'profile')->find($userId);

        if (! $user) {
            session()->forget(['user_id', 'user_type_id', 'username', 'email', 'user_type', 'tenant_id']);
            return redirect()->route('login');
        }

        $isTrainee = $user->isTrainee();

        if ($isTrainee) {
            $program = strtolower((string) request('program', ''));
            $counts = [
                'training'    => TrainingEntry::where('user_id', $userId)->when($program !== '', fn($q) => $q->where('program', $program))->count(),
                'rotational'  => RotationalEntry::where('user_id', $userId)->when($program !== '', fn($q) => $q->where('program', $program))->count(),
                'journal'     => JournalEntry::where('user_id', $userId)->when($program !== '', fn($q) => $q->where('program', $program))->count(),
                'presented'   => PresentedEntry::where('user_id', $userId)->when($program !== '', fn($q) => $q->where('program', $program))->count(),
                'published'   => PublishedEntry::where('user_id', $userId)->when($program !== '', fn($q) => $q->where('program', $program))->count(),
                'suggestions' => Suggestion::where('user_id', $userId)->when($program !== '', fn($q) => $q->where('program', $program))->count(),
            ];

            // Build dynamic recent activities across all sections
            $recentActivities = collect();

            $trainings = TrainingEntry::where('user_id', $userId)
                ->when($program !== '', fn($q) => $q->where('program', $program))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'section'    => 'Training',
                    'title'      => $item->pt_diagnosis ? "Training: {$item->pt_diagnosis}" : "Training entry #{$item->id}",
                    'meta'       => ($item->hospt_reg_no ? "Reg No: {$item->hospt_reg_no} | " : '') . "Status: " . ($item->entry_status ?? 'Draft'),
                    'status'     => $item->entry_status ?? 'Draft',
                    'icon'       => 'fa-solid fa-file-medical',
                    'badge_bg'   => '#e8f5e9',
                    'badge_text' => '#1b5e20',
                    'url'        => route('training.index', $program !== '' ? ['program' => $program] : []),
                    'created_at' => $item->created_at,
                ]);

            $rotationals = RotationalEntry::where('user_id', $userId)
                ->when($program !== '', fn($q) => $q->where('program', $program))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'section'    => 'Rotational',
                    'title'      => $item->department ? "Rotational: {$item->department}" : "Rotational entry #{$item->id}",
                    'meta'       => ($item->hospt_reg_no ? "Reg No: {$item->hospt_reg_no} | " : '') . "Status: " . ($item->entry_status ?? 'Draft'),
                    'status'     => $item->entry_status ?? 'Draft',
                    'icon'       => 'fa-solid fa-table-cells',
                    'badge_bg'   => '#e3f2fd',
                    'badge_text' => '#0d47a1',
                    'url'        => route('rotational.index', $program !== '' ? ['program' => $program] : []),
                    'created_at' => $item->created_at,
                ]);

            $journals = JournalEntry::where('user_id', $userId)
                ->when($program !== '', fn($q) => $q->where('program', $program))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'section'    => 'Journal Club',
                    'title'      => "Journal: " . ($item->topic ?: ($item->ref_of_art_disc ?: 'Journal Club entry')),
                    'meta'       => ($item->fac_by ? "Facilitator: {$item->fac_by} | " : '') . "Status: " . ($item->entry_status ?? 'Draft'),
                    'status'     => $item->entry_status ?? 'Draft',
                    'icon'       => 'fa-solid fa-book-journal-whills',
                    'badge_bg'   => '#f3e5f5',
                    'badge_text' => '#4a148c',
                    'url'        => route('journal.index', $program !== '' ? ['program' => $program] : []),
                    'created_at' => $item->created_at,
                ]);

            $presenteds = PresentedEntry::where('user_id', $userId)
                ->when($program !== '', fn($q) => $q->where('program', $program))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'section'    => 'Paper Presented',
                    'title'      => "Presentation: " . ($item->rec_title ?: 'Paper/Poster Presented'),
                    'meta'       => ($item->rec_venue ? "Venue: {$item->rec_venue} | " : '') . "Status: " . ($item->entry_status ?? 'Draft'),
                    'status'     => $item->entry_status ?? 'Draft',
                    'icon'       => 'fa-solid fa-chalkboard-user',
                    'badge_bg'   => '#fff3e0',
                    'badge_text' => '#e65100',
                    'url'        => route('presented.index', $program !== '' ? ['program' => $program] : []),
                    'created_at' => $item->created_at,
                ]);

            $publisheds = PublishedEntry::where('user_id', $userId)
                ->when($program !== '', fn($q) => $q->where('program', $program))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'section'    => 'Paper Published',
                    'title'      => "Published: " . ($item->rec_title ?: 'Paper Published'),
                    'meta'       => ($item->rec_journal ? "Journal: {$item->rec_journal} | " : '') . "Status: " . ($item->entry_status ?? 'Draft'),
                    'status'     => $item->entry_status ?? 'Draft',
                    'icon'       => 'fa-solid fa-newspaper',
                    'badge_bg'   => '#e0f2f1',
                    'badge_text' => '#004d40',
                    'url'        => route('published.index', $program !== '' ? ['program' => $program] : []),
                    'created_at' => $item->created_at,
                ]);

            $suggestions = Suggestion::where('user_id', $userId)
                ->when($program !== '', fn($q) => $q->where('program', $program))
                ->latest('created_at')
                ->limit(5)
                ->get()
                ->map(fn($item) => [
                    'id'         => $item->id,
                    'section'    => 'Suggestion',
                    'title'      => "Suggestion: " . \Illuminate\Support\Str::limit($item->suggestion_text, 60),
                    'meta'       => 'Submitted feedback',
                    'status'     => 'Submitted',
                    'icon'       => 'fa-solid fa-lightbulb',
                    'badge_bg'   => '#fbe9e7',
                    'badge_text' => '#bf360c',
                    'url'        => route('suggestions.index', $program !== '' ? ['program' => $program] : []),
                    'created_at' => $item->created_at,
                ]);

            $recentActivities = $recentActivities
                ->concat($trainings)
                ->concat($rotationals)
                ->concat($journals)
                ->concat($presenteds)
                ->concat($publisheds)
                ->concat($suggestions)
                ->sortByDesc('created_at')
                ->take(8)
                ->values();

            $lastEntryDate = $recentActivities->first()['created_at'] ?? null;
            $lastEntryLabel = $lastEntryDate
                ? $lastEntryDate->format('F j, Y')
                : null;

            return view('dashboard.trainee', compact('user', 'counts', 'lastEntryLabel', 'recentActivities'));
        }

        // Supervisor / Fellow view
        $trainees = User::whereHas('userType', fn($q) => $q->where('name', 'Trainee'))
            ->with('profile')
            ->get();

        $currentTrainees = $trainees->map(function ($trainee) {
            $trainingCount = TrainingEntry::where('user_id', $trainee->id)
                ->where('std_post', 'Yes')
                ->where('entry_status', 'Awaiting Approval')
                ->count();

            $rotationalCount = RotationalEntry::where('user_id', $trainee->id)
                ->where('std_post', 'Yes')
                ->where('entry_status', 'Awaiting Approval')
                ->count();

            $journalCount = JournalEntry::where('user_id', $trainee->id)
                ->where('std_post', 'Yes')
                ->where('entry_status', 'Awaiting Approval')
                ->count();

            $presentedCount = PresentedEntry::where('user_id', $trainee->id)
                ->where('std_post', 'Yes')
                ->where('entry_status', 'Awaiting Approval')
                ->count();

            $publishedCount = PublishedEntry::where('user_id', $trainee->id)
                ->where('std_post', 'Yes')
                ->where('entry_status', 'Awaiting Approval')
                ->count();

            $total = $trainingCount + $rotationalCount + $journalCount + $presentedCount + $publishedCount;

            return [
                'id'                  => $trainee->id,
                'name'                => $trainee->profile?->full_name ?: $trainee->username,
                'username'            => $trainee->username,
                'training'            => $trainingCount,
                'rotational'          => $rotationalCount,
                'journal'             => $journalCount,
                'presented'           => $presentedCount,
                'published'           => $publishedCount,
                'record_of_training'  => 0,
                'total'               => $total,
            ];
        });

        $rotationalTrainees = $currentTrainees->filter(fn($t) => $t['rotational'] > 0)->values();

        $stats = [
            'feedback_trainee'  => 0,
            'cme_credits'       => '12.00',
            'reports_count'     => 0,
            'workshop_feedback' => 0,
            'total_pending'     => $currentTrainees->sum('total'),
        ];

        return view('dashboard.supervisor', compact('user', 'currentTrainees', 'rotationalTrainees', 'stats'));
    }
}
