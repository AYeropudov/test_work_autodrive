FROM php:7.4.0-fpm-alpine

RUN apk update
RUN apk add bash curl

# INSTALL COMPOSER
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin
RUN apk add --no-cache \
		$PHPIZE_DEPS
RUN pecl install xdebug-2.8.1 \
	&& docker-php-ext-enable xdebug \
    && docker-php-ext-install mysqli pdo pdo_mysql
WORKDIR /var/www/html
