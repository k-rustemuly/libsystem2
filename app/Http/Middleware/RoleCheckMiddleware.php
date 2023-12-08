<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use MoonShine\MoonShineAuth;
use Symfony\Component\HttpFoundation\Response;

class RoleCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (MoonShineAuth::guard()->check() && !session('selected_admin') && !$request->routeIs('admin.choose.*')) {
        //     return to_route('index');
        // }
        return $next($request);
    }
}
