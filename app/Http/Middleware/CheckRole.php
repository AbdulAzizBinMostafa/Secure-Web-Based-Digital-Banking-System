<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized Access. Your role does not have permission.');
        }
        return $next($request);
    }
}