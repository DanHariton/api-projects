FROM php:8.4-fpm-alpine

RUN apk update && \
    apk upgrade && \
    apk add --no-cache curl nano zip wget bash autoconf build-base git postgresql-dev

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd pdo_pgsql xdebug intl zip bcmath curl opcache xml amqp

RUN pecl install excimer

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN mkdir -p /var/www/html/var/cache /var/www/html/var/log && \
    chown -R www-data:www-data /var/www/html/var/cache /var/www/html/var/log && \
    chmod -R 775 /var/www/html/var/cache /var/www/html/var/log