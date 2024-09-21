<?php

namespace Database\Seeders;

use App\Models\PriceList;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $totalUsers = 50;
        $usersWithPriceList = 30;

        $users = User::factory()->count($totalUsers)->create();
        $this->assignPriceLists($users, $usersWithPriceList);
    }

    private function assignPriceLists($users, int $count)
    {
        $priceLists = PriceList::all();

        foreach ($users->take($count) as $user) {
            $randomPriceList = $priceLists->random()->id;
            $user->price_list_id = $randomPriceList;
            $user->save();
        }
    }
}
