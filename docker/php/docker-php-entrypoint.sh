#!/bin/sh
if [ ! -d "vendor" ] || [ -z "$(ls -A vendor)" ]; then
  composer install
  composer dump-autoload --optimize
  php run.php create-db-schema
fi
php-fpm