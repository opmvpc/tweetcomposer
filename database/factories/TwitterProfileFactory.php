<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TwitterProfile>
 */
class TwitterProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $userName = $this->faker->userName();

        return [
            'twitter_id' => Str::uuid(),
            'nickname' => $userName,
            'avatar' => $this->faker->imageUrl($width = 128, $height = 128, $category = null, $randomize = true, $word = Str::of($userName)->upper(), $gray = false),
            'token' => $this->faker->sha256(),
            'token_secret' => $this->faker->sha256(),
        ];
    }
}
