<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\ShippingLine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ShippingLine>
 */
class ShippingLineFactory extends Factory
{
    protected $model = ShippingLine::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'city_id' => City::factory(),
        ];
    }
}
