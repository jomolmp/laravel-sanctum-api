<?php
declare(strict_types=1);

namespace Tests\Feature\Repository;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @covers \App\Repositories\TaskRepository
 */
class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGetUserTaskIsSuccessful(): void
    {
        $user = new User();
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.com');
        $user->setAttribute('password','123455');
        $user->save();

        $task=new Task();
        $task->setAttribute('name','task1');
        $task->setAttribute('description','this is task1');
        $task->setAttribute('status','pending');
        $task->user()->associate($user);
        $task->save();
        $task2=new Task();
        $task2->setAttribute('name','task2');
        $task2->setAttribute('description','this is task2');
        $task2->setAttribute('status','pending');
        $task2->user()->associate($user);
        $task2->save();
        $repository = new TaskRepository();

        $result = $repository->getUserTask($user);
        dd($result);
    }
}
