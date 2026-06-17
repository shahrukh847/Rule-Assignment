<?php

class TreeBuilder
{
    public function buildTree(array $rows): array
    {
        $nodes = [];
        $tree = [];

        foreach ($rows as $row) {

            $row['children'] = [];

            $nodes[$row['id']] = $row;
        }

        foreach ($nodes as $id => $node) {

            if ($node['parent_assignment_id']) {

                $parentId =
                    $node['parent_assignment_id'];

                $nodes[$parentId]['children'][] =
                    &$nodes[$id];

            } else {

                $tree[] = &$nodes[$id];
            }
        }

        return $tree;
    }
}