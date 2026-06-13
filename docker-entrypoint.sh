#!/bin/bash
set -e

echo "Waiting for database..."
while ! nc -z mysql.railway.internal 3306; do
  sleep 1
done
echo "Database ready."

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# НЕ кэшируем конфигурацию! Убираем config:cache, route:cache, view:cache
php artisan migrate --force
php artisan storage:link

nginx
php-fpm -F