<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Entities\Status;

class SearchController extends Controller
{
    const HEADER_CONTENT_TYPE = 'Content-Type';

    const CONTENT_TYPE_APPLICATION_JSON = '';

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
        $status = new Status(
            Status::STATUS_FAILURE,
            Status::REASON_NOT_IMPLEMENTED,
            'This method is not yet implemented. Please try again later'
        );

        return (new Response(json_encode($status), Response::HTTP_NOT_IMPLEMENTED))
            ->withHeaders([self::HEADER_CONTENT_TYPE => self::CONTENT_TYPE_APPLICATION_JSON]);
    }
}
