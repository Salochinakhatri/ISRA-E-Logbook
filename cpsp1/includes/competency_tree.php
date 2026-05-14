<?php

declare(strict_types=1);

/**
 * @param list<array{id:int,label:string,children:list<array{id:int,label:string}>}> $groups
 */
function render_competency_tree(array $groups, string $prefix = 'com'): void
{
    $gName = $prefix;
    $dName = $prefix . '_detail';

    echo '<ul id="competencyTreeRoot" class="competency-tree">';
    foreach ($groups as $g) {
        $gid = (int) $g['id'];
        $label = htmlspecialchars((string) $g['label'], ENT_QUOTES, 'UTF-8');
        echo '<li class="competency-tree__node competency-tree__node--parent">';
        echo '<span class="competency-tree__toggle" data-toggle-branch role="button" tabindex="0" aria-expanded="false">+</span>';
        echo '<input type="checkbox" name="' . $gName . '_id[]" value="' . $gid . '" id="' . $gName . 'g_' . $gid . '">';
        echo '<label class="competency-tree__text" for="' . $gName . 'g_' . $gid . '">' . $label . '</label>';
        echo '<ul class="competency-tree__branch">';
        foreach ($g['children'] as $c) {
            $cid = (int) $c['id'];
            $cl = htmlspecialchars((string) $c['label'], ENT_QUOTES, 'UTF-8');
            echo '<li class="competency-tree__node competency-tree__node--leaf">';
            echo '<input type="checkbox" name="' . $dName . '_id[]" value="' . $cid . '" id="' . $gName . 'd_' . $cid . '">';
            echo '<label for="' . $gName . 'd_' . $cid . '">' . $cl . '</label>';
            echo '</li>';
        }
        echo '</ul>';
        echo '</li>';
    }
    echo '</ul>';
}
