<?php

namespace App\Modifiers;

/**
 * Class DiscountModifier
 *
 * Represents a discount modifier that can apply either a percentage or a fixed amount discount
 * to a given price based on specified thresholds for amount and quantity.
 */
class DiscountModifier implements PriceModifierInterface
{
    protected float $value;
    protected bool $isValuePercentage;
    protected ?float $amountThreshold;
    protected ?int $quantityThreshold;

    /**
     * DiscountModifier constructor.
     *
     * @param float $value The discount value (percentage or fixed amount).
     * @param bool $isValuePercentage Whether the discount is a percentage.
     * @param float|null $amountThreshold Minimum amount for discount to apply.
     * @param int|null $quantityThreshold Minimum quantity for discount to apply.
     */
    public function __construct(
        float $value,
        bool $isValuePercentage = true,
        ?float $amountThreshold = null,
        ?int $quantityThreshold = null
    ) {
        $this->value = $value;
        $this->isValuePercentage = $isValuePercentage;
        $this->amountThreshold = $amountThreshold;
        $this->quantityThreshold = $quantityThreshold;
    }

    /**
     * Apply discount to the price based on thresholds.
     *
     * @param float $price The original price of the product.
     * @param int $quantity The quantity of the product being purchased.
     * @return float The price after the discount is applied.
     */
    public function apply(float $price, int $quantity = 0): float
    {
        if ($this->shouldApplyDiscount($price, $quantity)) {
            return $this->applyDiscount($price);
        }

        return $price;
    }

    /**
     * Determine if the discount applies based on thresholds.
     *
     * @param float $price
     * @param int $quantity
     * @return bool
     */
    protected function shouldApplyDiscount(float $price, int $quantity): bool
    {
        return ($this->amountThreshold !== null && $price >= $this->amountThreshold)
            || ($this->quantityThreshold !== null && $quantity >= $this->quantityThreshold);
    }

    /**
     * Apply the discount to the price.
     *
     * @param float $price
     * @return float
     */
    protected function applyDiscount(float $price): float
    {
        return $this->isValuePercentage
            ? round($price * (1 - $this->value / 100), 2)
            : round($price - $this->value, 2);
    }
}
