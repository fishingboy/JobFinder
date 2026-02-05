FROM php:8.2-fpm
RUN apt-get update && apt-get install -y --no-install-recommends unzip git libzip-dev curl && \
    rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install -j$(nproc) pdo_mysql
RUN docker-php-ext-install -j$(nproc) zip
RUN pecl install xdebug && docker-php-ext-enable xdebug
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
RUN curl -sS https://getcomposer.org/download/latest-stable/composer.phar -o /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer
