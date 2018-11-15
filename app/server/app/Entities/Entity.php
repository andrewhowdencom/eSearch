<?php

declare(strict_types = 1);

namespace App\Entities;

/**
 * Provides various helper methods for entities.
 *
 * Class Entity
 */
abstract class Entity
{
    /**
     * Determines the function that should be called to store properties passed in via Array
     *
     * @param string $property
     * @param string $action
     * @return string
     */
    protected function getPropertySetter(string $property)
    {
        return "set" . ucfirst($property);
    }
}
