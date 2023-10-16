# syntax=docker/dockerfile:1
FROM php:8.2-apache

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql 
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
RUN echo "ServerName 172.18.0.1" >> /etc/apache2/apache2.conf

COPY ./debug-configs/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/
COPY ./debug-configs/error_reporting.ini /usr/local/etc/php/conf.d/