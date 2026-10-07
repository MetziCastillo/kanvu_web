FROM php:8.4-apache

RUN docker-php-ext-install pdo pdo_mysql

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

RUN chown -R www-data:www-data storage bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 80