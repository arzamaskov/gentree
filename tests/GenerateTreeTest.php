<?php

declare(strict_types=1);

namespace App\Tests\Command;

use App\Command\GenerateTree;
use App\Service\{CsvReader, JsonSerializer, TreeBuilder};
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class GenerateTreeTest extends TestCase
{
    public function testExecute(): void
    {
        $csvReaderMock = $this->createMock(CsvReader::class);
        $jsonSerializerMock = $this->createMock(JsonSerializer::class);
        $treeBuilderMock = $this->createMock(TreeBuilder::class);

        $command = new GenerateTree($csvReaderMock, $jsonSerializerMock, $treeBuilderMock);

        $application = new Application();
        $application->add($command);

        $commandTester = new CommandTester($command);

        $inputFileName = 'input.csv';
        $outputFileName = 'output.json';
        $csvData = [['item1', 'type1', 'parent1', 'relation1'], ['item2', 'type2', 'parent2', 'relation2']];
        $treeData = [['item1' => ['children' => []]], ['item2' => ['children' => []]]];
        $jsonData = json_encode($treeData);
        $csvDataGenerator = function () use ($csvData) {
            foreach ($csvData as $row) {
                yield $row;
            }
        };

        $csvReaderMock->expects($this->once())
            ->method('read')
            ->with($inputFileName)
            ->willReturn($csvDataGenerator());

        $treeBuilderMock->expects($this->once())
            ->method('addNode')
            ->willReturnOnConsecutiveCalls(
                null,
                null
            );

        $treeBuilderMock->expects($this->once())
            ->method('buildTree')
            ->willReturn($treeData);

        $jsonSerializerMock->expects($this->once())
            ->method('serialize')
            ->with($treeData)
            ->willReturn($jsonData);

        $commandTester->execute([
            'command' => $command->getName(),
            'input_file_name' => $inputFileName,
            'output_file_name' => $outputFileName,
        ]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString("JSON tree saved to $outputFileName!", $output);
    }
}
