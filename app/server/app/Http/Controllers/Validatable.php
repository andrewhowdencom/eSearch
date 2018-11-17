<?php

namespace App\Http\Controllers;

interface Validatable
{
    public static function getRequiredParameters(): array;
}
