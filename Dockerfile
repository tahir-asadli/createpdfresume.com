# --- Stage 1: Composer dependencies ---
FROM composer:2 AS vendor
WORKDIR /app
COPY ./src/database/ database/
COPY ./src/composer.json ./src/composer.lock ./
RUN composer install \
    --no-interaction --no-plugins --no-scripts \
    --no-dev --prefer-dist --optimize-autoloader \
    --ignore-platform-reqs

# --- Stage 2: Frontend assets (skip if no Vite/Mix) ---
FROM node:20-alpine AS frontend
WORKDIR /app
COPY ./src/package*.json ./
RUN npm ci
COPY ./src/resources/ resources/
COPY ./src/vite.config.js ./
RUN npm run build

# --- Stage 3: Final image ---
FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev zip unzip git \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && a2enmod rewrite

WORKDIR /var/www/html
COPY ./src .
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN cp .env.example .env \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

COPY docker/apache-laravel.conf /etc/apache2/sites-available/000-default.conf

# COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# RUN chmod +x /usr/local/bin/entrypoint.sh

EXPOSE 80
# ENTRYPOINT ["entrypoint.sh"]
CMD ["apache2-foreground"]