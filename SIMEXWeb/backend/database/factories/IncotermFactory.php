<?php

namespace Database\Factories;

use App\Models\Incoterm;
use App\Models\IncotermType;
use App\Models\TrackingStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Incoterm>
 */
class IncotermFactory extends Factory
{
    protected $model = Incoterm::class;

    public function definition(): array
    {
        return [
            'incoterm_type_id' => IncotermType::factory(),
            'tracking_step_id' => TrackingStep::factory(),
            'order_num' => fake()->numberBetween(1, 10),
        ];
    }
}
