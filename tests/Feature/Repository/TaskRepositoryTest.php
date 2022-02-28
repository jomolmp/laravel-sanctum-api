<?php
declare(strict_types=1);

namespace Tests\Feature\Repository;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertJson;

/**
 * @covers \App\Repositories\TaskRepository
 */
class TaskRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testRepoGetUserTaskIsSuccessful(): void
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
    
        $expected = [
            $task->toArray()
        ];
        $repository = new TaskRepository();  
        $result = $repository->getUserTask($user);
        self::assertEquals($expected, $result->toArray());
    }

    public function testrepoCreateTaskIsSuccessful():void
    {
        $user = new User();
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.com');
        $user->setAttribute('password', '7627168');
        $user->save();

        $task=new Task();
        $task->setAttribute('name','task 1');
        $task->setAttribute('description','task 1 description');
        $task->setAttribute('status','pending');
        $task->save();

        $expected = [
            'name'=>'task 1',
            'description'=>'task 1 description',
            'status'=>'pending',
            'user_id'=>$user->id
        ];
        $respository = new TaskRepository();
        $result=$respository-> CreateTask($task->toArray(),$user);
        $result->toArray();
        $this->assertDatabaseHas($result,$expected);
    }

    public function testrepoUpdateTaskIsSuccessful(): void
    {
       
        $task=new Task();
        $task->setAttribute('name','task1');
        $task->setAttribute('description','this is task1');
        $task->setAttribute('status','pending');
        $task->save();
        
        $data=[
            'name' => 'task2',
            'description' =>'this is task2',
            'status' => 'completed'
             ];
             
        $id=$task->getAttribute('id');
        $repository = new TaskRepository();
        $result=$repository->UpdateTask($data,$id);

        self::assertSame('task2', $result->getAttribute('name'));
        self::assertSame('this is task2', $result->getAttribute('description'));
        self::assertSame('completed', $result->getAttribute('status'));
        $this->assertDatabaseHas('tasks',$data);
    }

    public function testrepoDeleteTaskIsSuccessful(): void
    {
        $task=new Task();
        $task->setAttribute('name','task1');
        $task->setAttribute('description','this is task1');
        $task->setAttribute('status','pending');
        $task->save();
        $data=
        [
            'name' => "task1",
            'description' =>"this is task1",
            'status' => "completed"
        ];
        
        $id=$task->getAttribute('id');
        $repository = new TaskRepository();
        $result=$repository->DeleteTask($id);
        $this->assertDatabaseMissing('tasks',$data);
    }

    public function testrepoFindTaskByidIsSuccessful(): void
    {
        $task=new Task();
        $task->setAttribute('name','task1');
        $task->setAttribute('description','this is task1');
        $task->setAttribute('status','pending');
        $task->save();
        $expected=
        [
            'name' => "task1",
            'description' =>"this is task1",
            'status' => "pending",
            'user'=>null
        ];
        
        $id=$task->getAttribute('id');
        $repository = new TaskRepository();
        $result=$repository->ShowTaskById($id)->toArray();
        self::assertEquals($expected,$task->toArray());
       
    }
}

