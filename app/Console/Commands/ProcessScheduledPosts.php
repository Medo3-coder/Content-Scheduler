<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;

class ProcessScheduledPosts extends Command
{
    protected $signature = 'posts:process';

    protected $description = 'Publish scheduled posts that are due';

    public function handle(): int
    {
        $posts = Post::where('status', 'scheduled')
                     ->where('scheduled_time', '<=', now())
                     ->with('platforms', 'user')
                     ->get();

        if ($posts->isEmpty()) {
            $this->info('No posts to publish.');
            return Command::SUCCESS;
        }

        foreach ($posts as $post) {
            DB::transaction(function () use ($post) {
                // Mock publishing (set status)
                $post->status = 'published';
                $post->save();

                // Update platform pivot status
                foreach ($post->platforms as $platform) {
                    $post->platforms()->updateExistingPivot($platform->id, [
                        'platform_status' => 'published',
                    ]);
                }

                // Log activity
                ActivityLog::create([
                    'user_id' => $post->user_id,
                    'action' => 'Published Post',
                    'description' => "Post titled '{$post->title}' was published to platforms.",
                ]);

                $this->info("Published post: {$post->title}");
            });
        }

        return Command::SUCCESS;
    }
}
