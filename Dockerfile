FROM php:8.3-fpm

# Install Composer dependencies
# RUN apt-get update && apt-get install -y unzip git curl

# # Install Composer globally
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite

RUN adduser --disabled-password --gecos '' appuser

USER appuser

WORKDIR /var/www/html

COPY ./ /var/www/html