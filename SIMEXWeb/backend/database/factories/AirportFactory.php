<?php

namespace Database\Factories;

use App\Models\Airport;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airport>
 */
class AirportFactory extends Factory
{
    protected $model = Airport::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->lexify('???')),
            'name' => fake()->city() . ' Airport',
            'city_id' => City::factory(),
        ];
    }
}
