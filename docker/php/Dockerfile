FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpq-dev libonig-dev libzip-dev \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    mbstring \
    zip \
    exif \
    pcntl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# IMPORTANT: create folders (safe even if volume overrides later)
RUN mkdir -p storage/framework/{views,cache,sessions} \
    bootstrap/cache

# DO NOT rely on chmod here (Windows override)