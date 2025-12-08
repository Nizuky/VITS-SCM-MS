FROM php:8.2-fpm

ARG DEBIAN_FRONTEND=noninteractive
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        nginx \
        ca-certificates \
        curl \
        git \
        zip \
        unzip \
        default-mysql-client \
        nodejs npm \
    && rm -rf /var/lib/apt/lists/*

# PHP extensions required by the app
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        build-essential \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring exif pcntl bcmath gd zip \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html

# Copy nginx template and start script
COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template
COPY docker/prod/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/start.sh"]
