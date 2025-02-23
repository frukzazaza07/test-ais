#!/bin/bash

# composer install --ignore-platform-req=ext-zip --optimize-autoloader --no-dev

# Run migrations
php artisan migrate --force

# Run database seeding
php artisan db:seed --force

php artisan storage:link

# Start PHP-FPM
exec "$@"