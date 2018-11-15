<?php

declare(strict_types = 1);

namespace App\Integration\Entities;

use \App\Entities\Product;
use Laravel\Lumen\Testing\TestCase;
use \App\Exceptions\InvalidEntityException;

class ProductTest extends TestCase
{
    /**
     * Bootstraps the application
     *
     * @return mixed|\Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        return require __DIR__.'/../../../bootstrap/app.php';
    }

    /**
     * Verifies the constructed object can be expressed as an array
     */
    public function testCanPersistToElasticSearch()
    {
        $product = new Product(
            [
                'id'   => '1b2e7790-e903-11e8-857a-0fced86608d1',
                'name' => 'Wedding Dress',
                'sku'  => 'ABC12345'
            ]
        );

        $product->save();
    }
}
