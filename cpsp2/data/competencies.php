<?php

declare(strict_types=1);

/**
 * Full CPSP competency tree – program-aware.
 * Returns the competency tree for the given program:
 *  - 'urogyn' → Urogynaecology (FCPS)
 *  - 'obgyn'  → Obstetrics and Gynaecology (FCPS)
 *  - ''        → IMM + FCPS-II combined tree (default / generic)
 *
 * @return list<array{id:int,label:string,children:list<array{id:int,label:string}>}>
 */
function competency_tree_data(string $program = ''): array
{
    static $trees = [];

    $key = $program === '' ? '__default__' : $program;

    if (isset($trees[$key])) {
        return $trees[$key];
    }

    if ($program === 'urogyn') {
        /** @var list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $tree */
        $tree = require __DIR__ . '/groups/competency_urogyn.php';
    } elseif ($program === 'obgyn') {
        /** @var list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $tree */
        $tree = require __DIR__ . '/groups/competency_obgyn.php';
    } else {
        // Generic combined IMM + FCPS-II tree (used when no program is specified)
        /** @var list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $imm */
        $imm  = require __DIR__ . '/groups/competency_imm.php';
        /** @var list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $fcps */
        $fcps = require __DIR__ . '/groups/competency_fcps.php';
        $tree = array_merge($imm, $fcps);
    }

    $trees[$key] = $tree;

    return $tree;
}
