<?php

namespace Tests\Feature\Http\Controllers;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    private const URI = 'api/products';
    public function testProductIndex():void
    {
       $this->withoutExceptionHandling();
        $product=new Product();
        $product->setAttribute('name','Product 1');
        $product->setAttribute('slug','Product-1');
        $product->setAttribute('description','This is Product 1');
        $product->setAttribute('price','completed');
        $product->save();
        $response=$this->json('GET',self::URI,);
        $response->assertStatus(200)
        ->assertJson([$product->toArray()]);
    }

    public function testCreateProduct():void
    {
        
        $this->withoutExceptionHandling();
        $user = new User();
        $user -> setAttribute('name', 'assa');
        $user -> setAttribute('email', 'assa@gmail.com');
        $user -> setAttribute('password', '123456');
        $user->save();
        Sanctum::actingAs($user);
    
        $response=$this->json('POST', self::URI, [
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

    public function testCreateProductFailsIfRequiredParametersAreMissing():void
    {
        $user = new User();
        $user->setAttribute('name', 'jo');
        $user->setAttribute('email', 'jo@gmail');
        $user->setAttribute('password', '113434');
        $user->save();
        Sanctum::actingAs($user);
        
        $response = $this->json('POST', self::URI, []);

        $expected = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' =>[
                     'The name field is required.'
              ],
              'slug' =>[
                     'The slug field is required.'
              ],
                'description' =>[
                     'The description field is required.'
              ],
                'price' =>[
                     'The price field is required.'
              ],
            ],
        ];

        $response->assertStatus(422)
             ->assertJsonFragment($expected);
    }
    
    public function testProductFindById():void
    {
        $this->withoutExceptionHandling();
        $product=new Product();
        $product->setAttribute('name','iphone 12');
        $product->setAttribute('slug','iphone-12');
        $product->setAttribute('description','this is iphone12');
        $product->setAttribute('price','300.99');
        $product->save();
        $uri = \sprintf('%s/%s',self::URI,$product->getAttribute('id'));
        $response=$this->json('GET',$uri);
        $response->assertStatus(200)
            ->assertJson($product->toArray());
    }

    public function testFindProductByName():void
    {
        $this->withoutExceptionHandling();
        $product=new Product();
        $product->setAttribute('name','redmi');
        $product->setAttribute('slug','redmi12');
        $product->setAttribute('description','this is redmi');
        $product->setAttribute('price','700.99');
        $product->save();
        $uri = \sprintf('%s/%s',self::URI, $product->getAttribute('id'));
        $response = $this->json('GET', $uri);
        $response->assertStatus(200)
             ->assertJson($product->toArray());    
    }

    public function testUpdateProduct():void
    {
        $this->withoutExceptionHandling();
        $user = new User();
        $user -> setAttribute('name', 'joe');
        $user -> setAttribute('email', 'joe@gmail.com');
        $user -> setAttribute('password', '123456');
        $user->save();
        Sanctum::actingAs($user);
        $product=new Product();
        $product->setAttribute('name','iphone 12');
        $product->setAttribute('slug','iphone-12');
        $product->setAttribute('description','this is iphone12');
        $product->setAttribute('price','300.99');
        $product->save();    
        $uri=\sprintf('%s/%s',self::URI,$product->getAttribute('id'));
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
       $response->assertStatus(201)
       ->assertJsonFragment($expected);
       $this->assertDatabaseHas('products',$expected);
    }

     public function testUpdateProductFailsIfParametersAreMissing(): void
    {
        $user = new User();
        $user->setAttribute('name', 'john');
        $user->setAttribute('email', 'john@gmail');
        $user->setAttribute('password', '12324');
        $user->save();
        Sanctum::actingAs($user);
        $product = new product();
        $product->setAttribute('name', 'Iphone12');
        $product->setAttribute('slug', 'Iphone-12');
        $product->setAttribute('name', 'this is Iphone 12');
        $product->setAttribute('price', '599.00');
        $product->save();
        $uri = \sprintf('%s/%s', self::URI, $product->getAttribute('id'));
        $response = $this->json('PUT',$uri,[]);
        $expected = [
            'message' => 'The given data was invalid.',
            'errors' => [
                'name' =>[
                    'The name field is required.'
                ],
                'slug' =>[
                    'The slug field is required.'
                ],
                'description' =>[
                    'The description field is required.'
                ],
                'price' =>[
                    'The price field is required.'
                ],
            ],
        ];
        $response->assertStatus(422)
             ->assertJsonFragment($expected);
    }


    public function testDeleteProduct():void
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
?>