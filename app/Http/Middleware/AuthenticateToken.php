<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthenticateToken
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->filled('token')
            ? $request->get('token')
            : $request->headers->get('X-LF-AUTH');

        if ($token === null || $token !== config('auth.access_token')) {
            abort(401);
        }

        return $next($request);
    }
}
