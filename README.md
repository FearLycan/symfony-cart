
docker exec -it php bash

composer install

php bin/console doctrine:fixtures:load