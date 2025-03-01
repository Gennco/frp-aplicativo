<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next, ...$guards)
    {
        \Log::info('Middleware check - Auth: ' . Auth::check());
        if (Auth::check()) {
            \Log::info('User is authenticated');
            return $next($request);
        }

        \Log::info('User is not authenticated, redirecting');
        return redirect()->route('empleado.login');
    }
}
