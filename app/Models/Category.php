<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'parent_id'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }

    /**
     * Recursively retrieve all IDs of the current category and its subcategories.
     *
     * @return array An array of IDs for the current category and its subcategories.
     */
    public function getAllSubcategoryIds(): array
    {
        $ids = [$this->id];

        foreach ($this->subcategories as $subcategory) {
            $ids = array_merge($ids, $subcategory->getAllSubcategoryIds());
        }

        return $ids;
    }
}
