<?php

namespace App\Services;

use App\Modifiers\DiscountModifier;
use App\Modifiers\TaxModifier;
use Illuminate\Support\Collection;

class PriceModifierService
{
    /**
     * Turns a collection of PriceModifier models into an array of classes that implement PriceModifierInterface.
     *
     * @param Collection $priceModifiers The collection of PriceModifier models.
     * @return array An array of PriceModifierInterface objects.
     */
    public function handle(Collection $priceModifiers): array
    {
        return $priceModifiers
            ->sortBy(function ($modifier) {
                return $modifier->type === 'discount' ? 0 : 1; // Discount first, then tax
            })
            ->map(function ($modifier) {
                if ($modifier->type === 'tax') {
                    return new TaxModifier($modifier->value);
                } elseif ($modifier->type === 'discount') {
                    return new DiscountModifier(
                        $modifier->value,
                        $modifier->is_percentage,
                        $modifier->amount_threshold
                    );
                }
                return null;
            })
            ->filter()
            ->all();
    }
}
