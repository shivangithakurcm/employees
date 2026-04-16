<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return response()->json(['error' => 'Invalid auth format'], 401);
        }

        $key = substr($header, 7);

        if ($key !== env('API_KEY')) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        return $next($request);
    }
}