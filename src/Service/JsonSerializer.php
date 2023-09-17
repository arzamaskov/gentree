<?php

namespace App\Service;

use Throwable;

class JsonSerializer
{
    /**
     * @param array<string,mixed> $data
     */
    public function serialize(array $data): string
    {
        try {
            $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Throwable $e) {
            echo sprintf("%s.\n", $e->getMessage());
        }

        return $json;
    }
}
