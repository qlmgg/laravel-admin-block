<?php

namespace App\Middlewares;

use App\Models\User;
use Closure;
use Illuminate\Support\Facades\Session;

class BlockAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(!User::modelGurd()->check()){
            return redirect()->route("block.Auth.login");
        }
        return $next($request);
    }
}
