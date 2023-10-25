FROM php:8.2-fpm AS app
WORKDIR /app
RUN apt-get update && apt-get install -y \
    libzip-dev

RUN docker-php-ext-install pdo_mysql zip

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./docker/php/docker-php-entrypoint.sh /docker-php-entrypoint.sh
RUN chmod +x /docker-php-entrypoint.sh
RUN echo "alias do='php run.php'" >> ~/.bashrc
CMD [ "bash" ]
ENTRYPOINT ["/docker-php-entrypoint.sh"]

