FROM php:8.2-fpm
RUN apt-get update && apt-get install -y --no-install-recommends unzip git libzip-dev && \
    rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) zip
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
