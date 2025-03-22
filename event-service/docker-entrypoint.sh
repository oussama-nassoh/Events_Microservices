#!/bin/bash

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Cache configs
php artisan config:cache
php artisan route:cache

# Start the server
php artisan serve --host=0.0.0.0 --port=8003