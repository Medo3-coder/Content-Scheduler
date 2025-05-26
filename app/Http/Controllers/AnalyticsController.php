<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Platform;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Get all platforms
        $platforms = Platform::all();

        // Count scheduled and published posts
        $scheduled = Post::where('user_id', $user->id)->where('status', 'scheduled')->count();
        $published = Post::where('user_id', $user->id)->where('status', 'published')->count();

        $total = $scheduled + $published;
        $successRate = $total > 0 ? round(($published / $total) * 100, 2) : 0;

        // Posts per platform
        $postsPerPlatform = [];
        foreach ($platforms as $platform) {
            $count = $platform->posts()
                ->where('user_id', $user->id)
                ->count();

            $postsPerPlatform[] = [
                'platform' => $platform->name,
                'type' => $platform->type,
                'total_posts' => $count,
            ];
        }

        return response()->json([
            'posts_per_platform' => $postsPerPlatform,
            'scheduled_count' => $scheduled,
            'published_count' => $published,
            'success_rate' => $successRate,
        ]);
    }
}
