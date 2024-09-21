<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = Category::all();
        $maxProductsPerCategory = 500;
        $this->addProductsToCategories($categories, $maxProductsPerCategory);
        $this->assignProductsToSecondCategories(300);
        $this->unpublishRandomProducts(50);
    }

    private function addProductsToCategories($categories, $maxProducts)
    {
        foreach ($categories as $category) {
            $numberOfProducts = rand(0, $maxProducts);
            for ($i = 0; $i < $numberOfProducts; $i++) {
                $product = Product::factory()->create();
                $product->categories()->attach($category->id);
            }
        }
    }

    private function assignProductsToSecondCategories($amount)
    {
        $categories = Category::all();
        $products = Product::inRandomOrder()->take($amount)->get();

        foreach ($products as $product) {
            $availableCategories = $categories->where('id', '!=', $product->categories()->first()->id);
            if ($availableCategories->isNotEmpty()) {
                $secondCategory = $availableCategories->random();
                $product->categories()->attach($secondCategory->id);
            }
        }
    }

    private function unpublishRandomProducts(int $count)
    {
        $productsToUnpublish = Product::inRandomOrder()->take($count)->get();

        foreach ($productsToUnpublish as $product) {
            $product->published = false;
            $product->save();
        }
    }
}
