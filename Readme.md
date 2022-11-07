## Installation

1. 😀 Clone this rep.

2. Go inside folder `./docker` and run `docker-sync-stack start` to start containers.

3. Inside the `php` container, run `composer install` to install dependencies from `/var/www/symfony` folder.

4. Inside the `php` container run the following command

```
php bin/console doctrine:migrations:migrate --no-interaction
```