<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Thread>
 */
class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'scheduled_at' => $this->faker->randomElement([null, $this->faker->dateTimeBetween('-1 week', '+1 week'), $this->faker->dateTimeBetween('-1 week', '+1 week')]),
        ];
    }
}
