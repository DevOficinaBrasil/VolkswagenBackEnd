<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputs
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->has('born_at') && !empty($request->born_at)){
            $request->merge(['born_at' => Carbon::createFromFormat('d/m/Y', $request->born_at)->format('Y-m-d')]);
        }
        /*
        if($request->has('cnpj')){
            $request->merge(['cnpj' => preg_replace('/\D/', '', $request->cnpj)]);
        }

        if($request->has('document')){
            $request->merge(['document' => preg_replace('/\D/', '', $request->document)]);
        }

        if($request->has('phone')){
            $request->merge(['phone' => preg_replace('/\D/', '', $request->phone)]);
        }

        if($request->has('cep')){
            $request->merge(['cep' => preg_replace('/\D/', '', $request->cep)]);
        }

        if($request->has('auto_repair_cep')){
            $request->merge(['auto_repair_cep' => preg_replace('/\D/', '', $request->auto_repair_cep)]);
        }

        if($request->has('auto_repair_phone')){
            $request->merge(['auto_repair_phone' => preg_replace('/\D/', '', $request->auto_repair_phone)]);
        }
        */
        return $next($request);
    }
}
