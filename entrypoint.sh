#!/bin/sh

if [ ! -d "vendor" ]; then
    composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
fi

if [ ! -d "vendor/laravel/sail" ]; then
    composer require laravel/sail --dev
    php artisan sail:install
fi

chown -R www-data:www-data /var/www/html