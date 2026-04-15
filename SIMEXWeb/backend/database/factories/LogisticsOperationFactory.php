<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\CommercialOffer;
use App\Models\LogisticsOperation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogisticsOperation>
 */
class LogisticsOperationFactory extends Factory
{
    protected $model = LogisticsOperation::class;

    public function definition(): array
    {
        return [
            'reference' => 'LO-' . fake()->unique()->numerify('####'),
            'commercial_offer_id' => CommercialOffer::factory(),
            'client_id' => Client::factory(),
            'status' => 'preparation',
            'etd' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'eta' => fake()->dateTimeBetween('+1 month', '+3 months'),
            'atd' => null,
            'ata' => null,
            'odoo_id' => null,
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }
}
