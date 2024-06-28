<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertBooleans
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->has('check')){
            $request->merge(['check' => filter_var($request->input('check'), FILTER_VALIDATE_BOOLEAN)]);
        }

        if($request->has('exist')){
            $request->merge(['exist' => filter_var($request->input('exist'), FILTER_VALIDATE_BOOLEAN)]);
        }
        
        return $next($request);
    }
}
