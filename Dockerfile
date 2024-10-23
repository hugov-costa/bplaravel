FROM php:fpm-alpine3.20

RUN apk --no-cache add \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libxml2-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /var/www/html

COPY . .

RUN composer install

RUN composer require laravel/sail --dev && php artisan sail:install

RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www/html