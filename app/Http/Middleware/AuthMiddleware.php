<?php

namespace App\Http\Middleware;

use Closure, Auth;
use Illuminate\Http\Request;

class AuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return response()->json(['message' => 'please login', 'status' => 400]);
        }

        return $next($request);
    }
}
