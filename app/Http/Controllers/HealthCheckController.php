<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HealthCheckController extends Controller
{
    public function healthcheck(Request $request):Response
    {
        $header = $request->header();

         return new Response($header);
         
    }
}