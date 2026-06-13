#!/bin/bash
set -e

# Ждём готовности базы данных (опционально, можно закомментировать)
# echo "Waiting for database..."
# while ! nc -z $DB_HOST $DB_PORT; do sleep 1; done

# Генерируем ключ, если его нет
php artisan key:generate --force

# Кэшируем конфиги (опционально, можно закомментировать)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Запускаем миграции
php artisan migrate --force

# Запускаем Nginx и PHP-FPM
nginx
php-fpm -F