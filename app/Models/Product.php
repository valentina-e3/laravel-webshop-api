<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'SKU';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['name', 'description', 'price', 'SKU', 'published'];

    /**
     * Scope a query to include the final price of products for a specific user.
     *
     * This query joins the `contract_lists` and `price_list_items` tables to determine
     * the final price of each product based on the user's contract price, price list,
     * or default product price. The order of precedence is:
     * 1. The user's contract price (`contract_lists.price`)
     * 2. The price from the price list (`price_list_items.price`)
     * 3. The default product price (`products.price`)
     *
     * If the user has a contract price or a price list, it will override the default product price.
     *
     * @param Builder $query
     * @param User $user
     *
     * @return Builder
     */
    public function scopeWithFinalPrice(Builder $query, User $user): Builder
    {
        return $query->where('published', true)
            ->leftJoin('contract_lists', function ($join) use ($user) {
                $join->on('products.SKU', '=', 'contract_lists.SKU')
                    ->where('contract_lists.user_id', '=', $user->id);
            })
            ->leftJoin('price_list_items', function ($join) use ($user) {
                $join->on('products.SKU', '=', 'price_list_items.SKU')
                    ->where('price_list_items.price_list_id', '=', $user->price_list_id);
            })
            ->select(
                'products.*',
                DB::raw('COALESCE(contract_lists.price, price_list_items.price, products.price) AS final_price')
            );
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
