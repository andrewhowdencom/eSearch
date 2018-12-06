<?php

namespace App\Resource;

use Illuminate\Support\Facades\DB;

class MySQL implements \App\Resource\CollectionInterface
{
    /** @var DB */
    private $connection;

    public function __construct(
        DB $connection
    ) {
        $this->connection = $connection;
    }

    /**
     * @return array
     */
    public function query(string $storageIdentifier, string $query, int $startIndex, int $numberResults): array
    {
        throw new \Exception('Unimplemented');
    }
}