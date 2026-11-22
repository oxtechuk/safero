<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Switch application language between Arabic and English.
     */
    public function switch(string $locale, Request $request): RedirectResponse
    {
        if (! in_array($locale, ['ar', 'en'], true)) {
            $locale = 'ar';
        }

        $request->session()->put('locale', $locale);
        cookie()->queue(cookie()->forever('locale', $locale));

        if (Auth::check() && Auth::user()) {
            $user = Auth::user();
            $user->locale = $locale;
            $user->save();
        }

        return redirect()->back();
    }
}
