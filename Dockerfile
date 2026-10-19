FROM php:8.4-apache
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*


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