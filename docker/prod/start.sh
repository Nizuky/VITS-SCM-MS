#!/bin/sh
set -e

# Substitute PORT into nginx template (default 80)
TEMPLATE=/etc/nginx/conf.d/default.conf.template
DEST=/etc/nginx/conf.d/default.conf
# Ensure we bind explicitly to 0.0.0.0:<PORT>. Platform provides a numeric PORT.
NUM_PORT=${PORT:-8080}
LISTEN_ADDR="0.0.0.0:${NUM_PORT}"

if [ -f "$TEMPLATE" ]; then
  echo "Using nginx template: substituting LISTEN_ADDR=${LISTEN_ADDR}"
  sed "s/__PORT__/${LISTEN_ADDR}/g" "$TEMPLATE" > "$DEST"
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
