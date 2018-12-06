<?php

declare(strict_types = 1);

namespace App\Entities\Collection;

use Illuminate\Contracts\Support\Arrayable;
use App;

/**
 * Responsible for handling the "common functions" associated with the various kind of objects returned by this. This
 * includes things like:
 *
 * - Handling connection and authentication
 * - Handling the conversion from the data storage primitive to the object primitive
 *
 */
abstract class EntityAbstract implements Arrayable
{
    /** @var int|null */
    private $startIndex = null;

    /** @var int|null */
    private $numberResults = null;

    /** @var string|null */
    private $query = null;

    /** @var array|null The results from ElasticSearch */
    private $results = null;

    /** @var App\Resource\CollectionInterface */
    private $resource = null;

    const DEFAULT_NUMBER_OF_RESULTS   = 50;
    const DEFAULT_START_INDEX = 0;

    /**
     * @var array
     *
     * An array of the form:
     *
     * [
     *   ['property' => 'filterExpression']
     * ]
     *
     * Expressions are simple key → value pairs; there is no complex expression matching such as wildcards.
     */
    private $filters = [];

    /**
     * @todo: Does Laravel do DI? If so, how can we bootstrap the connections tructure into this object.
     * @todo: Pull CollectionInterface out via DI. For now, just hard coding ES to make the app "work", so I
     *        can reason about it again.
     * EntityAbstract constructor.
     */
    public function __construct(
        App\Resource\ElasticSearch $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * This function sets the position in the logical set of all search results that this collection should return.
     *
     * For example, if the search results returned:
     *
     * index = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
     *
     * And the user wanted page "3" of "2 results" this would require setting the start index to (3 * 2 - 1) → 5
     *
     * index = [5, 6 ...]
     *
     * @see
     *   - https://cloud.google.com/apis/design/design_patterns#list_pagination
     *
     * @param int $index The numeric representation of the product that this collection should start with.
     *
     * @return EntityAbstract for chaining
     */
    public function setStartIndex(int $index)
    {
        $this->startIndex = $index;

        return $this;
    }

    /**
     * This function sets the total page size, or number of results that should follow the index position.
     *
     * @param int $result
     *
     * @return EntityAbstract;
     */
    public function setNumberResults(int $result)
    {
        $this->numberResults = $result;

        return $this;
    }

    /**
     * Sets the query.
     *
     * The query is an abstract string, and determining which results match a given query is deferred to this collection
     *
     * @param string $query
     * @return EntityAbstract;
     */
    public function setQuery(string $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * Adds a filter to the filter stack.
     *
     * For example, the property might be.
     *
     * @param string $property The attribute to filter
     * @param string $value    The value that the attribute should have.
     *
     * @return $this;
     */
    public function addFilter(string $property, string $value)
    {
        $this->filters[] = [$property => $value];

        return $this;
    }

    /**
     * Expresses the results as an array. Should express the hydrated objects as an arry, not the initial objects.
     * May need to be implemented in the implementing classes.
     *
     * @return array|void
     */
    abstract public function toArray();

    /**
     * Returns the index of the implementing class
     *
     * @todo: I reaaaaaaaaaly don't like this. The collection entity should definitely not have any notion of the
     *        underling storage layer.
     *
     *        Would need to subclass out the storage implementations on a per entity basis, or come up with some
     *        rule (such as the storage identifier) that allowed different implementations to map arbitrary
     *        complexity onto that identifier.
     *
     *        Storage identifier remains because ${TIME}
     *
     * @return string
     */
    abstract protected function getStorageIdentifier();

    /**
     * Returns the results
     *
     * Caches the results to prevent retuning to the network for each reference to this object
     */
    protected function getResults()
    {
        if (!$this->results) {
            $this->results = $this->resource->query(
                $this->getStorageIdentifier(),
                $this->query,
                $this->startIndex ? $this->startIndex : self::DEFAULT_START_INDEX,
                $this->numberResults ? $this->numberResults : self::DEFAULT_NUMBER_OF_RESULTS
            );
        }

        return $this->results;
    }
}