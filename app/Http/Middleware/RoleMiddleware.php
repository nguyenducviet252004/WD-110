<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role)
    {
        $roles = explode('|', $role);
        if (!$request->user() || !in_array($request->user()->role, $roles)) {
            return response()->json(['message' => 'Không có quyền truy cập.'], 403);
        }

        return $next($request);
    }
}
