FROM php:7-cli-alpine

RUN apk add --no-cache --update --virtual buildDeps g++ make autoconf composer

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer global require hirak/prestissimo

RUN pecl install redis xdebug && \
    docker-php-ext-enable redis xdebug
