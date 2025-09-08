#!/usr/bin/env bash
# Render build script

echo "Starting build process..."

# Install composer dependencies
composer install --no-dev --optimize-autoloader

# Install npm dependencies
npm ci

# Build assets
npm run build

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Clear and cache routes
php artisan route:clear
php artisan route:cache

# Clear and cache views
php artisan view:clear
php artisan view:cache

# Generate optimized class loader
php artisan optimize

echo "Build process completed!"
