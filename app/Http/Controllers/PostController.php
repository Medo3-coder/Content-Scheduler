<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\ActivityLog;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    // GET /posts?status=scheduled&date=2025-05-22
    public function index(Request $request)
    {
        // Debug user
        $user = $request->user();

        $query = Post::with('platforms')
            ->where('user_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date')) {
            $query->whereDate('scheduled_time', $request->date);
        }
        $posts = $query->latest()->get();
        return response()->json($posts);
    }

    public function store(StorePostRequest $request)
    {
        $user = $request->user();

        // Daily scheduling limit
        $scheduledCount = Post::where('user_id', $user->id)
            ->whereDate('scheduled_time', date('Y-m-d', strtotime($request->scheduled_time)))
            ->count();

        if ($scheduledCount >= 10) {
            return response()->json([
                'message' => 'You can only schedule up to 10 posts per day.',
            ], 403);
        }

        DB::beginTransaction();

        $post = Post::create([
            'user_id'        => $user->id,
            'title'          => $request->title,
            'content'        => $request->content,
            'image_url'      => $request->image_url,
            'scheduled_time' => $request->scheduled_time,
            'status'         => 'scheduled',
        ]);

        $post->platforms()->attach(
            $request->platform_ids,
            ['platform_status' => 'pending']
        );

        DB::commit();

        ActivityLog::create([
            'user_id'     => $user->id,
            'action'      => 'Created Post',
            'description' => "Created a post titled '{$post->title}'",
        ]);

        return response()->json([
            'message' => 'Post scheduled successfully',
            'post'    => $post->load('platforms'),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        try {
            if ($request->user()->id !== $post->user_id) {
                return response()->json(['message' => 'Unauthorized'], 403);
            }

            DB::beginTransaction();

            //Update post fields
            $post->update($request->validated());

            //Sync platforms (if provided)
            $request->whenFilled('platform_ids', function ($platformIds) use ($post) {
                $post->platforms()->syncWithPivotValues($platformIds, ['platform_status' => 'pending']);
            });

            DB::commit();

            ActivityLog::create([
                'user_id' => $post->user_id,
                'action' => 'Updated Post',
                'description' => "Updated post titled '{$post->title}'",
            ]);

            return response()->json([
                'message' => 'Post updated successfully',
                'post'    => $post->fresh(['platforms']),
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Post not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Update failed'], 500);
        }
    }

    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $postTitle = $post->title; // Store title before deletion
        $post->delete();

        ActivityLog::create([
            'user_id' => $post->user_id,
            'action' => 'Deleted Post',
            'description' => "Deleted post titled '{$postTitle}'",
        ]);

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
