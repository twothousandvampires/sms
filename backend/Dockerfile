FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./src /var/www/html/

RUN cp .env.example .env
RUN php artisan key:generate --force

RUN composer install --no-dev --optimize-autoloader

RUN chmod -R 775 storage

EXPOSE 8000

CMD sh -c "sleep 5 && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"