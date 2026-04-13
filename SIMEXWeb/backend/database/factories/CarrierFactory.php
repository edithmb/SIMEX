<?php

namespace Database\Factories;

use App\Models\Carrier;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Carrier>
 */
class CarrierFactory extends Factory
{
    protected $model = Carrier::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'city_id' => City::factory(),
        ];
    }
}
