<?php

namespace Database\Factories;

use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoginSession>
 */
class LoginSessionFactory extends Factory
{
    protected $model = LoginSession::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'device_type' => 'desktop',
            'logged_in_at' => now(),
            'logged_out_at' => null,
            'token_expires_at' => now()->addDay(),
        ];
    }
}
