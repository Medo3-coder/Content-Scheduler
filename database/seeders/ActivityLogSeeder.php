<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $actions = [
            'login' => 'User logged into the system',
            'post_created' => 'Created a new blog post',
            'post_updated' => 'Updated an existing post',
            'post_published' => 'Published post to social platforms',
        ];

        foreach ($users as $user) {
            $action = fake()->randomElement(array_keys($actions));

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $action,
                'description' => $actions[$action],
            ]);
        }
    }
}