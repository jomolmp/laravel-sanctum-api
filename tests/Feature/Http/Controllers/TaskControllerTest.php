<?php
namespace Tests\Feature\HTTP\Controllers;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Nette\Utils\Arrays;
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
        $task1=new Task;
        $task1->setAttribute('name','task1');
        $task1->setAttribute('description','this is task1');
        $task1->setAttribute('status','completed');
        $task1->save();
        $response=$this->json('GET',self::URI);
        $response->assertStatus(200);
        $response->assertJson([$task->toArray(), $task1->toArray()]);
    }

    public function testOnlyAuthenticatedUserTasksWillBeReturned():void
    {
        $this->withoutExceptionHandling();
        $user = new User();
        $user->setAttribute('name','jo');
        $user->setAttribute('email','jo@gmail.com');
        $user->setAttribute('password','123455');
        $user->save();

        $user2=new User();
        $user2->setAttribute('name','john');
        $user2->setAttribute('email','john@gmail.com');
        $user2->setAttribute('password','123aa455');
        $user2->save();

        // current auth user
        Sanctum::actingAs($user);

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
        $expected = [
            [
            'name'=>'task1',
            'description'=>'this is task1',
            'status'=>'pending',
            'user'=>[
                'user_id'=>$user->id
            ]
            ],
            [
                'name'=>'task2',
                'description'=>'this is task2',
                'status'=>'pending',
                'user'=>[
                    'user_id'=>$user->id
                ]
            ]
        ];

        $response=$this->json('GET','api/user-tasks');

        $response->assertStatus(200)
            ->assertJson($expected);
    }

    public function testCreateTask():void
    {
        $this->withoutExceptionHandling();
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

    public function testRelationSuccessfullInCreateTask():void
    {
        $this->withoutExceptionHandling();
        $user=new User();
        $user->setAttribute('name','assa');
        $user->setAttribute('email','assa@gmail.con');
        $user->setAttribute('password','45657');
        $user->save();

        $user1=new User();
        $user1->setAttribute('name','jo');
        $user1->setAttribute('email','jo@gmail.con');
        $user1->setAttribute('password','65765757');
        $user1->save();

        Sanctum::actingAs($user);
        $response=$this->json('POST',self::URI,[
           'name'=>'task 2',
           'description'=>'this is task 2',
           'status'=>'pending'
        ]);
        $expected=[
            'name'=>'task 2',
            'description'=>'this is task 2',
            'status'=>'pending',
            'user_id'=>$user->id
           ];

           $response->assertStatus(201);
          $this->assertDatabaseHas('tasks', $expected) ;
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
