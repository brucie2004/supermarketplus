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
        
        
        $dairy = Category::create(['name' => 'Dairy & Eggs', 'slug' => 'dairy-eggs']);
        $bakery = Category::create(['name' => 'Bakery', 'slug' => 'bakery']);
        $fruits = Category::create(['name' => 'Fruits & Vegetables', 'slug' => 'fruits-vegetables']);
        $beverages = Category::create(['name' => 'Beverages', 'slug' => 'beverages']);
        
        Category::create(['name' => 'Milk', 'slug' => 'milk', 'parent_id' => $dairy->id]);
        Category::create(['name' => 'Cheese', 'slug' => 'cheese', 'parent_id' => $dairy->id]);
        Category::create(['name' => 'Yogurt', 'slug' => 'yogurt', 'parent_id' => $dairy->id]);

        Category::create(['name' => 'Water', 'slug' => 'water', 'parent_id' => $beverages->id]);
        Category::create(['name' => 'Juice', 'slug' => 'juice', 'parent_id' => $beverages->id]);
        Category::create(['name' => 'Soda', 'slug' => 'soda', 'parent_id' => $beverages->id]);


    }
}
