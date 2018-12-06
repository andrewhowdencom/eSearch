<?php

declare(strict_types = 1);

namespace App\Entities\Collection;

use App\Entities\Product as ProductEntity;
use App\Exceptions\InvalidEntityException;

/**
 * @todo: This will need tests. How do we abstract out the data component? Need to think about this.
 *        Update: Can do it via reflection, setting the results property of the abstract method.
 *                Kind of nasty, but what can you do. Set the results? That's a little nonsensical`
 *
 * @todo: This class will be responsible for hydrading the data component.
 * @todo: Implement ArrayAccess, Iterable
 *
 */
class Product extends EntityAbstract
{
    const INDEX = 'products';

    /**
     * Returns the ElasticSearch index that holds this particular data.
     *
     * @return string
     */
    public function getStorageIdentifier()
    {
        return self::INDEX;
    }

    /**
     * @return array
     *
     * @throws InvalidEntityException in the case the results returned from ElasticSearch violate constraints required
     *                                by the product constructor.
     */
    public function toArray()
    {
        $results = [];

        foreach ($this->getResults() as $result) {
            $results[] = new ProductEntity(
                [
                    'id' => $result['id'],
                    'name' => $result['name'],
                    'sku' => $result['sku']
                ]
            );
        }

        return $results;
    }
}
