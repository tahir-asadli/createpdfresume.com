#!/bin/sh
set -e
# if [ ! -f .env ]; then
    # cp .env.app /var/www/html/.env
# fi


# cp -r ./app_data/. /var/www/html/storage/app
# docker cp ./app_data/. createpdfresumecom-app-1:/var/www/html/storage/app

php artisan key:generate
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
# php artisan migrate --force

# supervisorctl restart all

exec "$@"