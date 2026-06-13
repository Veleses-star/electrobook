#!/bin/bash
set -e

# Создаём файл .env из переменных окружения
cat > /var/www/html/.env <<EOF
APP_ENV=${APP_ENV}
APP_DEBUG=${APP_DEBUG}
APP_URL=${APP_URL}
APP_KEY=${APP_KEY}
DB_CONNECTION=mysql
DATABASE_URL=${DATABASE_URL}
IGNORE_ENV=1
EOF

# Ждём базу данных
echo "Waiting for database..."
while ! nc -z mysql.railway.internal 3306; do
  sleep 1
done
echo "Database ready."

# Очищаем кэш и запускаем миграции
php artisan config:clear
php artisan migrate --force
php artisan storage:link

# Запускаем сервер
nginx
php-fpm -F