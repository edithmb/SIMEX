<?php

namespace Database\Factories;

use App\Models\ContainerType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContainerType>
 */
class ContainerTypeFactory extends Factory
{
    protected $model = ContainerType::class;

    public function definition(): array
    {
        return [
            'type_name' => fake()->unique()->randomElement(['20GP', '40GP', '40HC', '20RF', '40RF', '20OT', '40OT']),
        ];
    }
}
