<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Medium;
use App\Models\Thread;
use App\Models\Tweet;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->createUsers()->each(function (User $user) {
            $this->createTweets($user);
        });
    }

    protected function createUsers()
    {
        User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
        ;
        User::factory(9)->create();

        return User::all();
    }

    protected function createTweets(User $user)
    {
        $threadCount = $this->faker->numberBetween(20, 50);
        for ($i = 0; $i < $threadCount; ++$i) {
            $thread = Thread::factory()
                ->create([
                    'user_id' => $user->id,
                ])
            ;
            $this->createReplies($thread, $user);
        }
    }

    protected function createReplies(Thread $thread, User $user)
    {
        $tweetCount = $this->faker->numberBetween(0, 6);
        for ($j = 0; $j < $tweetCount; ++$j) {
            $reply = Tweet::factory()->for($thread, 'thread')->create();
            $this->addMedias($reply);
        }
    }

    protected function addMedias(Tweet $tweet)
    {
        $mediaCount = $this->faker->numberBetween(0, 4);
        for ($i = 0; $i < $mediaCount; ++$i) {
            Medium::factory()
                ->create([
                    'tweet_id' => $tweet->id,
                ])
            ;
        }
    }
}
