<?php

namespace App\Http\Controllers;

use App\Entities\ItemList;
use App\Exceptions\InvalidEntityException;
use App\Entities\Collection\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SearchController extends Controller implements Validatable
{
    const HEADER_CONTENT_TYPE = 'Content-Type';

    const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

    const PAGINATION_DEFAULT_SIZE = 50;
    const PAGINATION_DEFAULT_INDEX = 0;

    private $collection;

    public function __construct(
        \App\Entities\Collection\Product $collection
    )
    {
        $this->collection = $collection;
    }

    /**
     * Allows querying the search database
     *
     * @param Request $request
     *
     * @return Response
     * @throws InvalidEntityException in the case the product collection failed to construct correctly
     */
    public function search(Request $request)
    {
        $this->collection->setQuery($request->query('q'));

        // Set some defaults on the number of results that can be later overriden.
        $this->collection->setStartIndex(Product::DEFAULT_START_INDEX);
        $this->collection->setNumberResults(Product::DEFAULT_NUMBER_OF_RESULTS);

        // Allow overriding size
        if ($request->query('pageSize')) {
            $this->collection->setNumberResults($request->query('pageSize'));
        }

        // Allow specifying page
        if ($request->query('pageToken')) {
            // Calculate the offset. The offset is $pageSize less than the total of the given multipliers. For example,
            // if we're on page "4" with result sets of "50", we want products 150 → 200.
            $offset = ($request->query('pageToken') * $request->query('pageSize') - $request->query('pageSize'));
            $this->collection->setStartIndex($offset);
        }

        // InvalidEntityException will be deferred to the global error handler, which returns the status object
        // indicating query failure
        $list = new ItemList($this->collection->toArray());

        return (new Response(json_encode($list), Response::HTTP_OK))
            ->withHeaders([self::HEADER_CONTENT_TYPE => self::CONTENT_TYPE_APPLICATION_JSON]);
    }

    /**
     * Returns the parameters either in the query or the body required for this request, in the form required by
     * Laravels validation library
     *
     * See https://laravel.com/docs/5.7/validation#quick-writing-the-validation-logic
     *
     * @return array
     */
    public static function getRequiredParameters(): array
    {
        return [
            'q' => 'required'
        ];
    }
}
