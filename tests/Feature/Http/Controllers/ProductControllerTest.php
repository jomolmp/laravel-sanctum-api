<?php

namespace Tests\Feature\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    private const URI = 'api/products';
    
    public function test_product_index():void
    {
        $this->withoutExceptionHandling();
        
        $user = new User();
        $user -> setAttribute('name', 'assa');
        $user -> setAttribute('email', 'assa@gmail.com');
        $user -> setAttribute('password', '123456');
        $user->save();
        $this->actingAs($user);        
        $response = $this->get('api/products');
        $response->assertStatus(200);
        //->assertJson([$user->toArray()]);
    }

    public function test_create_products():void
    {
        
        $this->withoutExceptionHandling();
        $user = new User();
        $user -> setAttribute('name', 'assa');
        $user -> setAttribute('email', 'assa@gmail.com');
        $user -> setAttribute('password', '123456');
        $user->save();
        Sanctum::actingAs($user);
    
        $response=$this->json('POST', 'api/products', [
            'name' => 'dell',
            'slug'=>'dell-laptop',
            'description'=>'this is dell',
            'price'=>'800.99'
        ]);
        
        $expected=[
            'name' => 'dell',
            'slug'=>'dell-laptop',
            'description'=>'this is dell',
            'price'=>'800.99'
           ];
           $response->assertStatus(201)
           ->assertJsonFragment($expected);
           $this->assertDatabaseHas('products',$expected);
        
    }
    public function test_find_product_by_id():void
    {
        $this->withoutExceptionHandling();
        $pro1=new Product();
        $pro1->setAttribute('name','iphone 12');
        $pro1->setAttribute('slug','iphone-12');
        $pro1->setAttribute('description','this is iphone12');
        $pro1->setAttribute('price','300.99');
        $pro1->save();
        $uri = \sprintf('%s/%s',self::URI,$pro1->getAttribute('id'));
        $response=$this->json('GET',$uri);
        $response->assertStatus(200)
            ->assertJson($pro1->toArray());
       
    }

    public function test_find_product_by_name():void
    {
        $this->withoutExceptionHandling();
        $pro1=new Product();
        $pro1->setAttribute('name','iphone 12');
        $pro1->setAttribute('slug','iphone-12');
        $pro1->setAttribute('description','this is iphone12');
        $pro1->setAttribute('price','300.99');
        $pro1->save();

        $pro2=new Product();
        $pro2->setAttribute('name','redmi');
        $pro2->setAttribute('slug','redmi12');
        $pro2->setAttribute('description','this is redmi');
        $pro2->setAttribute('price','700.99');
        $pro2->save();

        $response=$this->json('GET','api/products/{$pro1->name}')->assertStatus(200);
       
    //->assertJson([$pro1->toArray()]);
       
    }
    public function test_update_product():void
    {
        $this->withoutExceptionHandling();
        $user = new User();
        $user -> setAttribute('name', 'joe');
        $user -> setAttribute('email', 'joe@gmail.com');
        $user -> setAttribute('password', '123456');
        $user->save();
        Sanctum::actingAs($user);
        $pro1=new Product();
        $pro1->setAttribute('name','iphone 12');
        $pro1->setAttribute('slug','iphone-12');
        $pro1->setAttribute('description','this is iphone12');
        $pro1->setAttribute('price','300.99');
        $pro1->save();    
        $uri=\sprintf('%s/%s',self::URI,$pro1->getAttribute('id'));
        $response=$this->json('PUT',$uri,[
            'name'=>'iphone 12',
            'slug'=>'iphone-12',
            'description'=>'pending',
            'price'=>'500.99'
        ]);
        $expected=[
            'name'=>'iphone 12',
            'slug'=>'iphone-12',
            'description'=>'pending',
            'price'=>'500.99'
           ];
       $response->assertStatus(200)
       ->assertJsonFragment($expected);
       $this->assertDatabaseHas('products',$expected);
    
    }
    public function test_delete_product()
    {
        $this->withoutExceptionHandling();
        $user=new User;
        $user->setAttribute('name','joe');
        $user->setAttribute('email','joe@gmail.com');
        $user->setAttribute('password','123456');
        $user->save();
        Sanctum::actingAs($user);

        $pro=new Product;
        $pro->setAttribute('name','product 1');
        $pro->setAttribute('slug','product-1');
        $pro->setAttribute('description','this is product 1');
        $pro->setAttribute('price','200');
        $pro->save();

        $uri=\sprintf('%s/%s',self::URI,$pro->getAttribute('id'));

        $response=$this->json('DELETE',$uri);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('products',['id'=> $pro->getAttribute('id')]);
        
    }
    public function test_login():void
    {
        $user=new User;
        $user->setAttribute('name', 'john');
        $user->setAttribute('email','john@gmail.com');
        $user->setAttribute('password',bcrypt($password='123456'));
        $user->save();
    
        $response=$this->from('/login')->json('POST','api/login',[
            'email'=>$user->email,
            'password'=>$password
        ]);
        $response->assertRedirect('/');
    }

    public function test_logout():void
    {
        $user=new User;
        $user->setAttribute('name', 'john');
        $user->setAttribute('email','john@gmail.com');
        $user->setAttribute('password',bcrypt($password='123456'));
        $user->save();
        Sanctum::actingAs($user);
        $response=$this->json('POST','api/logout');
        $response->assertStatus(200);
    }
}