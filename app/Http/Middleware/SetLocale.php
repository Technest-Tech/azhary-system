<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check cookie first, then session, then default to 'en'
        $locale = $request->cookie('locale') 
            ?? session('locale') 
            ?? config('app.locale', 'en');
        
        // Validate locale (only allow 'en' or 'fr')
        if (!in_array($locale, ['en', 'fr'])) {
            $locale = 'en';
        }
        
        App::setLocale($locale);
        
        return $next($request);
    }
}
