<?php

namespace Database\Seeders;

use App\Models\ContractList;
use App\Models\PriceListItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContractListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $totalUsers = 40;
        $maxNumOfProducts = 2000;

        $users = User::inRandomOrder()->take($totalUsers)->get();

        foreach ($users as $user) {
            $this->createContractListForUser($user, $maxNumOfProducts);
        }

        $this->addContractListForRandomUser();
    }

    private function createContractListForUser(User $user, int $maxNumOfProducts)
    {
        $numProducts = rand(1, $maxNumOfProducts);
        $products = Product::inRandomOrder()->take($numProducts)->get();

        foreach ($products as $product) {
            ContractList::factory()->create([
                'user_id' => $user->id,
                'SKU' => $product->SKU,
            ]);
        }
    }

    /**
     * Add contract lists for a random user with a price list.
     */
    private function addContractListForRandomUser()
    {
        $users = User::has('priceList')->get();

        foreach ($users as $user) {
            $priceListItems = PriceListItem::where('price_list_id', $user->price_list_id)->get();

            foreach ($priceListItems as $priceListItem) {
                if (rand(0, 1)) {
                    ContractList::factory()->create([
                        'user_id' => $user->id,
                        'SKU' => $priceListItem->SKU,
                    ]);
                }
            }
        }
    }
}
