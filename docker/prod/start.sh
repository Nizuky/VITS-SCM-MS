#!/bin/sh
set -e

# Substitute PORT into nginx template (default 80)
TEMPLATE=/etc/nginx/conf.d/default.conf.template
DEST=/etc/nginx/conf.d/default.conf
PORT=${PORT:-80}

if [ -f "$TEMPLATE" ]; then
  echo "Using nginx template: substituting PORT=${PORT}"
  sed "s/__PORT__/${PORT}/g" "$TEMPLATE" > "$DEST"
else
  echo "No nginx template found at $TEMPLATE"
fi

# Make sure php-fpm listens on loopback
if [ -f /etc/php/8.2/fpm/pool.d/www.conf ]; then
  sed -i -E "s@^listen\s*=.*@listen = 127.0.0.1:9000@" /etc/php/8.2/fpm/pool.d/www.conf || true
fi

# Start php-fpm in background, then run nginx in foreground
php-fpm &
nginx -g 'daemon off;'
