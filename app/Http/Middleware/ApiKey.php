<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('X-API-KEY');

        if ($apiKey !== env('API_KEY')) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        return $next($request);
    }
}