FROM php:7.4-cli

COPY . /app
WORKDIR /app

RUN chmod +x bin/console.php

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
CMD ["composer", "install"]
