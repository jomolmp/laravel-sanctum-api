<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * @covers \App\Http\Controllers\TaskController
 */
class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    private const URI = 'api/tasks';

    public function testIndexIsSuccessful(): void
    {
        $task = new Task();
        $task->setAttribute('name', 'task1');
        $task->setAttribute('status', 'completed');
        $task->setAttribute('description', 'Task 1 description');
        $task->save();

        $tas2 = new Task();
        $tas2->setAttribute('name', 'task2');
        $tas2->setAttribute('status', 'completed');
        $tas2->setAttribute('description', 'Task 2 description');
        $tas2->save();

        $response = $this->json('GET', self::URI);

        $response->assertStatus(200)
            ->assertJson([$task->toArray(), $tas2->toArray()]);
    }
}
