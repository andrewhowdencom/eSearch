<?php

declare(strict_types = 1);

namespace App\Entities;

use \PHPUnit\Framework\TestCase;
use \App\Exceptions\InvalidEntityException;

class ItemListTest extends TestCase
{
    /**
     * @doesNotPerformAssertions
     */
    public function testValidConstruction()
    {
        $itemList = new ItemList([]);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testValidConstructionWithProduct()
    {
        $product = new Product(
            [
                'id' => '53818250-e916-11e8-9600-9b0bf2a4a441',
                'name' => 'FooProduct',
                'sku' => 'FooSku'
            ]
        );

        $itemList = new ItemList([$product]);
    }

    public function testInvalidWithDifferentTypes()
    {
        $this->expectException(InvalidEntityException::class);

        $items = [
            'foo',
            48,
            new Product(
                [
                    'id' => '53818250-e916-11e8-9600-9b0bf2a4a441',
                    'name' => 'FooProduct',
                    'sku' => 'FooSku'
                ]
            )
        ];

        $itemList = new ItemList($items);
    }

    /**
     * Make sure the class can be serialised to JSON correctly
     */
    public function testJsonSerializationWithProduct()
    {
        $comparison = json_encode(
            [
                '@context' => 'http://schema.org',
                '@type' => 'ItemList',
                'itemListElement' => [
                    [
                        '@context' => 'http://schema.org',
                        '@type' => 'Product',
                        'id' => '53818250-e916-11e8-9600-9b0bf2a4a441',
                        'name' => 'FooProduct',
                        'sku' => 'FooSku'
                    ]
                ]
            ]
        );

        $product = new Product(
            [
                'id' => '53818250-e916-11e8-9600-9b0bf2a4a441',
                'name' => 'FooProduct',
                'sku' => 'FooSku'
            ]
        );

        $itemList = new ItemList([$product]);

        $this->assertSame(
            $comparison,
            json_encode($itemList),
            "The JSON serilaized Status object was not as expected"
        );
    }
}
