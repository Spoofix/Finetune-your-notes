<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DomainsfeedFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['processing', 'ready', 're-load'];
        $user_id = ['2', '3', '4', '5'];
        return [
            'name' => $this->faker->domainName,
            'status' => $this->faker->randomElement($statuses),
            'user_id' => $this->faker->randomElement($user_id),

        ];
    }
}
