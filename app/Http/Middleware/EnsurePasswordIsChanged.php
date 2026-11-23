<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $isFirstLoginRoute = $request->routeIs('password.first-login', 'password.first-login.update');

        if ($request->user()->must_change_password && ! $isFirstLoginRoute) {
            return redirect()->route('password.first-login');
        }

        if (! $request->user()->must_change_password && $isFirstLoginRoute) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
