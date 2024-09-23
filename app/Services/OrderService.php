<?php

namespace App\Services;

use App\Models\PriceModifier;
use App\Models\Product;
use App\Models\User;

class OrderService
{
    protected $modifiers = [];
    protected $priceResolutionService;
    protected $priceModifierService;

    /**
     * OrderService constructor.
     *
     * Initializes the service by injecting the PriceResolutionService and PriceModifierService.
     * Also loads all active price modifiers that should be applied to the entire order.
     *
     * @param PriceResolutionService $priceResolutionService
     * @param PriceModifierService $priceModifierService
     */
    public function __construct(PriceResolutionService $priceResolutionService, PriceModifierService $priceModifierService)
    {
        $this->priceResolutionService = $priceResolutionService;
        $this->priceModifierService = $priceModifierService;

        $priceModifiers = PriceModifier::where('is_active', true)
            ->where('apply_to_order', true)
            ->get();

        $this->modifiers = $this->priceModifierService->handle($priceModifiers);
    }

    /**
     * Check if the total amount for the order matches the provided amount.
     *
     * This method calculates the total order amount based on the product list and
     * their corresponding prices, applying any product-specific and order-level modifiers.
     * It returns true if the calculated amount matches the provided amount.
     *
     * @param array $productList
     * @param float $amount
     * @param User|null $user
     * @return bool
     */
    public function checkOrderAmount(array $productList, float $amount, User $user = null): bool
    {
        $totalAmount = 0;

        foreach ($productList as $productListItem) {
            $product = Product::where('SKU', $productListItem['SKU'])
                ->where('published', true)
                ->first();

            if ($product == null) {
                return false; // Invalid product
            }

            // Apply product-specific modifiers
            $productModifiers = $this->priceModifierService->handle($product->modifiers);
            $price = $productListItem['price'];
            foreach ($productModifiers as $modifier) {
                $price = $modifier->apply($price);
            }
            $totalAmount += round($price * $productListItem['quantity'], 2);
        }

        // Apply order-level modifiers
        foreach ($this->modifiers as $modifier) {
            $totalAmount = $modifier->apply($totalAmount);
        }

        // Compare calculated total with the provided amount
        return bccomp($totalAmount, $amount, 2) === 0;
    }

    /**
     * Validate that the prices in the product list match the correct prices for the user.
     * Also checks if the product is not published.
     *
     * @param array $productList
     * @param User|null $user
     * @return bool
     */
    public function checkOrderItemsValidity(array $productList, User $user = null): bool
    {
        foreach ($productList as $productListItem) {
            $product = Product::where('SKU', $productListItem['SKU'])->where('published', true)->first();

            if ($product == null) {
                return false;
            }

            $correctPrice = $this->priceResolutionService->getProductPriceForUser($product, $user);

            if (bccomp($correctPrice, $productListItem['price'], 2) !== 0) {
                return false;
            }
        }
        return true;
    }
}
