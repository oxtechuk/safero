<?php

use App\Models\Setting;
use App\Support\ArabicShaper;

if (! function_exists('setting')) {
    /**
     * Retrieve a setting value or fallback to default.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            return Setting::get($key, $default);
        } catch (Throwable) {
            return $default;
        }
    }
}

if (! function_exists('arabic_pdf')) {
    /**
     * Shape and format Arabic text for DomPDF rendering without disconnected letters.
     */
    function arabic_pdf(?string $text): string
    {
        return ArabicShaper::shape($text);
    }
}
