<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'image' => 'default-electronics.png'],
            ['name' => 'Clothing', 'image' => 'default-clothing.png'],
            ['name' => 'Chemicals', 'image' => 'default-chemicals.png'],
            ['name' => 'Machinery', 'image' => 'default-machinery.png'],
            ['name' => 'Food & Beverages', 'image' => 'default-food.png'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
