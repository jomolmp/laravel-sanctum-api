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
       $response->assertJsonFragment(['api-secret-key'=>['apisecretkey']]);
        
    }
}