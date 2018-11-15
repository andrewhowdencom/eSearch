<?php

declare(strict_types = 1);

namespace App\Entities;

use \PHPUnit\Framework\TestCase;
use \App\Exceptions\InvalidEntityException;

class ProductTest extends TestCase
{
    /**
     * Verifies the constructed object can be expressed as an array
     */
    public function testCanExpressAsArray()
    {
        $cmpArray = [
            '@context' => 'http://schema.org',
            '@type'    => 'Product',
            'id'       => '1b2e7790-e903-11e8-857a-0fced86608d1',
            'name'     => 'Wedding Dress',
            'sku'      => 'ABC12345'
        ];

        $product = new Product(
            [
                'id'   => '1b2e7790-e903-11e8-857a-0fced86608d1',
                'name' => 'Wedding Dress',
                'sku'  => 'ABC12345'
            ]
        );

        $this->assertEquals($cmpArray, $product->toArray());
    }

    // Test validates iD
    // Test validates SKU
    // Test requires missing properties
    // Test can fetch properties after query
    // Test validates category ids (or objects?)
}
