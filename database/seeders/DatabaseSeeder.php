<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ecospace;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a known test user (idempotent)
        User::firstOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Test User',
        ]);

        // Create additional random users
        User::factory(9)->create();

        // Create sample ecospaces and events
        Ecospace::factory(15)->create();
        Event::factory(30)->create();

        // Seed reviews after ecospaces/events are present
        $this->call(ReviewSeeder::class);

        // Note: province/recipe seeders removed from call list because their files
        // are not present in this repository. Add them back here if you create
        // `ProvinceSeeder` or `RecipeSeeder` classes.
    }
}
