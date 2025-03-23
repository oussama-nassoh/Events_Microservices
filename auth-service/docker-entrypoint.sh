#!/bin/bash
set -e  # Exit immediately if a command exits with a non-zero status

echo "Installing Composer dependencies..."
composer install --no-interaction

# Generate application key if not already set
php artisan key:generate --no-interaction --force

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

php artisan db:seed

# Cache configs
php artisan config:cache
php artisan route:cache

# Start the server
php artisan serve --host=0.0.0.0 --port=8001