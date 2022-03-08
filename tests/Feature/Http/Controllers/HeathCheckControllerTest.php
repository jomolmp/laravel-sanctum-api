<?php
namespace Tests\Feature\Controllers;
use Tests\TestCase;

class HealthCheckControllerTest extends TestCase
{
    public function testhealthcheck()
    {
        $this->withoutExceptionHandling();
        $header = [
            'api_secret_key'=>'apisecretkey'
        ];
        $response=$this->json('GET','api/health-check',[],$header);
        $response->assertStatus(200);
        $response->assertSee("Health CheckController Successfull");
    }
     
    public function getDifferentInputCombinations(): iterable
    {
        yield 'header without value' => [ 
            'input' => ['api_secret_key' => null],
            'expected' => ['message'=>'Missing header Credentials']
        ];
        yield 'header with wrong key' => [
            'input' => ['api_secret_key' => 'wrong key'],
            'expected' => ['message'=>'Missing header Credentials']
        ];
        yield 'no header' =>[
            'input' => [],
            'expected' => ['message'=>'Missing header Credentials']
        ];
    }
    /**
     * @dataProvider getDifferentInputCombinations
     */
     public function testHeaderAndValue(array $input, array $expected):void
     {
        $response = $this->json('GET','api/health-check',[],$input);
        $response->assertStatus(422)
        ->assertJson($expected);
     }
}