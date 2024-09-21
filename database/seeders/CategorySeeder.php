<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->createCategories(20, 3, 3);
    }

    private function createCategories($numberOfCategories, $maxLevels, $maxSubCategories)
    {
        for ($i = 0; $i < $numberOfCategories; $i++) {
            $category = Category::factory()->create([
                'parent_id' => null,
            ]);

            $this->createSubcategories($category->id, $maxLevels, $maxSubCategories);
        }
    }

    private function createSubcategories($parentId, $level, $maxSubCategories)
    {
        if ($level <= 0) {
            return;
        }

        $numberOfSubcategories = rand(0, $maxSubCategories);

        for ($j = 0; $j < $numberOfSubcategories; $j++) {
            $subcategory = Category::factory()->create([
                'parent_id' => $parentId,
            ]);

            $this->createSubcategories($subcategory->id, $level - 1, $maxSubCategories);
        }
    }
}
