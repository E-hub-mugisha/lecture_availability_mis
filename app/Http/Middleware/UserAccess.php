<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $UserType): Response
    {
        // Ensure the user is authenticated
        if (auth()->check() && auth()->user()->type == $UserType) {
            return $next($request);
        }

        // Redirect unauthorized users instead of returning JSON
        return redirect('/home')->with('error', 'You do not have permission to access this page.');
    }
}
