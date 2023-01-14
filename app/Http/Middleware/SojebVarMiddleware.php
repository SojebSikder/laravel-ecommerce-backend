<?php

namespace App\Http\Middleware;

use App\Helper\SettingHelper;
use App\Lib\SojebVar\SojebVar;
use Closure;
use Illuminate\Http\Request;

class SojebVarMiddleware
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
        // set custom variable
        SojebVar::addVariable([
            'app.name' => SettingHelper::get('name'),
        ]);
        return $next($request);
    }
}
