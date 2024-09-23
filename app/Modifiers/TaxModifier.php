<?php

namespace App\Modifiers;

/**
 * Class TaxModifier
 *
 * Represents a tax modifier that applies a percentage-based tax to a price.
 */
class TaxModifier implements PriceModifierInterface
{
    protected float $value;

    /**
     * TaxModifier constructor.
     *
     * @param float $value The percentage of the tax to be applied (e.g., 25.00 for 25%).
     */
    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Apply the tax modifier to a given price.
     *
     * @param float $price The original price of the product.
     * @param int $quantity The quantity of the product being purchased, not used in this modifier.
     *
     * @return float The price after tax has been added.
     */
    public function apply(float $price, int $quantity = 0): float
    {
        return round($price + ($price * ($this->value / 100)), 2);
    }
}
