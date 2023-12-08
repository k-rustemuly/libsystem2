<?php

namespace App\Http\Middleware;

use App\MoonShine\Pages\ChooseRole;
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
        if (MoonShineAuth::guard()->check() && !session('selected_admin') && $request->fullUrl() != to_page(ChooseRole::class) && !$request->routeIs('admin.choose.to')) {
            return to_page(ChooseRole::class, redirect: true);
        }
        return $next($request);
    }
}
