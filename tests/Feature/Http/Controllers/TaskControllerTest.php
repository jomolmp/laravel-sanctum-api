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

    public function testCreateIsSuccessful(): void
    {
        $user = new User();
        $user->setAttribute('name', 'John');
        $user->setAttribute('email', 'John.doe@gmail.com');
        $user->setAttribute('password', 'nsadinfsdi');
        $user->save();

        Sanctum::actingAs($user);

        $response = $this->json('POST', self::URI, [
            'name' => 'task 1',
            'description' => 'task description',
            'status' => 'completed',
        ]);

        $expected = [
            'name' => 'task 1',
            'description' => 'task description',
            'status' => 'completed',
        ];

        $response->assertStatus(201)
            ->assertJsonFragment($expected);
        $this->assertDatabaseHas('tasks', $expected);
    }

    public function testCreateFailsIfRequiredParamsAreMissing(): void
    {
        $user = new User();
        $user->setAttribute('name', 'John');
        $user->setAttribute('email', 'John.doe@gmail.com');
        $user->setAttribute('password', 'nsadinfsdi');
        $user->save();

        Sanctum::actingAs($user);

        $expected = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' => [
                    'The name field is required.'
                ],
                'description' => [
                    'The description field is required.'
                ],
                'status' => [
                    'The status field is required.'
                ],
            ],
        ];

        $response = $this->json('POST', self::URI, []);

        $response->assertStatus(422)
            ->assertJsonFragment($expected);
    }

    public function testFindByIdIsSuccessful(): void
    {
        $task = new Task();
        $task->setAttribute('name', 'task1');
        $task->setAttribute('description', 'task1');
        $task->setAttribute('status', 'completed');
        $task->save();

        $uri = \sprintf('%s/%s', self::URI, $task->getAttribute('id'));

        $response = $this->json('GET', $uri);

        $response->assertStatus(200)
            ->assertJson($task->toArray());
    }
}
