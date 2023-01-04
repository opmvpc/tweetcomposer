<?php

namespace Database\Seeders;

use App\Models\Medium;
use App\Models\Thread;
use App\Models\Tweet;
use App\Models\TwitterProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
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
            $this->createTwitterProfiles($user);
            $this->createThreads($user);
        });
    }

    protected function createTwitterProfiles(User $user)
    {
        $profileCount = $this->faker->numberBetween(1, 3);
        for ($i = 0; $i < $profileCount; ++$i) {
            $twitterProfile = TwitterProfile::factory()
                ->create([
                    'user_id' => $user->id,
                ])
            ;

            $user->twitter_profile_id = $twitterProfile->id;
            $user->save();
        }
    }

    protected function createUsers()
    {
        User::factory(9)->create();

        return User::all();
    }

    protected function createThreads(User $user)
    {
        $threadCount = $this->faker->numberBetween(20, 50);
        $twitterProfilesIds = $user->twitterProfiles->pluck('id')->toArray();
        for ($i = 0; $i < $threadCount; ++$i) {
            $thread = Thread::factory()
                ->create([
                    'user_id' => $user->id,
                    'twitter_profile_id' => $this->faker->randomElement($twitterProfilesIds),
                ])
            ;
            $this->createTweets($thread);
        }
    }

    protected function createTweets(Thread $thread)
    {
        $tweetCount = $this->faker->numberBetween(1, 6);
        for ($j = 0; $j < $tweetCount; ++$j) {
            $tweet = Tweet::factory()->for($thread, 'thread')->create();
            $this->createMedia($tweet);
        }
    }

    protected function createMedia(Tweet $tweet)
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
