FROM php:8.4-fpm-alpine

RUN apk update && apk add --no-cache \
    bash \
    curl \
    libzip-dev \
    zip \
    unzip \
    mysql-client \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY ./si_project_2025_be .

RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000

CMD ["php-fpm"]
