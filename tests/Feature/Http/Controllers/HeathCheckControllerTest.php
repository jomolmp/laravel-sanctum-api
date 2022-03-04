<?php
namespace Tests\Feature\Controllers;

use Tests\TestCase;

class HealthCheckControllerTest extends TestCase
{
    public function testhealthcheck()
    {
        $this->withoutExceptionHandling();
        $header = [
            'api-secret-key'=>'apisecretkey'
        ];
        $response=$this->json('GET','api/health-check',[],$header);
        $response->assertStatus(200);
        $response->assertJsonFragment(['api-secret-key'=>['apisecretkey']]);
    }
}