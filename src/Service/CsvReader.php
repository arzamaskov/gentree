<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\CsvSymbol;
use Generator;
use RuntimeException;

class CsvReader
{
    /**
     * @return Generator<array|bool|null>
     */
    public function read(string $csvFile): Generator
    {
        $file = fopen($csvFile, 'r');
        if ($file === false) {
            throw new RuntimeException("Unable to open CSV file: $csvFile");
        }

        while (($data = fgetcsv($file, 0, CsvSymbol::DELIMITER, CsvSymbol::QUOTE)) !== false) {
            yield $data;
        }

        fclose($file);
    }
}
