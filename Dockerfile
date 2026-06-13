FROM php:8.2-fpm

# Устанавливаем необходимые расширения PHP
RUN apt-get update && apt-get install -y \
    nginx \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Устанавливаем Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем конфигурацию Nginx
COPY nginx.conf /etc/nginx/nginx.conf

# Рабочая директория
WORKDIR /var/www/html

# Копируем все файлы проекта
COPY . .

# Устанавливаем права на storage и bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Устанавливаем зависимости Composer (без dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Скрипт для запуска (будет генерировать ключ и запускать миграции)
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Открываем порт 80
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]