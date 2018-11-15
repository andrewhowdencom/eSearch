<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Exceptions\InvalidEntityException;

/**
 * Responsible for expressing the state of a request when there is no other information about that request.
 *
 * @package App\Entities
 */
class Product extends Entity implements SchemaDefinition, \JsonSerializable
{
    const CONTEXT = 'http://schema.org';
    const TYPE    = 'Product';

    const INDEX = 'products';

    const SERIALISATION_ORDER = [
        'context',
        'type',
        'id',
        'name',
        'sku',
        'categoryIds',
    ];

    const REQUIRED_PROPERTIES = [
        'id',
        'name',
        'sku'
    ];

    /**
     * @var string UUID that references the product
     */
    private $id;

    /**
     * @var string Canonical text representation of the product name
     */
    private $name;

    /**
     * @var string The merchant identifier of the product
     */
    private $sku;

    /**
     * @var Category[]
     */
    private $categories;

    /**
     * @param array $properties
     *
     * @throws InvalidEntityException if the required properties are missing
     */
    public function __construct(array $properties)
    {
        foreach (self::REQUIRED_PROPERTIES as $property) {
            if (!array_key_exists($property, $properties)) {
                throw new InvalidEntityException(sprintf("Missing property '%s'"), $property);
            }
        }

        // Persist the data
        foreach ($properties as $property => $value) {
            $method = $this->getPropertySetter($property);

            $this->$method($value);
        }
    }

    public function getContext(): string
    {
        return self::CONTEXT;
    }

    public function getType(): string
    {
         return self::TYPE;
    }

    public function getIndex(): string
    {
        return self::INDEX;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Validates and sets the ID
     *
     * @param string $id;
     *
     * @return Product
     */
    private function setId(string $id): Product
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Sets the Name property
     *
     * @param string $name
     *
     * @return Product
     */
    private function setName(string $name): Product
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the SKU property
     *
     * @param string $sku
     *
     * @return Product
     */
    private function setSku(string $sku): Product
    {
        $this->sku = $sku;

        return $this;
    }

    /**
     * Expresses this object as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            '@context' => $this->getContext(),
            '@type'    => $this->getType(),
            'id'       => $this->getId(),
            'name'     => $this->getName(),
            'sku'      => $this->getSku()
        ];
    }

    /**
     * Expresses how this object should be serialised to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
