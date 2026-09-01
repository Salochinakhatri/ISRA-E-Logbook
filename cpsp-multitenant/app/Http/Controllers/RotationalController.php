<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\RotationalEntry;
use App\Services\CompetencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RotationalController extends Controller
{
    public function __construct(private CompetencyService $competencyService) {}

    public function index(Request $request): View
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $query = RotationalEntry::where('user_id', $userId);

        if ($program !== '') {
            $query->where('program', $program);
        }

        $status = $request->input('f_status', '');
        $level  = $request->input('f_level', '');
        $reg    = $request->input('f_reg', '');

        if ($status !== '') $query->where('entry_status', $status);
        if ($level !== '')  $query->where('level_id', $level);
        if ($reg !== '')    $query->where('hospt_reg_no', 'like', "%{$reg}%");

        $entries = $query->latest('created_at')->get();
        $compMap = $this->competencyService->labelMap($program);
        $filters = compact('status', 'level', 'reg') + [
            'post_from' => $request->input('f_post_from', ''),
            'post_to'   => $request->input('f_post_to', ''),
            'adm_from'  => $request->input('f_adm_from', ''),
            'adm_to'    => $request->input('f_adm_to', ''),
        ];

        $lastEntry      = RotationalEntry::where('user_id', $userId)->latest('created_at')->first();
        $lastEntryLabel = $lastEntry ? $lastEntry->created_at?->format('F j, Y') : null;

        return view('rotational.index', compact('entries', 'program', 'compMap', 'filters', 'lastEntryLabel'));
    }

    public function create(Request $request): View
    {
        $program    = (string) $request->input('program', '');
        $compTree   = $this->competencyService->treeData($program);
        $formOld    = session()->pull('form_old', []);
        $formErrors = session()->pull('form_errors', []);

        return view('rotational.create', compact('program', 'compTree', 'formOld', 'formErrors'));
    }

    public function store(Request $request): RedirectResponse
    {
        $userId  = (int) session('user_id');
        $program = (string) $request->input('program', '');

        $request->validate([
            'form_type'         => ['required', 'string'],
            'hospt_reg_no'      => ['required', 'string', 'max:120'],
            'date_of_admission' => ['required', 'string'],
            'pt_gender'         => ['required', 'string'],
            'pt_age'            => ['required', 'string'],
            'pt_age_type'       => ['required', 'string'],
            'pt_diagnosis'      => ['required', 'string', 'max:500'],
            'under_sup_name'    => ['required', 'string', 'max:255'],
            'level_id'          => ['required', 'string'],
            'outcome_id'        => ['required', 'string'],
            'brief_desc'        => ['required', 'string'],
            'std_post'          => ['required', 'in:Yes,No'],
        ]);

        RotationalEntry::create([
            'user_id'           => $userId,
            'form_type'         => $request->input('form_type'),
            'hospt_reg_no'      => $request->input('hospt_reg_no'),
            'date_of_admission' => $this->parseBritishDate($request->input('date_of_admission', '')),
            'pt_gender'         => $request->input('pt_gender'),
            'pt_age'            => $request->input('pt_age'),
            'pt_age_type'       => $request->input('pt_age_type'),
            'pt_diagnosis'      => $request->input('pt_diagnosis'),
            'under_sup_name'    => $request->input('under_sup_name'),
            'level_id'          => $request->input('level_id'),
            'outcome_id'        => $request->input('outcome_id'),
            'brief_desc'        => $request->input('brief_desc'),
            'entry_for_prog_id' => (string) $request->input('entry_for_prog_id', ''),
            'program'           => $program,
            'rot_ids'           => $request->input('rot_id') ? array_map('intval', (array) $request->input('rot_id')) : null,
            'rot_detail_ids'    => $request->input('rot_detail_id') ? array_map('intval', (array) $request->input('rot_detail_id')) : null,
            'alt_procedure'     => (string) $request->input('alt_procedure', ''),
            'std_post'          => $request->input('std_post'),
            'entry_status'      => $request->input('std_post') === 'Yes' ? 'Awaiting Approval' : 'Draft',
        ]);

        $params = $program !== '' ? ['program' => $program] : [];

        return redirect()->route('rotational.index', $params)
            ->with('flash_ok', 'Rotational entry saved successfully.');
    }

    private function parseBritishDate(?string $s): ?string
    {
        $s = trim((string) ($s ?? ''));
        if ($s === '' || ! preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $s, $m)) {
            return null;
        }

        return $m[3] . '-' . $m[2] . '-' . $m[1];
    }
}
