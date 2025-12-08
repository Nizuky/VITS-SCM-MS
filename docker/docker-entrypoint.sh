#!/usr/bin/env sh
set -e

# Default port if not supplied
: ${PORT:=80}

# Replace placeholder in nginx config template
if [ -f /etc/nginx/conf.d/default.template ]; then
  echo "Templating nginx config with PORT=${PORT}"
  # envsubst is provided by gettext-base
  export PORT
  envsubst '${PORT}' < /etc/nginx/conf.d/default.template > /etc/nginx/conf.d/default.conf
fi

# Ensure php-fpm socket directory exists if using unix socket
if [ -S /run/php/php-fpm.sock ] || [ -d /run/php ]; then
  mkdir -p /run/php || true
fi

# Exec supervisord in foreground
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
