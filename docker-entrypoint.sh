#!/bin/bash

# Start PostgreSQL in the background
service postgresql start

# Wait a moment to make sure Postgres is up
sleep 3

# Run Laravel migrations (optional)
php artisan migrate --force || true

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=8000
