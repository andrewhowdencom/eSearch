<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var $router Laravel\Lumen\Routing\Router */
$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->get(
    '/v1/search',
    [
        'middleware' => ['token', 'validate:\App\Http\Controllers\SearchController'],
        'uses' => 'SearchController@search'
    ]
);

// Stub function that describes project purpose
$router->get('/', function () {
    $content = <<<EOF
= eSearch API.

You can find more information at:

  - https://github.com/andrewhowdencom/esearch

If you're playing around, you can:

== Get a token

Visit:

  - https://api.esearch.local/auth/redirect

== Query

You will need a valid token set in a HTTP header in the following form:

  Authentication: Bearer \${TOKEN}

Then, visit:

  - https://api.esearch.local/v1/search?q=Fish
EOF;
    $response = new \Illuminate\Http\Response($content);
    $response->header('Content-type', 'text/plain');

    return $response;
});
