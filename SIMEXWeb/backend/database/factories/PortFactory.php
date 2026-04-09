<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Port;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Port>
 */
class PortFactory extends Factory
{
    protected $model = Port::class;

    public function definition(): array
    {
        return [
            'name' => fake()->city() . ' Port',
            'city_id' => City::factory(),
        ];
    }
}
