<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Client;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        return [
            'name' => fake()->address(),
            'client_id' => Client::factory(),
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'city_id' => City::factory(),
        ];
    }
}
