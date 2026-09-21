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
# COPY ./src/package*.json ./
# RUN npm i
# COPY ./src/resources/ resources/
# COPY ./src/vite.config.js ./
# RUN npm run build

COPY ./src .
RUN npm i
RUN npm run build

# --- Stage 3: Final image ---
FROM php:8.4-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev zip unzip git \
    supervisor \
    curl wget gnupg unzip fonts-liberation \
    poppler-utils \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && a2enmod rewrite \
    && wget -q https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb \
    && apt-get install -y ./google-chrome-stable_current_amd64.deb \
    && rm google-chrome-stable_current_amd64.deb \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
COPY ./src .
COPY ./.env.app .env
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

RUN mkdir -p /var/www/.chrome-data /var/www/html/storage/app/chrome-tmp \
    && chown -R www-data:www-data /var/www/.chrome-data /var/www/html/storage/app/chrome-tmp
    
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache \
    && php /var/www/html/artisan key:generate \
    && php /var/www/html/artisan storage:link \
    && php /var/www/html/artisan config:cache \
    && php /var/www/html/artisan route:cache \
    && php /var/www/html/artisan view:cache 

COPY docker/apache-laravel.conf /etc/apache2/sites-available/000-default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
# FROM php:8.4-apache

# RUN apt-get update && apt-get install -y \
#     libpng-dev libonig-dev libxml2-dev libzip-dev libicu-dev zip unzip git \
#     && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
#     && a2enmod rewrite

# WORKDIR /var/www/html
# COPY ./src .
# COPY --from=vendor /app/vendor ./vendor
# COPY --from=frontend /app/public/build ./public/build

# RUN cp .env.example .env \
#     && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
#     && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# COPY docker/apache-laravel.conf /etc/apache2/sites-available/000-default.conf

# # COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
# # RUN chmod +x /usr/local/bin/entrypoint.sh

# EXPOSE 80
# # ENTRYPOINT ["entrypoint.sh"]
# CMD ["apache2-foreground"]