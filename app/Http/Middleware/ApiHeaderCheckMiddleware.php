<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomException;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Config;
use Illuminate\Support\Facades\Config as FacadesConfig;

class ApiHeaderCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $header = config('apisecret.api-secret-key');
        if($request->hasHeader('api-secret-key'))
        {      
            if($request->header('api-secret-key')===$header)
            {
                return $next($request);
            }
            else
            {
                throw new CustomException('invalid secret key');
            }
        }
        else
        {
            throw new CustomException('invalid header');
        }
       
    }
}
