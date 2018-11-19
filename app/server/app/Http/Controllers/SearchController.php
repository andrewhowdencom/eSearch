<?php

namespace App\Http\Controllers;

use App\Entities\ItemList;
use App\Entities\Product;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Entities\Status;

class SearchController extends Controller implements Validatable
{
    const HEADER_CONTENT_TYPE = 'Content-Type';

    const CONTENT_TYPE_APPLICATION_JSON = 'application/json';

    const PAGINATION_DEFAULT_SIZE = 50;
    const PAGINATION_DEFAULT_INDEX = 0;

    /**
     * Allows querying the search database
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request)
    {
        // Todo: Move the logic out into a collection or search abstraction
        $client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();

        $params = [
            'index' => Product::INDEX,
            'q'     => $request->query('q'),
            'from'  => self::PAGINATION_DEFAULT_INDEX,
            'size'  => self::PAGINATION_DEFAULT_SIZE
        ];

        // Allow overriding size
        if ($request->query('pageSize')) {
            $params['size'] = $request->query('pageSize');
        }

        // Allow specifying page
        if ($request->query('pageToken')) {
            // Calculate the offset. The offset is $pageSIze less than the total of the given multipliers. For example,
            // if we're on page "4" with result sets of "50", we want products 150 → 200.
            $offset = ($request->query('pageToken') * $request->query('pageSize') - $request->query('pageSize'));
            $params['from'] = $offset;
        }

        // Performs the search in the lucene syntax.
        $results = $client->search($params);

        $hits = $results['hits']['hits'];
        $results = [];

        foreach ($hits as $hit) {
            $results[] = new Product(
                [
                    'id' => $hit['_source']['id'],
                    'name' => $hit['_source']['name'],
                    'sku' => $hit['_source']['sku']
                ]
            );
        }

        $list = new ItemList($results);

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
