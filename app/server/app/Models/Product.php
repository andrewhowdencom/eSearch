<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Elasticquent\ElasticquentTrait;

/**
 * This class represents the Product property within the ElasticSearch index.
 *
 * @package App\Models
 */
class Product extends Model
{
    use ElasticquentTrait;
}
