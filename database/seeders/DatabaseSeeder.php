<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\Category::factory()->create([
            'title' => fake()->title(),
            'description' => fake()->titleFemale(),
            'status' => fake()->boolean(50),
        ]);
    }
}
