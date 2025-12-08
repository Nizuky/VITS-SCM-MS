# Multi-stage Dockerfile for Laravel + Vite
# - node_builder: builds frontend assets (npm run build)
# - composer_builder: installs PHP dependencies
# - final: PHP-FPM image containing the app and built assets

FROM node:18-bullseye-slim AS node_builder
WORKDIR /app
COPY package*.json ./

# Install system build tools needed by some native Node modules and optional binaries
RUN apt-get update \
    && apt-get install -y --no-install-recommends ca-certificates curl git python3 build-essential \
    && rm -rf /var/lib/apt/lists/* \
    && if [ -f package-lock.json ]; then npm ci --silent; else npm install --silent; fi

# Copy sources needed for build (configs + resources + src files)
COPY . .

# Build frontend assets (Vite + Tailwind)
RUN npm run build

FROM composer:2 AS composer_builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader
COPY . /app

FROM php:8.2-fpm
WORKDIR /var/www/html

# Install system dependencies and PHP extensions required by Laravel and packages
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        zip \
        unzip \
        git \
        curl \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring exif pcntl bcmath gd zip \
    && pecl install redis || true \
    && docker-php-ext-enable redis || true \
    && rm -rf /var/lib/apt/lists/*

# Copy application code and dependencies from build stages
COPY --from=composer_builder /app /var/www/html
COPY --from=node_builder /app/public/build /var/www/html/public/build

# Ensure storage & cache dirs exist and are writable by www-data
RUN mkdir -p /var/www/html/storage/framework/cache/data \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure php-fpm listens on the loopback interface so nginx can connect via 127.0.0.1:9000
RUN if [ -f /etc/php/8.2/fpm/pool.d/www.conf ]; then \
            sed -i -E "s@^listen\s*=.*@listen = 127.0.0.1:9000@" /etc/php/8.2/fpm/pool.d/www.conf || true; \
        fi \
        && if [ -f /usr/local/etc/php-fpm.d/www.conf ]; then \
            sed -i -E "s@^listen\s*=.*@listen = 127.0.0.1:9000@" /usr/local/etc/php-fpm.d/www.conf || true; \
        fi

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

EXPOSE 9000
CMD ["php-fpm"]
