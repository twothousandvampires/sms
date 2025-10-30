docker compose build
docker compose up -d
docker compose exec backend composer install
docker compose exec backend php artisan serve --host=0.0.0.0 --port=8000