<?php

namespace Database\Seeders;

use App\Models\PriceModifier;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PriceModifierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        PriceModifier::create([
            'name' => '10% Discount',
            'type' => 'discount',
            'value' => 10.00,
            'amount_threshold' => 100.00,
            'quantity_threshold' => null,
            'is_percentage' => true,
            'is_active' => true,
        ]);

        $this->seedTaxModifiersForAllProducts();
    }

    public function seedTaxModifiersForAllProducts()
    {
        $taxModifier = PriceModifier::create([
            'name' => '25% Tax',
            'type' => 'tax',
            'value' => 25.00,
            'amount_threshold' => 0.00,
            'quantity_threshold' => null,
            'is_percentage' => true,
            'is_active' => true,
            'apply_to_order' => false,
        ]);

        $products = Product::whereDoesntHave('modifiers')->get();

        foreach ($products as $product) {
            DB::table('product_price_modifiers')->insert([
                'product_SKU' => $product->SKU,
                'modifier_id' => $taxModifier->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
