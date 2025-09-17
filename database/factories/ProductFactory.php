<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $categoryIds = \App\Models\Category::pluck('id')->toArray();

        return [
             'name' => $this->faker->words(3, true), // e.g., "Fresh Organic Milk"
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomFloat(2, 1, 100), // price between 1 and 100
            'image' => 'https://via.placeholder.com/300?text=Product+Image',
            'category_id' => $this->faker->randomElement($categoryIds),
            'stock_quantity' => $this->faker->numberBetween(0, 200),
        ];
    }
}
