#!/usr/bin/env bash
# ==============================================================================
# Safero ERP - Hostinger Deployment & Cache Optimization Script
# ==============================================================================
# Usage:
#   bash deploy.sh
# ==============================================================================

set -e

echo "🚀 [Safero ERP] Starting deployment optimization for Hostinger..."

# 1. Install or update Composer dependencies if composer is available
if command -v composer &> /dev/null; then
    echo "📦 Checking and installing Composer production dependencies..."
    composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction
fi

# 2. Clear old caches
echo "🧹 Clearing previous application caches..."
php artisan optimize:clear

# 3. Cache Configuration, Routes, and Views for maximum production performance
echo "⚡ Caching configuration, routes, and Blade views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Create Storage Symlink (Required for uploaded signatures, stamps, attachments)
echo "🔗 Ensuring public storage link exists..."
php artisan storage:link || true

# 5. Run Database Migrations safely
echo "📦 Running database migrations..."
php artisan migrate --force

echo "🎉 [Safero ERP] Deployment completed successfully! Your application is ready on Hostinger."
