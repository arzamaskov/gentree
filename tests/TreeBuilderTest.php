<?php

declare(strict_types=1);

use App\Enum\ItemType;
use App\Service\TreeBuilder;
use PHPUnit\Framework\TestCase;

class TreeBuilderTest extends TestCase
{
    public function testAddNode(): void
    {
        $treeBuilder = new TreeBuilder();

        $itemName1 = 'Item1';
        $itemType1 = ItemType::DIRECT_COMPONENTS;
        $itemParent1 = '';
        $itemRelation1 = 'Relation1';

        $itemName2 = 'Item2';
        $itemType2 = ItemType::DIRECT_COMPONENTS;
        $itemParent2 = 'Item1';
        $itemRelation2 = 'Relation2';

        $treeBuilder->addNode($itemName1, $itemType1, $itemParent1, $itemRelation1);
        $treeBuilder->addNode($itemName2, $itemType2, $itemParent2, $itemRelation2);

        $nodes = $treeBuilder->buildTree();

        $this->assertCount(1, $nodes);
        $this->assertArrayHasKey('Item1', $nodes);

        $item1 = $nodes['Item1'];

        $this->assertEquals('Item1', $item1['itemName']);
    }

    public function testBuildTree(): void
    {
        $treeBuilder = new TreeBuilder();

        $treeBuilder->addNode('Item1', ItemType::DIRECT_COMPONENTS, '', '');
        $treeBuilder->addNode('Item2', ItemType::DIRECT_COMPONENTS, 'Item1', 'Item1');

        $tree = $treeBuilder->buildTree();
        $this->assertCount(1, $tree);

        $item1 = reset($tree);
        $this->assertEquals('Item1', $item1['itemName']);
        $this->assertNull($item1['parent']);
        $this->assertCount(1, $item1['children']);

        $item2 = $item1['children'][0];
        $this->assertEquals('Item2', $item2['itemName']);
        $this->assertEquals('Item1', $item2['parent']);
        $this->assertCount(0, $item2['children']);
    }
}
