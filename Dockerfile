FROM php:8.1-fpm

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y git unzip
RUN composer global require "laravel/lumen-installer"
ENV PATH $PATH:/root/.composer/vendor/bin

WORKDIR /var/www

COPY . /var/www

COPY .env /var/www/.env

RUN composer install

RUN mkdir -p /var/www/bootstrap/cache

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
