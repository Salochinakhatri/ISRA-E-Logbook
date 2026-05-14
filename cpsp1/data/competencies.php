<?php

declare(strict_types=1);

/**
 * Full CPSP competency tree: IMM + FCPS-II groups with all portal detail IDs.
 * Source split into data/groups/competency_imm.php and competency_fcps.php for maintainability.
 *
 * @return list<array{id:int,label:string,children:list<array{id:int,label:string}>}>
 */
function competency_tree_data(): array
{
    static $tree = null;
    if ($tree !== null) {
        return $tree;
    }

    /** @var list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $imm */
    $imm = require __DIR__ . '/groups/competency_imm.php';
    /** @var list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $fcps */
    $fcps = require __DIR__ . '/groups/competency_fcps.php';

    $tree = array_merge($imm, $fcps);

    return $tree;
}
