FROM php:8.3-fpm

# Install Composer dependencies
# RUN apt-get update && apt-get install -y unzip git curl

# # Install Composer globally
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN adduser --disabled-password --gecos '' appuser

USER appuser

WORKDIR /var/www/html

COPY ./ /var/www/html