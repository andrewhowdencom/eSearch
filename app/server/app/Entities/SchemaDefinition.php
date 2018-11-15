<?php

declare(strict_types = 1);

namespace App\Entities;

interface SchemaDefinition
{
    /**
     * Returns the "Type" of the Schema.
     *
     * For example,
     *  - "Thing"
     *  - "Product"
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Returns the "Context" of a schema; where it is defined.
     *
     * For example,
     *   - Schema.org
     *
     * @return string
     */
    public function getContext(): string;
}
