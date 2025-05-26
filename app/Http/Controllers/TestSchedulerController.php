<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;

class TestSchedulerController extends Controller
{
    /**
     * Test the post scheduler for a specific user.
     * Creates a test post and processes it immediately.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function testScheduler(int $userId): JsonResponse
    {
        $user = User::findOrFail($userId);

        // Create immediate post
        $post = $user->posts()->create([
            'title' => 'Test Post for User ' . $user->id,
            'content' => 'Test content',
            'status' => 'scheduled',
            'scheduled_time' => now()->subMinute(),
        ]);

        // Attach platforms
        $post->platforms()->attach([1, 2], [
            'platform_status' => 'pending'
        ]);

        // Process the post
        Artisan::call('posts:process');

        return response()->json([
            'post' => $post->fresh(),
            'platforms' => $post->platforms
        ]);
    }
}
