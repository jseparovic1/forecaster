FROM php:7.4-cli
RUN apt-get update && apt-get install -y --no-install-recommends \
        unzip \
        git \
    && apt-get clean
COPY . /app
WORKDIR /app
RUN chmod +x /app/bin/console.php

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install
