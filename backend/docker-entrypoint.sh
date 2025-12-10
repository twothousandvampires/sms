#!/bin/bash

echo "Waiting for MySQL to start..."
# Используем просто sleep или установим netcat
# Вместо mysqladmin используем другой метод проверки

# Установим netcat если нужно
apt-get update && apt-get install -y netcat 2>/dev/null || true

# Ждем MySQL (альтернативный метод)
counter=0
while ! nc -z mysql 3306; do
    sleep 1
    counter=$((counter+1))
    if [ $counter -gt 60 ]; then
        echo "MySQL connection timeout"
        exit 1
    fi
done
echo "MySQL started"

echo "Installing Composer dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader

echo "Generating application key..."
php artisan key:generate

echo "Running database migrations..."
php artisan migrate --force

echo "Installing Laravel Sanctum..."
php artisan sanctum:install

echo "Starting Laravel application..."
exec "$@"