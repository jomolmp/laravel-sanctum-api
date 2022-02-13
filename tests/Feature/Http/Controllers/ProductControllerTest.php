<?php

namespace Tests\Feature\Http\Controllers;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_the_products_index_page_is_rendered_properly()
    {
        
        //create a user
        $user=User::factory()->create();
        $this->actingAs($user);

        //hit the /products page
        $response = $this->get('api/products');
        
        //assert that we got status 200
        $response->assertStatus(200);
    }
}
