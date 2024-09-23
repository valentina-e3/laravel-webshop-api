<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PriceModifier;
use App\Models\OrderPriceModifier;
use App\Models\OrderItemPriceModifier;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    /**
     * Save the order and related order items and modifiers
     *
     * @param array $orderData
     * @param array $products
     * @return Order
     */
    public function create(array $orderData, array $products): Order
    {
        return DB::transaction(function () use ($orderData, $products) {
            $order = Order::create($orderData);

            foreach ($products as $product) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'SKU' => $product['SKU'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);

                $this->saveOrderItemModifiers($orderItem);
            }

            $this->saveOrderModifiers($order);

            return $order;
        });
    }

    /**
     * Save the price modifiers for an order item
     *
     * @param OrderItem $orderItem
     */
    private function saveOrderItemModifiers(OrderItem $orderItem): void
    {
        $modifiers = $orderItem->product->modifiers;
        foreach ($modifiers as $modifier) {
            OrderItemPriceModifier::create([
                'order_item_id' => $orderItem->id,
                'modifier_id' => $modifier->id,
            ]);
        }
    }

    /**
     * Save the price modifiers for the entire order
     *
     * @param Order $order
     */
    private function saveOrderModifiers(Order $order): void
    {
        $modifiers = PriceModifier::where('is_active', true)
            ->where('apply_to_order', true)
            ->get();
        foreach ($modifiers as $modifier) {
            OrderPriceModifier::create([
                'order_id' => $order->id,
                'modifier_id' => $modifier['id'],
            ]);
        }
    }
}
