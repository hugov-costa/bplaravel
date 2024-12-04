FROM php:8.3-fpm-alpine

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apk --no-cache add \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libxml2-dev \
    oniguruma-dev \
    imagemagick-dev \
    autoconf \
    build-base \
    && docker-php-ext-configure gd \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install imagick \
    && docker-php-ext-enable imagick \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/imagick.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

WORKDIR /var/www/html

COPY . .

COPY entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]