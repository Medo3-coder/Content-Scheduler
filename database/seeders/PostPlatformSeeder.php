<?php

namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Post;
use Illuminate\Database\Seeder;

class PostPlatformSeeder extends Seeder
{
    public function run()
    {
        $posts = Post::all();
        $platforms = Platform::all();
        $statuses = ['pending', 'published'];

        foreach ($posts as $post) {
            $selectedPlatforms = $platforms->random(rand(1, 3));

            foreach ($selectedPlatforms as $platform) {
                $post->platforms()->attach($platform->id, [
                    'platform_status' => fake()->randomElement($statuses),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}