# syntax=docker/dockerfile:1
FROM php:8.2-apache

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN echo "ServerName 172.18.0.1" >> /etc/apache2/apache2.conf
RUN docker-php-ext-install mysqli pdo pdo_mysql 
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"