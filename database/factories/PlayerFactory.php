<?php

namespace Database\Factories;

use App\Models\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'credential_code' => fake()->unique()->regexify('[A-Za-z0-9]{4}'),
            'gender_id' => Gender::MALE,
            'ability' => fake()->numberBetween(1,100),
            'streng' => fake()->numberBetween(1,10),
            'speed' => fake()->numberBetween(1,10),
            'reaction' => fake()->numberBetween(1,10),
            'won_games' => 0,
            'lost_games' => 0
        ];
    }
}
