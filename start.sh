#!/usr/bin/env bash
# Render start script

echo "Starting application..."

# Run database migrations
php artisan migrate --force

# Start the web server
php artisan serve --host=0.0.0.0 --port=$PORT
