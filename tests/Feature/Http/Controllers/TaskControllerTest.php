<?php

namespace Tests\Feature\HTTP\Controllers;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    private const URI='api/tasks'; 
    public function testTaskIndex():void
    {
        $this->withoutExceptionHandling();
        $task=new Task;
        $task->setAttribute('name','task1');
        $task->setAttribute('description','this is task1');
        $task->setAttribute('status','completed');
        $task->save();
        $response=$this->json('GET',self::URI,);
        $response->assertStatus(200);
        $response->assertJson([$task->toArray()]);
    }

    public function testCreateTask():void
    {
        $user=new User();
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
    public function testTaskCreateFailsIfRequiredParametersAreMissing():void
    {
        $user=new User();
        $user->setAttribute('name', 'john');
        $user->setAttribute('email', 'john@gmail.com');
        $user->setAttribute('password', 'joh126');
        $user->save();
        Sanctum::actingAs($user);
        $expected=[
            'message' => 'The given data was invalid.',
            'errors' =>[
                'name' =>[
                    'The name field is required.'
                ],
                'description' =>[
                    'The description field is required.'
                ],
                'status' =>[
                    'The status field is required.'
                ],
            ],
        ];
        $response=$this->json('POST',self::URI,[]);
        $response->assertStatus(422)
        ->assertJsonFragment($expected);
    }
    public function testTaskUpdate()
    {
        $this->withoutExceptionHandling();
        $user=new User;
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.con');
        $user->setAttribute('password','123456');
        $user->save();
        Sanctum::actingAs($user);
        $task=new Task;
        $task->setAttribute('name','task 1');
        $task->setAttribute('description','this is task 1');
        $task->setAttribute('status','completed');
        $task->save();
        $uri=\sprintf('%s/%s',self::URI,$task->getAttribute('id'));
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
       $response->assertStatus(201)
       ->assertJsonFragment($expected);
       $this->assertDatabaseHas('tasks',$expected);
    }
    
    public function testUpdateTaskFailsIfRequiredParametersAreMissing():void
    {
        $user = new User();
        $user -> setAttribute('name', 'john');
        $user -> setAttribute('email', 'john@gmail.com');
        $user -> setAttribute('password', '123455');
        $user->save();
        Sanctum::actingAs($user);
        $task = new Task();
        $task->setAttribute('name', 'task');
        $task->setAttribute('description', 'task-1');
        $task->setAttribute('status', 'pending');
        $task->save();
        $expected = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' =>[
                    'The name field is required.'
                ],
                'description' =>[
                    'The description field is required.'
                ],
                'status' =>[
                    'The status field is required.'
                ],
            ],
        ];
        $uri = \sprintf('%s/%s',self::URI,$task->getAttribute('id'));
        $response = $this->json('PUT',$uri,[]);
        $response->assertStatus(422)
            ->assertjsonFragment($expected);
    }
    public function testFindTaskByIndex()
    {
        $task=new Task;
        $task->setAttribute('name','task 1');
        $task->setAttribute('description','this is task 1');
        $task->setAttribute('status','completed');
        $task->save();
        $uri = \sprintf('%s/%s',self::URI,$task->getAttribute('id'));
        $response=$this->json('GET',$uri);
        $response->assertStatus(200)
            ->assertJson($task->toArray());
    }

    public function testTaskDelete()
    {
        $user=new User;
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.con');
        $user->setAttribute('password','123456');
        $user->save();
        Sanctum::actingAs($user);
        $task=new Task;
        $task->setAttribute('name','task 1');
        $task->setAttribute('description','this is task 1');
        $task->setAttribute('status','completed');
        $task->save();
        $uri=\sprintf('%s/%s',self::URI,$task->getAttribute('id'));
        $response=$this->json('DELETE',$uri);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks',[
     'id'=> $task->getAttribute('id')]);
    }
}
?>