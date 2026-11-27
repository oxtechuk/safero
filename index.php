<?php

/**
 * Safero ERP - Hostinger Root Bootstrapper
 *
 * This file serves as a fallback entry point for Hostinger Shared/Cloud
 * hosting environments where the domain root points directly to the project root.
 */
$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'
);

// If the requested static file exists in the public directory, let the server handle it
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';
