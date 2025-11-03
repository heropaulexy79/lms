<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Use firstOrCreate to prevent errors on re-seeding
        User::firstOrCreate(
            ['email' => 'test@example.com'], // Check if this user exists
            [ // If not, create them with this data
                'name' => 'Test User',
                'password' => Hash::make('password'), // Add a default password
                'email_verified_at' => now(),
            ]
        );

        // Call your other seeders
        $this->call(CourseTemplateSeeder::class);
    }
}

