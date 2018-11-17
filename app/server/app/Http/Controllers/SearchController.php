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

    /**
     * Allows querying the search database
     *
     * @param Request $request
     *
     * @return Response
     */
    public function search(Request $request)
    {
        // Todo: Authentication
        // Todo: Validate via middleware.
        // Todo: Move the logic out into a collection or search abstraction
        $client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();

        $results = $client->search([
            'index' => Product::INDEX,
            'type' => 'object',
            'body' => [
                'query' => [
                    'match_all' => new \StdClass()
                ]
            ]
        ]);

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
