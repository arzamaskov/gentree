<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\{CsvReader, JsonSerializer, TreeBuilder};
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\{InputArgument, InputInterface};
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

class GenerateTree extends Command
{
    protected static $defaultName = 'generate';
    protected static $defaultDescription = 'Creates a JSON tree file based on a CSV file.';

    private $csvReader;
    private $jsonSerializer;
    private $treeBuilder;

    public function __construct(
        CsvReader $csvReader,
        JsonSerializer $jsonSerializer,
        TreeBuilder $treeBuilder
    ) {
        parent::__construct();
        $this->csvReader = $csvReader;
        $this->jsonSerializer = $jsonSerializer;
        $this->treeBuilder = $treeBuilder;
    }

    protected function configure(): void
    {
        $this->addArgument('input_file_name', InputArgument::REQUIRED, 'Input CSV file');
        $this->addArgument('output_file_name', InputArgument::REQUIRED, 'Output JSON file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFileName = $input->getArgument('input_file_name');
        $outputFileName = $input->getArgument('output_file_name');

        $csvData = $this->csvReader->read($inputFileName);
        $isFirstRow = true;

        foreach ($csvData as $row) {
            if ($isFirstRow) {
                $isFirstRow = false;
                continue;
            }

            [$itemName, $itemType, $itemParent, $itemRelation] = $row;
            $this->treeBuilder->addNode($itemName, $itemType, $itemParent, $itemRelation);
        }

        $treeData = $this->treeBuilder->buildTree();
        $jsonData = $this->jsonSerializer->serialize($treeData);

        try {
            file_put_contents($outputFileName, $jsonData);

            $output->writeln("JSON tree saved to $outputFileName!");

            return Command::SUCCESS;
        } catch (Throwable $e) {
            echo sprintf("%s.\n", $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
