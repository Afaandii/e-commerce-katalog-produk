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

    public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id', 'id');
    }

    public function typeProduct()
    {
        return $this->belongsTo(TypeProduct::class, 'type_id', 'id');
    }

    public function brandProduct()
    {
        return $this->belongsTo(BrandProduct::class, 'brand_id', 'id');
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }
}
