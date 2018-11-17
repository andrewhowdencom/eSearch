<?php

declare(strict_types = 1);

namespace App\Entities;

use App\Exceptions\InvalidEntityException;

/**
 * Responsible for expressing the state of a request when there is no other information about that request.
 *
 * @package App\Entities
 */
class ItemList implements SchemaDefinition, \JsonSerializable
{
    const CONTEXT = 'http://schema.org';
    const TYPE    = 'ItemList';

    private $itemListElement;

    /**
     * Status constructor.
     *
     * @param array $elements
     */
    public function __construct(array $elements)
    {
        $this->setItemListElement($elements);
    }

    /**
     * Validates the elements are all of the same type.
     *
     * @param array Entity|String $elements
     * @throws InvalidEntityException if elements in a list are different
     */
    private function setItemListElement(array $elements)
    {
        // If there are no elements, all the "nothings" are of the same type.
        if (count($elements) === 0) {
            $this->itemListElement = [];
            return;
        }

        $sample = $elements[0];
        $comparisonType = gettype($sample);

        if ($comparisonType === "object") {
            $comparisonClass = get_class($sample);
        }

        foreach ($elements as $element) {
            // Determine if there are differnet individual types
            if (gettype($element) !== $comparisonType) {
                throw new InvalidEntityException("Cannot construct list elements with items of different types");
            }

            if ($comparisonType === "object" && get_class($element) !== $comparisonClass) {
                throw new InvalidEntityException("Cannot construct list elements with items of different objects");
            }
        }

        $this->itemListElement = $elements;
    }

    public function getContext(): string
    {
        return self::CONTEXT;
    }

    public function getType(): string
    {
         return self::TYPE;
    }

    /**
     * Returns the components this entity is storing
     */
    public function getItemListElement(): array
    {
        return $this->itemListElement;
    }

    /**
     * Expresses how this object should be serialised to JSON
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            '@context'        => $this->getContext(),
            '@type'           => $this->getType(),
            'itemListElement' => $this->getItemListElement()
        ];
    }
}
