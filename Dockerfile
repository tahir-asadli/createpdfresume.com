FROM php:8.4-apache


WORKDIR /var/www/html
COPY ./src .
# COPY ./.env.app .env
# COPY ./app_data/. ./storage/app


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

RUN apt-get update && apt-get install -y curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN composer install \
    --no-interaction --no-plugins --no-scripts \
    --no-dev --prefer-dist --optimize-autoloader \
    --ignore-platform-reqs

RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_24.x | bash - \
    && apt-get install -y nodejs

RUN npm i
RUN npm run build

    RUN mkdir -p /var/www/.chrome-data /var/www/html/storage/app/chrome-tmp \
    && chown -R www-data:www-data /var/www/.chrome-data /var/www/html/storage/app/chrome-tmp
    
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    # && php /var/www/html/artisan key:generate \
    # && php /var/www/html/artisan storage:link \
    # && php /var/www/html/artisan config:cache \
    # && php /var/www/html/artisan route:cache \
    # && php /var/www/html/artisan view:cache 

COPY docker/apache-laravel.conf /etc/apache2/sites-available/000-default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 80
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
