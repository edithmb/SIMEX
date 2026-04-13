<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientRequest;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientRequest>
 */
class ClientRequestFactory extends Factory
{
    protected $model = ClientRequest::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'volume_m3' => fake()->randomFloat(2, 1, 100),
            'gross_weight_kg' => fake()->randomFloat(2, 10, 5000),
            'comments' => fake()->sentence(),
            'origin_id' => Location::factory(),
            'destination_id' => Location::factory(),
            'created_by' => User::factory(),
            'estado' => 'enviado',
        ];
    }
}
