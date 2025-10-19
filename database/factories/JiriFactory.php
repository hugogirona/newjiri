<?php

namespace Database\Factories;

use App\Models\Jiri;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Jiri>
 */
class JiriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->country(),
            'starts_at' => Carbon::now()->addDays(rand(1, 30)),
            'location' => fake()->city(),
            'description' => fake()->boolean()
                ? fake()->paragraphs(2, true)
                : null,
            'user_id' => User::factory(),
        ];
    }
}
