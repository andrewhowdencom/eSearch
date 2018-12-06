<?php

namespace App\Resource;

/**
 * This is heavily tied to the "collection" abstraction. Further work would be required to pull out the various
 * connection semantics et. al for the various adapters.
 *
 * Deliberatley not using Laravel here as it's basically a POC. Laravel does do the ORM modelling quite well
 * (SQLite is implemented in other places), however time is a limiting factor.
 *
 * Class ResourceInterface
 * @package App\Resource
 */
interface CollectionInterface
{
    /**
     * Implements the query object that queries the data store for a given set of objects
     *
     * The query interface is a fairly poor abstraction of the data storage layer behind the query entity.
     *
     * @todo: This would probably benefit from a "query" entity that could provide some arbitrary characteristics,
     *        rather than a discrete set of values.
     *
     * @param string $query
     * @param string $storageIdentifier
     * @param int $startIndex
     * @param int $numberResults
     *
     * @todo: Describe the return format.
     *
     * @return array of associative arrays that *should* be valid objects.
     *
     *         The objects themselves do not enforce correctness coming from the data pool, but rather only minimally
     *         transform the objects to establish some sort of consistency.
     *
     *         Enforcing properties of the objects is the domain of the entity model itself (i.e. product), which may
     *         choose to further transform the object beyond the bounds of the data storage model to facilitate things
     *         such as schema changes over time in non structured data. That object in turn is constructed by the
     *         collection object.
     *
     *         Here, the only guarantee is that we will make a best effort transformation to a stable associative
     *         array.
     *
     *         Array is of the form:
     *
     *         [
     *           [
     *             'id' => string,
     *             'name' => string,
     *             'sku' => string,
     *             'category_id' => string
     *             'attributes' => [
     *               'key' => 'value',
     *               ...
     *             ]
     *           ]
     *        ]
     *
     *         It's difficult to reason about the difference between "sku", "name" and "other arbitrary attributes".
     *         It makes the intellectual distinction between "core" attributes and "other" attributes, which is messy
     *         This comes as a result of using the schema.org data types. Future work may seek to revisit these
     *         data times, and either investigate why some attributes are "first class" objects, and some are only
     *         "additional" objects.
     */
    public function query(string $storageIdentifier, string $query,  int $startIndex, int $numberResults): array;
}