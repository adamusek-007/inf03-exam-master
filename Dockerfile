# syntax=docker/dockerfile:1

FROM php:8.2-apache

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql 
RUN docker-php-ext-configure pdo_mysql
RUN docker-php-ext-enable pdo pdo_mysql

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
# RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


# Create a non-privileged user that the app will run under.
# See https://docs.docker.com/develop/develop-images/dockerfile_best-practices/#user
# ARG UID=10001
# RUN adduser \
#     --disabled-password \
#     --gecos "" \
#     --home "/nonexistent" \
#     --shell "/sbin/nologin" \
#     --no-create-home \
#     --uid "${UID}" \
#     appuser
# USER appuser
