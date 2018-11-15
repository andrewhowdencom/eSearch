<?php

declare(strict_types = 1);

namespace App\Entities;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Contracts\Support\Arrayable;

abstract class ElasticSearchEntity extends Entity implements Arrayable
{
    /**
     * Persists the updated object to ElasticSearch
     *
     * @return ElasticSearchEntity
     */
    public function save(): ElasticSearchEntity
    {
        // Persist the data to the index
        $this->getClient()
            ->index(
                [
                    'index' => $this->getIndex(),
                    'type'  => 'object',
                    'id'    => $this->getId(),
                    'body'  => $this->toArray()
                ]
            );

        return $this;
    }

    /**
     * Denotes the index the model is expected to be saved on.
     *
     * Analogous to a "table" in a relational database.
     *
     * @return string
     */
    abstract public function getIndex(): string;

    /**
     * Returns the canonical object for this reference.
     *
     * Should be a UUID
     *
     * @return string
     */
    abstract public function getId(): string;

    /**
     * Builds the ElasticSearch client suitable for operations
     */
    private function getClient(): Client
    {
        $client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();

        return $client;
    }
}
