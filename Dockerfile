
# Etapa 1: instalar las dependencias de Laravel
FROM php:8.4-cli AS vendor

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql zip mbstring intl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader


# Etapa 2: compilar Vue, Inertia y Tailwind
FROM node:22-alpine AS frontend

WORKDIR /app

COPY package*.json ./

RUN npm ci

COPY . .

RUN npm run build


# Etapa 3: ejecutar Laravel con Apache
FROM php:8.4-apache AS production

RUN apt-get update && apt-get install -y \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql zip mbstring intl \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# Dependencias de PHP
COPY --from=vendor /var/www/html/vendor ./vendor

# Archivos compilados por Vite
COPY --from=frontend /app/public/build ./public/build

# Configuración de Apache para servir Laravel desde /public
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Crear carpetas necesarias y asignar permisos
RUN mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN rm -f bootstrap/cache/packages.php bootstrap/cache/services.php

EXPOSE 80
