<?php

namespace App\Providers;

use App\Resource\ElasticSearch;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Lumen does not have the db.connection object
        // https://github.com/GeneaLabs/laravel-model-caching/issues/148
        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });

        $this->app->alias('db.connection', \Illuminate\Database\ConnectionInterface::class);

        // Bind the ES implementation to the DI container
        $this->app->bind('\App\Resource\CollectionInterface', ElasticSearch::class);
    }
}
