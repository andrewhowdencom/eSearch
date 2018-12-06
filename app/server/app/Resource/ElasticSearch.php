<?php

namespace App\Resource;

use \Elasticsearch\ClientBuilder;

class ElasticSearch implements \App\Resource\CollectionInterface
{
    /** @var \Elasticsearch\Client */
    private $client;

    public function __construct()
    {
        // @todo: Remove the direct call to the factory to a DI call via the constructor.
        // @todo: Pull this into the interface
        $this->client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();
    }

    /**
     * Queries elasticsearch, caches the results
     *
     * @todo: $storageIdentifier maps terribly onto the EAV storage in MySQL that this would invariably require.
     *
     * @param string $storageIdentifier The identifier for the index or table containing the properties
     * @return array
     */
    public function query(string $storageIdentifier, string $query, int $startIndex, int $numberResults): array
    {
        $results = [];

        // Need to throw an exception required param shere. need at least:
        // Can we do this via a constructor? Shfit all the setters out, do it via constructor instead.
        // Prevents late binding, but makes it more "reasonable"
        // - index
        // - query
        $params = [

            'index' => $storageIdentifier,
            'q'     => $query,
            'from'  => $startIndex,
            'size'  => $numberResults
        ];

        // Performs the search in the lucene syntax.
        $esResult = $this->client->search($params);

        // This will break at the first invalid array property ... I think.
        // @todo: Double check this. I'm not sure if this will still throw an invalid array access error.

        // Need to transform these into some sort of abstract result that transforms from both MySQL and the ES
        // tooling.
        //
        // Probably simply an associative, unstructured array. Further stuff will be handled by the implementing
        // collections
        //
        // Current:
        // array (
        //  '_index' => 'products',
        //  '_type' => 'object',
        //  '_id' => 'bf212c1d-e342-3677-8537-defb17270242',
        //  '_score' => 2.3750248,
        //  '_source' =>
        //  array (
        //    '@context' => 'http://schema.org',
        //    '@type' => 'Product',
        //    'id' => 'bf212c1d-e342-3677-8537-defb17270242',
        //    'name' => 'Quality Fish',
        //    'sku' => 'e553b6a3-aa75-35ad-a479-2b20cb14212d',
        //  ),
        //)
        if ($esResult && $esResult['hits'] && $esResult['hits']['hits']) {
            // Transform the objects from the native data store time to an associative array that can later be
            // constructed into the product object.

            foreach ($esResult['hits']['hits'] as $hit) {
                $result = [];
                // Add missing properties
                $result['category_id'] = null;
                $result['attributes'] = [];

                // Add mapped properties
                $result['id'] = $hit['_source']['id'];
                $result['name'] = $hit['_source']['name'];
                $result['sku'] = $hit['_source']['sku'];

                // Push the result!
                $results[] = $result;
            }
        }

        // Hooray! Domain objects.
        // (Note: I would not commit these comments. They're mostly for fun at this stage)
        return $results;
    }
}