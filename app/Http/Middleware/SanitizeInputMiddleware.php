<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInputMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input =$request->all();
        array_walk_recursive($input,function($value){
            $value = strip_tags($value);
            $value = preg_replace('/on\w+="[^"]*"/i', '', $value);
            $value = preg_replace("/on\w+='[^']*'/i", '', $value);
            $value = preg_replace('/javascript:/i', '', $value);
            $value=htmlspecialchars($value,ENT_QUOTES,'UTF-8');
        });

        $request->merge($input);
        return $next($request);
    }
}
