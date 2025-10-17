<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use Tymon\JWTAuth\Facades\JWTAuth;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a test user
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Generate JWT token
        $this->token = JWTAuth::fromUser($this->user);
    }

    /**
     * Test task listing
     */
    public function test_can_list_tasks()
    {
        Task::factory()->count(3)->create(['created_by' => $this->user->id, 'assigned_user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson('/api/tasks');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'status',
                            'priority',
                            'created_by',
                            'assigned_user_id',
                            'due_date',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'current_page',
                    'per_page',
                    'total'
                ]);
    }

    /**
     * Test task creation
     */
    public function test_can_create_task()
    {
        $taskData = [
            'title' => 'Test Task',
            'description' => 'This is a test task',
            'status' => 'todo',
            'priority' => 'medium',
            'due_date' => now()->addDays(7)->toDateString(),
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'id',
                    'title',
                    'description',
                    'status',
                    'priority',
                    'created_by',
                    'due_date',
                    'created_at',
                    'updated_at'
                ])
                ->assertJson([
                    'title' => 'Test Task',
                    'description' => 'This is a test task',
                    'status' => 'todo',
                    'priority' => 'medium',
                ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'created_by' => $this->user->id,
        ]);
    }

    /**
     * Test task creation validation
     */
    public function test_task_creation_validation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson('/api/tasks', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['title']);
    }

    /**
     * Test task show
     */
    public function test_can_show_task()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id, 'assigned_user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $task->id,
                    'title' => $task->title,
                ]);
    }

    /**
     * Test task update
     */
    public function test_can_update_task()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id, 'assigned_user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Task Title',
            'status' => 'in-progress',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
                ->assertJson([
                    'title' => 'Updated Task Title',
                    'status' => 'in-progress',
                ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task Title',
            'status' => 'in-progress',
        ]);
    }

    /**
     * Test task deletion
     */
    public function test_can_delete_task()
    {
        $task = Task::factory()->create(['created_by' => $this->user->id, 'assigned_user_id' => $this->user->id]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Task deleted successfully'
                ]);

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    /**
     * Test unauthorized access
     */
    public function test_unauthorized_access()
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }
}
