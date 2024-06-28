<?php

namespace App\Http\Middleware;

use App\Services\SinglePassService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $validate = new SinglePassService();

            $validate->verify($request->bearerToken());
        } catch (\RuntimeException $error) {
            return response()->json([$error->getMessage()], 401);
        }

        return $next($request);
    }
}
