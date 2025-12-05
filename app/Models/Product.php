<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'category',
        'sku'
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => number_format($value, 2),
            set: fn ($value) => str_replace(',', '', $value)
        );
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function getFirstImageAttribute()
    {
        return $this->images->first()->image_path ?? 'default-product.jpg';
    }
}