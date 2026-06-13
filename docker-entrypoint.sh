#!/bin/bash
set -e

echo "Waiting for database..."
while ! nc -z db 3306; do
  sleep 1
done
echo "Database ready."

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
php artisan storage:link

nginx
php-fpm -F