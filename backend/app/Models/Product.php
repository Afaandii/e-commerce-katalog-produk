<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'product';

    protected $fillable = [
        'category_id',
        'type_id',
        'brand_id',
        'product_name',
        'price',
        'stock',
        'rating',
        'spesification_product',
        'information_product',
    ];
}
