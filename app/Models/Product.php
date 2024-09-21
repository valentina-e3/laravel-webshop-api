<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'SKU';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name', 'description', 'price', 'SKU', 'published'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {});
    }

    private function generateSKU()
    {
        $nameCode = strtoupper((substr(preg_replace('/[^A-Za-z0-9]/', '', $this->name), 0, 3)));
        $brandCode = strtoupper(substr($this->brand, 0, 3));
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function modifiers()
    {
        return $this->belongsToMany(PriceModifier::class, 'product_price_modifiers', 'product_SKU', 'modifier_id');
    }
}
