<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class CustomCheckRole
{
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => ['You must log in to view this page!']
            ]);
        }
        $user = Auth::user();
        if (!$user || $user->role != 'admin') {
            return response()->json([
                'success' => false,
                'error' => ['You may not use this site!']
            ]);
        }

        return $next($request);
    }
}
