#!/bin/sh
set -e
if [ ! -f .env ]; then
    cp .env.app .env
fi
php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force

exec "$@"