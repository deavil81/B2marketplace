<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware {
    public function handle($request, Closure $next) {
        if (!Auth::check()) {
            return response('Unauthorized.', 401);
        }
        return $next($request);
    }
}

