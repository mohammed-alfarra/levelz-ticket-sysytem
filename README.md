### Installation

```bash
cp .env.example .env
echo "UID=`id -u`" >> .env
cp docker/mysql/.env.example docker/mysql/.env
docker-compose up -d
docker-compose exec php sh -c "composer install"
docker-compose exec php sh -c "php artisan key:generate"
docker-compose exec php sh -c "php artisan migrate --seed"
```
