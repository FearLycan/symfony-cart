docker-compose up -d --build

docker exec -it php bash

composer install

symfony console doctrine:fixtures:load
APP_ENV=test symfony console doctrine:fixtures:load