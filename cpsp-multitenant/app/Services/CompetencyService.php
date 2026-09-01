<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Loads competency trees from static data files.
 * Supports IMM, FCPS, UroGyn, and ObGyn trees.
 */
class CompetencyService
{
    /** @var array<string, array<int, string>> */
    private array $maps = [];

    /**
     * Build a flat id→label map for the given program.
     *
     * @return array<int, string>
     */
    public function labelMap(string $program): array
    {
        if (isset($this->maps[$program])) {
            return $this->maps[$program];
        }

        $tree = $this->treeData($program);
        $map  = [];

        foreach ($tree as $group) {
            $map[(int) $group['id']] = (string) $group['label'];
            foreach ($group['children'] as $child) {
                $map[(int) $child['id']] = (string) $child['label'];
            }
        }

        $this->maps[$program] = $map;

        return $map;
    }

    /**
     * Load the competency tree for the given program.
     *
     * @return list<array{id: int, label: string, children: list<array{id: int, label: string}>}>
     */
    public function treeData(string $program): array
    {
        $dataDir = __DIR__ . '/data';

        $isGynae = false;
        try {
            $tenant = app(\App\Services\TenantManager::class)->getTenant();
            if ($tenant && ($tenant->domain === 'cpsp2.test' || (int) $tenant->id === 2)) {
                $isGynae = true;
            }
        } catch (\Throwable) {}

        if ($isGynae || in_array($program, ['urogyn', 'obgyn', 'ms', 'dgo'], true)) {
            $raw = require $dataDir . '/competency_obgyn.php';
        } else {
            $raw = array_merge(
                require $dataDir . '/competency_imm.php',
                require $dataDir . '/competency_fcps.php',
            );
        }

        return array_map(function ($group) {
            $group['label'] = $this->cleanLabel((string) $group['label']);
            $group['children'] = array_map(function ($child) {
                $child['label'] = $this->cleanLabel((string) $child['label']);
                return $child;
            }, $group['children'] ?? []);
            return $group;
        }, $raw);
    }

    /**
     * Remove program indicator suffixes like (IMM), (FCPS - II), (FCPS-III) from labels.
     */
    public function cleanLabel(string $s): string
    {
        return trim((string) preg_replace('/\s*\((?:IMM|FCPS(?:\s*-\s*[I|V|X\d]+)?)\)\s*/i', '', $s));
    }

    /**
     * Format competency group/detail IDs into an HTML cell.
     *
     * @param list<int>|null $groupIds
     * @param list<int>|null $detailIds
     * @param array<int, string> $map
     */
    public function formatCell(?array $groupIds, ?array $detailIds, array $map): string
    {
        $groupIds  = $groupIds  ?? [];
        $detailIds = $detailIds ?? [];
        $lines     = [];
        $bullets   = [];

        foreach ($groupIds as $gid) {
            $gid = (int) $gid;
            if ($gid > 0) {
                $lines[] = $map[$gid] ?? ('Group #' . $gid);
            }
        }

        foreach ($detailIds as $did) {
            $did = (int) $did;
            if ($did > 0) {
                $bullets[] = $map[$did] ?? ('Detail #' . $did);
            }
        }

        $html = '';
        foreach ($lines as $ln) {
            $html .= '<div class="comp-cell__group">' . e($ln) . '</div>';
        }
        if ($bullets !== []) {
            $html .= '<ul class="comp-cell__list">';
            foreach ($bullets as $b) {
                $html .= '<li>' . e($b) . '</li>';
            }
            $html .= '</ul>';
        }

        return $html !== '' ? $html : '<span class="text-muted">—</span>';
    }
}
