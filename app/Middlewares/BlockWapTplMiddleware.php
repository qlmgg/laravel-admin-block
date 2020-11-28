<?php

namespace App\Middlewares;

use Closure;

class BlockWapTplMiddleware
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
        //修改默认的加载模版路径
        $view = app('view')->getFinder();
        $view->prependLocation(public_path('block/views/'));
        return $next($request);
    }
}
