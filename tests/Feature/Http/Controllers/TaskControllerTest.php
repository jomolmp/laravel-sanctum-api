<?php

namespace Tests\Feature\HTTP\Controllers;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;


class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    private const URI='api/tasks'; 

    public function test_task_index()
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

    public function test_task_create_successful()
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

    public function test_task_create_request_validation():void
    {
        $user=new User();
        $user->setAttribute('name','john');
        $user->setAttribute('email','john@gmail.com');
        $user->setAttribute('password','john');
        $user->save();
        Sanctum::actingAs($user);
        $expected=[
            'message' =>'The given data was invalid.',
            'errors'=>[     
                'name'=>['The name field is required.'],
                              
                'description'=>['The description field is required.'],
         
                'status'=>['The status field is required.' ],
                   
                ],
            ];
        $response=$this->json('POST',self::URI,[]);
            $response->assertStatus(422)
            ->assertJsonFragment($expected);
    
    }

    public function test_task_update()
    {
        $this->withoutExceptionHandling();
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
        print($tas->getAttribute('id'));
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
       $response->assertStatus(201)
       ->assertJsonFragment($expected);
       $this->assertDatabaseHas('tasks',$expected);
       
    }
    public function testUpdateFailsIfRequiredParamsAreMissing():void
    {
        $user = new User();
        $user->setAttribute('name', 'John');
        $user->setAttribute('email', 'John.doe@gmail.com');
        $user->setAttribute('password', 'nsadinfsdi');
        $user->save();

        Sanctum::actingAs($user);
        
        $expected=[
            'message' =>'The given data was invalid.',
            'errors'=>[     
                'name'=>['The name field is required.'],
                              
                'description'=>['The description field is required.'],
         
                'status'=>['The status field is required.' ],
                   
                ],
            ];
        
        $tas=new Task;
        $tas->setAttribute('name','task 1');
        $tas->setAttribute('description','this is task 1');
        $tas->setAttribute('status','completed');
        $tas->save();
        print($tas->getAttribute('id'));
        $uri=\sprintf('%s/%s',self::URI,$tas->getAttribute('id'));
      $response=$this->json('PUT',$uri,[]);
      $response->assertStatus(422)
      ->assertJsonFragment($expected);
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

       $response=$this->json('GET','api/tasks');
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

        $uri=\sprintf('%s/%s',self::URI,$tas->getAttribute('id'));

        $response=$this->json('DELETE',$uri);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks',['id'=> $tas->getAttribute('id')]);
    }
}
