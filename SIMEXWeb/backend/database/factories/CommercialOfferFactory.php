<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\CommercialOffer;
use App\Models\ContainerType;
use App\Models\Incoterm;
use App\Models\Port;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CommercialOffer>
 */
class CommercialOfferFactory extends Factory
{
    protected $model = CommercialOffer::class;

    public function definition(): array
    {
        return [
            'reference' => 'CO-' . fake()->unique()->numerify('####'),
            'client_request_id' => ClientRequest::factory(),
            'client_id' => Client::factory(),
            'incoterm_id' => Incoterm::factory(),
            'origin_port_id' => Port::factory(),
            'destination_port_id' => Port::factory(),
            'container_type_id' => ContainerType::factory(),
            'price' => fake()->randomFloat(2, 100, 50000),
            'valid_until' => fake()->dateTimeBetween('+1 week', '+3 months'),
            'status' => 'draft',
            'rejection_reason' => null,
            'comments' => fake()->sentence(),
            'odoo_id' => null,
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    public function accepted(): static
    {
        return $this->state(fn () => ['status' => 'accepted']);
    }

    public function rejected(): static
    {
        return $this->state(fn () => ['status' => 'rejected', 'rejection_reason' => fake()->sentence()]);
    }
}
