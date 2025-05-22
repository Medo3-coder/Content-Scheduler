<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        // Get all users to associate posts with
        $users = User::all();

        // Create 20 sample posts
        for ($i = 0; $i < 20; $i++) {
            Post::create([
                'title' => fake()->sentence(3),
                'content' => fake()->text(500),
                'image_url' => fake()->boolean(70) ? fake()->imageUrl(800, 600) : null,
                'scheduled_time' => fake()->boolean(70) ? Carbon::now()->addDays(rand(1, 30)) : null,
                'status' => fake()->randomElement(['draft', 'scheduled', 'published']),
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
