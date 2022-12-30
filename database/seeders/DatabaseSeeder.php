<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        \App\Models\User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
        ;
        \App\Models\User::factory(9)->create();

        $users = \App\Models\User::all();

        foreach ($users as $user) {
            $tweetCount = $faker->numberBetween(20, 50);
            for ($i = 0; $i < $tweetCount; ++$i) {
                $tweet = \App\Models\Tweet::factory()
                    ->create([
                        'user_id' => $user->id,
                    ])
                ;

                $mediaCount = $faker->numberBetween(0, 4);
                for ($j = 0; $j < $mediaCount; ++$j) {
                    \App\Models\Media::factory()
                        ->create([
                            'tweet_id' => $tweet->id,
                        ])
                    ;
                }
            }
        }
    }
}
