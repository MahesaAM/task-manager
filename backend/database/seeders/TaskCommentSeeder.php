<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskComment;
use App\Models\Task;
use App\Models\User;

class TaskCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();

        $comments = [
            [
                'task_id' => $tasks->first()->id,
                'user_id' => $users->where('role', 'manager')->first()->id,
                'comment' => 'Great work on implementing the authentication system. The JWT setup looks solid.',
            ],
            [
                'task_id' => $tasks->first()->id,
                'user_id' => $users->where('role', 'developer')->first()->id,
                'comment' => 'Thanks! I followed the best practices for JWT implementation. Ready for testing.',
            ],
            [
                'task_id' => $tasks->skip(1)->first()->id,
                'user_id' => $users->where('role', 'manager')->first()->id,
                'comment' => 'Please ensure all CRUD operations are properly validated and include proper error handling.',
            ],
            [
                'task_id' => $tasks->skip(1)->first()->id,
                'user_id' => $users->where('role', 'developer')->first()->id,
                'comment' => 'Will add comprehensive validation and error responses. Should be done by next week.',
            ],
            [
                'task_id' => $tasks->skip(2)->first()->id,
                'user_id' => $users->where('role', 'admin')->first()->id,
                'comment' => 'The database schema looks good. Make sure to add proper indexes for performance.',
            ],
            [
                'task_id' => $tasks->skip(3)->first()->id,
                'user_id' => $users->where('role', 'manager')->first()->id,
                'comment' => 'File upload should support multiple formats and include virus scanning.',
            ],
            [
                'task_id' => $tasks->skip(4)->first()->id,
                'user_id' => $users->where('role', 'developer')->first()->id,
                'comment' => 'Starting work on the Vue.js dashboard. Will use Nuxt.js for SSR.',
            ],
            [
                'task_id' => $tasks->skip(5)->first()->id,
                'user_id' => $users->where('role', 'tester')->first()->id,
                'comment' => 'Will create unit tests for all API endpoints and models.',
            ],
            [
                'task_id' => $tasks->skip(6)->first()->id,
                'user_id' => $users->where('role', 'admin')->first()->id,
                'comment' => 'Planning to use GitHub Actions for CI/CD pipeline.',
            ],
            [
                'task_id' => $tasks->skip(7)->first()->id,
                'user_id' => $users->where('role', 'developer')->first()->id,
                'comment' => 'Will focus on query optimization and caching strategies.',
            ],
        ];

        foreach ($comments as $comment) {
            TaskComment::create($comment);
        }
    }
}
