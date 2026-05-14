<?php

declare(strict_types=1);

/** @var array<string,mixed>|null $entry */

if ($entry === null) {
    echo '<p class="elog-empty">Entry not found.</p>';

    return;
}

require_once __DIR__ . '/../includes/training_constants.php';

$gids = is_string($entry['com_ids'] ?? null) ? json_decode($entry['com_ids'], true) : ($entry['com_ids'] ?? []);
$dids = is_string($entry['com_detail_ids'] ?? null) ? json_decode($entry['com_detail_ids'], true) : ($entry['com_detail_ids'] ?? []);
if (!is_array($gids)) {
    $gids = [];
}
if (!is_array($dids)) {
    $dids = [];
}
$gids = array_map('intval', $gids);
$dids = array_map('intval', $dids);
$compMap = training_competency_label_map();

?>
<div class="elog-view">
    <div class="elog-view__grid">
        <div><span class="kv-label">Hospital Reg. No</span><span class="kv-val"><?= htmlspecialchars((string) ($entry['hospt_reg_no'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></div>
        <div><span class="kv-label">Admission</span><span class="kv-val"><?= htmlspecialchars(format_admit_ordinal(isset($entry['date_of_admission']) ? (string) $entry['date_of_admission'] : null), ENT_QUOTES, 'UTF-8') ?></span></div>
        <div><span class="kv-label">Gender / Age</span><span class="kv-val"><?= htmlspecialchars((string) ($entry['pt_gender'] ?? ''), ENT_QUOTES, 'UTF-8') ?> / <?= htmlspecialchars((string) ($entry['pt_age'] ?? ''), ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars((string) ($entry['pt_age_type'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></div>
        <div><span class="kv-label">Diagnosis</span><span class="kv-val"><?= htmlspecialchars((string) ($entry['pt_diagnosis'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></div>
        <div><span class="kv-label">Program</span><span class="kv-val"><?= htmlspecialchars(training_program_label((string) ($entry['entry_for_prog_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?></span></div>
        <div><span class="kv-label">Level</span><span class="kv-val"><?= htmlspecialchars(training_level_label((string) ($entry['level_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?></span></div>
        <div><span class="kv-label">Status</span><span class="kv-val"><?= htmlspecialchars((string) ($entry['entry_status'] ?? ''), ENT_QUOTES, 'UTF-8') ?></span></div>
    </div>
    <h2 class="elog-view__h">Brief description</h2>
    <div class="elog-view__box"><?= nl2br(htmlspecialchars(strip_tags((string) ($entry['brief_desc'] ?? '')), ENT_QUOTES, 'UTF-8')) ?></div>
    <h2 class="elog-view__h">Competency</h2>
    <div class="elog-view__box"><?= training_format_competency_cell($gids, $dids, $compMap) ?></div>
</div>
