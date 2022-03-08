<?php
namespace App\Http\Middleware;
use App\Exceptions\ApiSecretNotFoundException;
use Closure;
use Illuminate\Http\Request;

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
        $value = config('apisecret.api_secret_key');
        if($request->hasHeader('api_secret_key')&& $request->header('api_secret_key')===$value)
        {      
                return $next($request);
        }
        else
        {
            throw new ApiSecretNotFoundException("Missing header Credentials");
        }
    }
}
