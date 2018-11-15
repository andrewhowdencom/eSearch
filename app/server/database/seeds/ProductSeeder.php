<?php

use Illuminate\Database\Seeder;
use Elasticsearch\ClientBuilder;
use Faker\Factory;
use App\Entities\Product;

// phpcs:disable -- Can't be in a namespace.
class ProductSeeder extends Seeder
{
    private $products = [
        'Wedding Dress',
        'Shoe',
        'Cape',
        'Fork',
        'Fish',
        'Bear',
        'Cup',
        'Boxing Glove',
        'Hammer',
        'Bottle'
    ];

    private $attributes = [
        'Blue',
        'Yellow',
        'Large',
        'Scary',
        'Garden',
        'Uncomfortable',
        'Twin',
        'Fresh',
        'Quality',
        'Sharp'
    ];

    /**
     * Creates a whole series of products in the ElasticSearch index for later querying
     */
    public function run()
    {
        $faker = Factory::create();
        $client = ClientBuilder::create()
            ->setHosts([config('elasticsearch.host')])
            ->build();

        // Index
        $idxParams = ['index' => \App\Entities\Product::INDEX];

        // Recreate the index from scratch
        $client->indices()->delete($idxParams);
        $client->indices()->create($idxParams);

        for ($i = 0; $i <= 1000; $i++) {
            $k = array_rand($this->products);

            $product = new Product([
                'id' => $faker->uuid,
                'name' => $this->makeProductName(),
                'sku' => $faker->uuid
            ]);

            $product->save();
        }
    }

    /**
     * Creates a random composite name based on the attributes and products at the top of the class
     *
     * @return string
     */
    private function makeProductName(): string
    {
        $j = array_rand($this->attributes);
        $k = array_rand($this->products);

        return sprintf('%s %s', $this->attributes[$j], $this->products[$k]);
    }
}
