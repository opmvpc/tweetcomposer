<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Storage::disk('local')->deleteDirectory('public/users');

        User::factory()
            ->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ])
        ;

        $this->call([
            TestSeeder::class,
        ]);
    }
}
