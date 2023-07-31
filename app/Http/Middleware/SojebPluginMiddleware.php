<?php

namespace App\Http\Middleware;

use App\Lib\Plugins\SojebPluginManager;
use Closure;
use Illuminate\Http\Request;

class SojebPluginMiddleware
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
        SojebPluginManager::initPlugin();
        return $next($request);
    }
}
