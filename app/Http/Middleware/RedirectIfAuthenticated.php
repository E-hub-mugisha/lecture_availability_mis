<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->type === 1) {
                    return redirect()->route('admin.dashboard.index');
                } elseif ($user->type === 2) {
                    return redirect()->route('lecturer.availability.index');
                } else {
                    return redirect()->route('students.dashboard.index');
                }
            }
        }

        return $next($request);
    }
}
