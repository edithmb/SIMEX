<?php

namespace Database\Factories;

use App\Models\IncotermType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IncotermType>
 */
class IncotermTypeFactory extends Factory
{
    protected $model = IncotermType::class;

    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->lexify('???')),
            'name' => fake()->word(),
        ];
    }
}
