# Muchmore

## How to run

application will run on port 8000 or anyone that u specify in `docker/.env`. You can find documentation at `api/docs`

```
cp docker/.env.dist docker/.env
cp .env.dist .env.local

docker-compose --env-file=docker/.env -f docker-compose.yaml  -f docker-compose.override.yaml up --build -d

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env  exec php composer install

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env exec php bin/console d:d:c 

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env exec php bin/console d:m:m -n

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env exec php bin/console d:f:l -n

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env exec php bin/console lexik -n

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env exec php bin/console lexik:jwt:generate-keypair --overwrite -n

docker compose -f docker-compose.yaml -f docker-compose.override.yaml --env-file=docker/.env exec php vendor/phpunit/phpunit/phpunit
```


