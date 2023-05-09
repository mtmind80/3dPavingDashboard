<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;

class RedirectIfLocked
{
    public function handle($request, Closure $next)
    {
        $route = Route::getRoutes()->match($request);
        $routeName = $route->getName();

        if (session()->get('lockout', false) && !in_array($routeName, ['lockout', 'unlock', 'endlockout'])) {
            return redirect()->route('lockout');
        }

        return $next($request);
    }

}
