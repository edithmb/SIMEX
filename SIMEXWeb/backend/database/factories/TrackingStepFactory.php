<?php

namespace Database\Factories;

use App\Models\TrackingStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrackingStep>
 */
class TrackingStepFactory extends Factory
{
    protected $model = TrackingStep::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }
}
