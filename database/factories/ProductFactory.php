<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            "supplier_id" => Supplier::inRandomOrder()->first()->id,
            "product_name" => "Coca Cola",
            "product_price" => '100.00',
            "product_image" => 'products/1728299441-6703c1b1c58c0.jpg',
        ];
    }
}
