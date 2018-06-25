FROM php:7.2-fpm-alpine

RUN apk add --no-cache --virtual .phpize-deps $PHPIZE_DEPS && \
    docker-php-ext-install pdo_mysql sockets && \
    pecl install mongodb && \
    apk del .phpize-deps
