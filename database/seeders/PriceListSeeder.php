<?php

namespace Database\Seeders;

use App\Models\PriceList;
use App\Models\PriceListItem;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->createPriceLists(20, 1000);
    }

    private function createPriceLists(int $numOfPriceLists, int $maxNumOfProduct)
    {
        for ($i = 0; $i < $numOfPriceLists; $i++) {
            $priceList = PriceList::factory()->create();
            $this->attachProductsToPriceList($priceList, $maxNumOfProduct);
        }
    }

    private function attachProductsToPriceList(PriceList $priceList, int $maxNumOfProduct)
    {
        $numProducts = rand(1, $maxNumOfProduct);
        $products = Product::inRandomOrder()->take($numProducts)->get();

        foreach ($products as $product) {
            PriceListItem::factory()->create([
                'price_list_id' => $priceList->id,
                'SKU' => $product->SKU,
            ]);
        }
    }
}
