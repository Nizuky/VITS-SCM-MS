FROM php:8.2-fpm

ARG DEBIAN_FRONTEND=noninteractive
# Make apt operations tolerant to Release info changes and ensure noninteractive installs.
# Install common helpers first, then required packages and PHP extensions in one safe flow.
RUN set -eux; \
    apt-get update || apt-get -o Acquire::AllowReleaseInfoChange::Suite=1 update; \
    apt-get install -y --no-install-recommends \
        apt-transport-https \
        ca-certificates \
        gnupg \
        lsb-release \
        curl \
        git \
        zip \
        unzip \
        default-mysql-client \
        nginx \
        nodejs \
        npm \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libzip-dev \
        build-essential \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring exif pcntl bcmath gd zip || true; \
    apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*;

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

EXPOSE 8080
ENTRYPOINT ["/usr/local/bin/start.sh"]
