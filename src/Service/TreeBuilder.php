<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\ItemType;

class TreeBuilder
{
    private array $nodes = [];

    public function addNode(string $name, string $type, string $parentName, string $relation): void
    {
        $node = [
            'itemName' => $name,
            'parent' => $parentName ?? null,
            'children' => [],
            'type' => $type,
            'relation' => $relation ?? null,
        ];
        $this->nodes[$name] = $node;
    }

    public function buildTree(): array
    {
        $roots = array_filter(
            $this->nodes,
            function ($node) {
                return empty($node['parent']);
            }
        );

        return array_map(
            function ($root) {
                return [
                'itemName' => $root['itemName'],
                'parent' => null,
                'children' => $this->buildTreeRecursive($this->nodes, $root['itemName']),
                ];
            },
            $roots
        );
    }

    /**
     * @param array<string,mixed> $nodes
     *
     * @return array<string,mixed>[]
     */
    private function buildTreeRecursive(array $nodes, string $parentName): array
    {
        $children = [];
        foreach ($nodes as $name => $node) {
            if ($node['parent'] === $parentName) {
                $child = [
                    'itemName' => $node['itemName'],
                    'parent' => $parentName,
                    'children' => $this->buildTreeRecursive($nodes, $name),
                ];

                if ($node['type'] === ItemType::DIRECT_COMPONENTS && !empty($node['relation'])) {
                    $relatedItem = $this->findRelatedItem($nodes, $node['relation']);
                    if (!empty($relatedItem)) {
                        $child['children'] = array_merge(
                            $child['children'],
                            $this->buildTreeRecursive(
                                $nodes,
                                $relatedItem['itemName']
                            ),
                        );
                    }
                }

                $children[] = $child;
            }
        }

        return $children;
    }

    /**
     * @param array<string,mixed> $nodes
     */
    private function findRelatedItem(array $nodes, string $relation): array|bool
    {
        $filteredNodes = array_filter(
            $nodes,
            function ($node) use ($relation) {
                return $node['itemName'] === $relation && $node['type'] === ItemType::PRODUCTS_AND_COMPONENTS;
            }
        );

        return reset($filteredNodes);
    }
}
