<?php

use App\Models\Setting;

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
