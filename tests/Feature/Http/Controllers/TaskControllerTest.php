<?php

namespace Tests\Feature\HTTP\Controllers;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    private const URI='api/tasks'; 

    public function test_task_index()
    {
       $task=new Task;
       $task->setAttribute('name','task1');
       $task->setAttribute('description','this is task1');
       $task->setAttribute('status','completed');
       $task->save();
       $response=$this->json('GET',self::URI,);
       $response->assertStatus(200);
       $response->assertJson([$task->toArray()]);

    }

    public function test_task_create()
    {
        $user=new User;
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.con');
        $user->setAttribute('password','123456');
        $user->save();
        Sanctum::actingAs($user);

       $response=$this->json('POST',self::URI,[
           'name'=>'task 1',
           'description'=>'this is task 1',
           'status'=>'completed'
       ]);
       $expected=[
        'name'=>'task 1',
        'description'=>'this is task 1',
        'status'=>'completed'
       ];
       $response->assertStatus(201)
       ->assertJsonFragment($expected);
       $this->assertDatabaseHas('tasks',$expected);

    }

    public function test_task_update()
    {
        $user=new User;
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.con');
        $user->setAttribute('password','123456');
        $user->save();
        Sanctum::actingAs($user);

        $tas=new Task;
        $tas->setAttribute('name','task 1');
        $tas->setAttribute('description','this is task 1');
        $tas->setAttribute('status','completed');
        $tas->save();

        $uri=\sprintf('%s/%s',self::URI,$tas->getAttribute('id'));
        $response=$this->json('PUT',$uri,[
            'name'=>'task 1',
            'description'=>'this is task 1',
            'status'=>'pending'
        ]);
        $expected=[
            'name'=>'task 1',
            'description'=>'this is task 1',
            'status'=>'pending'
           ];
       $response->assertStatus(200)
       ->assertJsonFragment($expected);
       $this->assertDatabaseHas('tasks',$expected);
       
    }

    public function test_task_find_by_index()
    {
        $tas=new Task;
        $tas->setAttribute('name','task 1');
        $tas->setAttribute('description','this is task 1');
        $tas->setAttribute('status','completed');
        $tas->save();
        $uri = \sprintf('%s/%s',self::URI,$tas->getAttribute('id'));
        $response=$this->json('GET',$uri);
        $response->assertStatus(200)
            ->assertJson($tas->toArray());
        
    }

    public function test_task_find_by_name()
    {
        $tas=new Task;
        $tas->setAttribute('name','task 1');
        $tas->setAttribute('description','this is task 1');
        $tas->setAttribute('status','completed');
        $tas->save();

       $response=$this->json('GET','api/tasks/task 1');
       $response->assertStatus(200);
    }

    public function test_task_delete()
    {
        $user=new User;
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.con');
        $user->setAttribute('password','123456');
        $user->save();
        Sanctum::actingAs($user);

        $tas=new Task;
        $tas->setAttribute('name','task 1');
        $tas->setAttribute('description','this is task 1');
        $tas->setAttribute('status','completed');
        $tas->save();
        
        $deleted=[
            'name'=>'task 1',
            'description'=>'this is task 1',
            'name'=>'completed',
        ];

        $uri=\sprintf('%s/%s',self::URI,$tas->getAttribute('id'));
        $response=$this->json('DELETE',$uri);
        $response->assertStatus(200);
        //$this->assertSoftDeleted('tasks',$deleted);
    }
}
