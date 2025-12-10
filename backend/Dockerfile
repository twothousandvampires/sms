FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd exif \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY ./src/ .

CMD ["sh", "-c", "sleep 15 && composer install && composer require laravel/sanctum --no-interaction && composer require spatie/laravel-medialibrary:^11.0 --no-interaction && php artisan key:generate && php artisan vendor:publish --provider='Laravel\\\\Sanctum\\\\SanctumServiceProvider' --force && php artisan vendor:publish --provider='Spatie\\\\MediaLibrary\\\\MediaLibraryServiceProvider' --tag='migrations' --force && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=8000"]