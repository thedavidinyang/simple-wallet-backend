#!/bin/bash

# Install Composer dependencies if they are not installed
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-progress --no-interaction
fi

# Run migrations
php artisan migrate --seed 

# Start the Laravel development server
php artisan serve --host=0.0.0.0 --port=8080

exec "$@"
