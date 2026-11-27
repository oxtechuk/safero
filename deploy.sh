#!/usr/bin/env bash
# ==============================================================================
# Safero ERP - Hostinger Deployment & Cache Optimization Script
# ==============================================================================
# Usage:
#   bash deploy.sh
# ==============================================================================

set -e

echo "🚀 [Safero ERP] Starting deployment optimization for Hostinger..."

# 1. Clear old caches
echo "🧹 Clearing previous application caches..."
php artisan optimize:clear

# 2. Cache Configuration, Routes, and Views for maximum production performance
echo "⚡ Caching configuration, routes, and Blade views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Create Storage Symlink (Required for uploaded signatures, stamps, attachments)
echo "🔗 Ensuring public storage link exists..."
php artisan storage:link || true

# 4. Run Database Migrations safely
echo "📦 Running database migrations..."
php artisan migrate --force

echo "🎉 [Safero ERP] Deployment completed successfully! Your application is ready on Hostinger."
