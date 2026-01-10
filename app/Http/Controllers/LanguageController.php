<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     */
    public function switchLanguage(Request $request)
    {
        $locale = $request->input('locale', 'en');
        
        // Validate locale
        if (!in_array($locale, ['en', 'fr'])) {
            $locale = 'en';
        }
        
        // Store in session
        Session::put('locale', $locale);
        
        // Store in cookie (1 year expiration)
        Cookie::queue('locale', $locale, 60 * 24 * 365);
        
        // Set app locale
        app()->setLocale($locale);
        
        return redirect()->back();
    }
}
