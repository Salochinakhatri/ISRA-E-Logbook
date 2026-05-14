<?php

declare(strict_types=1);

/** @return array<string,string> */
function training_form_type_options(): array
{
    return [
        '1' => '(FORM - A)- Record Of Operations / Procedures',
        '2' => '(FORM - B)- Record Of Emergency Procedures',
        '3' => '(FORM - D)- Cases presented at clinico-pathological Conference...',
        '6' => '(FORM - F)- Miscellaneous',
        '7' => '(FORM - G)- Record of autopsy/exhumation',
        '8' => '(FORM - H)- Record of medico legal cases',
        '9' => '(FORM - I)- Clinical Case Discussion',
        '10' => '(FORM - J)- Record of Orthodontic Procedure',
    ];
}

/** @return array<string,string> */
function training_level_options(): array
{
    return [
        '1' => 'Observer Status',
        '2' => 'Assistant Status',
        '3' => 'Performed under direct Supervision',
        '4' => 'Performed under indirect supervision',
        '5' => 'Performed independently',
        '5555' => 'Other',
    ];
}

/** @return array<string,string> */
function training_outcome_options(): array
{
    return [
        '2' => 'Admitted to inpatient facility',
        '3' => 'Treated and called for follow-up',
        '4' => 'Referred to other specialty unit',
        '5' => 'Death of the patient',
        '7' => 'Improved',
        '8' => 'Discharged',
        '9' => 'Treated',
        '10' => 'Under Treatment',
        '11' => 'Treatment Failure',
        '12' => 'Follow Up',
        '6' => 'Other',
    ];
}

/** @return array<string,string> */
function training_program_options(): array
{
    return [
        '2' => 'IMM',
        '3' => 'MCPS',
        '4' => 'FCPS-II',
    ];
}

/** @return array<string,string> */
function training_entry_status_options(): array
{
    return [
        '' => 'All Entry Status',
        'Draft' => 'Draft',
        'Approved' => 'Approved',
        'Awaiting Approval' => 'Awaiting Approval',
        'Discuss and Resubmit' => 'Discuss and Resubmit',
    ];
}

function training_form_type_label(string $id): string
{
    $m = training_form_type_options();

    return $m[$id] ?? ('Form #' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8'));
}

function training_level_label(string $id): string
{
    if ($id === '') {
        return '—';
    }
    $m = training_level_options();

    return $m[$id] ?? $id;
}

function training_program_label(string $id): string
{
    if ($id === '') {
        return '—';
    }
    $m = training_program_options();

    return $m[$id] ?? $id;
}

function format_last_entry_notice(?string $dt): ?string
{
    if ($dt === null || $dt === '') {
        return null;
    }
    $ts = strtotime($dt);
    if ($ts === false) {
        return null;
    }

    return date('F j, Y', $ts);
}

function format_admit_ordinal(?string $ymd): string
{
    if ($ymd === null || $ymd === '') {
        return '—';
    }
    $ts = strtotime($ymd . ' 12:00:00');
    if ($ts === false) {
        return '—';
    }
    $j = (int) date('j', $ts);
    $suffix = 'th';
    if ($j % 10 === 1 && $j !== 11) {
        $suffix = 'st';
    } elseif ($j % 10 === 2 && $j !== 12) {
        $suffix = 'nd';
    } elseif ($j % 10 === 3 && $j !== 13) {
        $suffix = 'rd';
    }

    return $j . $suffix . ' ' . date('M Y', $ts);
}

function format_datetime_display(?string $dt): string
{
    if ($dt === null || $dt === '') {
        return '—';
    }
    $ts = strtotime($dt);
    if ($ts === false) {
        return '—';
    }

    return date('j M Y', $ts);
}

/**
 * Build lookup id=>label from competency tree.
 *
 * @return array<int,string>
 */
function training_competency_label_map(): array
{
    require_once __DIR__ . '/../data/competencies.php';
    $map = [];
    foreach (competency_tree_data() as $g) {
        $map[(int) $g['id']] = (string) $g['label'];
        foreach ($g['children'] as $c) {
            $map[(int) $c['id']] = (string) $c['label'];
        }
    }

    return $map;
}

/**
 * @param array<int,int>|null $ids
 * @param array<int,string> $map
 */
function parse_dmy_to_sql_date(string $s): ?string
{
    $s = trim($s);
    if ($s === '') {
        return null;
    }
    if (!preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $s, $m)) {
        return null;
    }
    $d = $m[1];
    $mo = $m[2];
    $y = $m[3];
    if (!checkdate((int) $mo, (int) $d, (int) $y)) {
        return null;
    }

    return $y . '-' . $mo . '-' . $d;
}

/**
 * @param array<int,int>|null $groupIds
 * @param array<int,int>|null $detailIds
 * @param array<int,string> $map
 */
function training_format_competency_cell(?array $groupIds, ?array $detailIds, array $map): string
{
    $groupIds = $groupIds ?? [];
    $detailIds = $detailIds ?? [];
    $lines = [];
    foreach ($groupIds as $gid) {
        $gid = (int) $gid;
        if ($gid <= 0) {
            continue;
        }
        $lines[] = $map[$gid] ?? ('Group #' . $gid);
    }
    $bullets = [];
    foreach ($detailIds as $did) {
        $did = (int) $did;
        if ($did <= 0) {
            continue;
        }
        $bullets[] = $map[$did] ?? ('Detail #' . $did);
    }
    $html = '';
    foreach ($lines as $ln) {
        $html .= '<div class="comp-cell__group">' . htmlspecialchars($ln, ENT_QUOTES, 'UTF-8') . '</div>';
    }
    if ($bullets !== []) {
        $html .= '<ul class="comp-cell__list">';
        foreach ($bullets as $b) {
            $html .= '<li>' . htmlspecialchars($b, ENT_QUOTES, 'UTF-8') . '</li>';
        }
        $html .= '</ul>';
    }
    if ($html === '') {
        return '<span class="text-muted">—</span>';
    }

    return $html;
}
