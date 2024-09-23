<?php

namespace App\Modifiers;

/**
 * Interface PriceModifierInterface
 *
 * Represents a contract for any price modifier.
 */
interface PriceModifierInterface
{

    /**
     * Apply a modification to the price based on certain conditions.
     *
     * @param float $price The original price of the product or the whole order.
     * @param int $quantity The quantity of products being purchased.
     *
     * @return float The modified price after applying the price modifier.
     */
    public function apply(float $price, int $quantity = 0): float;
}
