<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ContractList;
use App\Models\PriceListItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PriceResolutionService
{
    /**
     * Get the applicable price for the product based on the current user.
     *
     * @param Product $product
     * @param User $user
     * @return float
     */
    public function getProductPriceForUser(Product $product, User $user = null)
    {
        if (!$user) {
            return $product->price;
        }

        $contract = ContractList::where('user_id', $user->id)
            ->where('SKU', $product->SKU)
            ->first();

        if ($contract) {
            return $contract->price;
        }

        if ($user->price_list_id) {
            $priceListItem = PriceListItem::where('price_list_id', $user->price_list_id)
                ->where('SKU', $product->SKU)
                ->first();

            if ($priceListItem) {
                return $priceListItem->price;
            }
        }

        return $product->price;
    }
}
