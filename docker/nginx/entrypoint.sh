#!/bin/sh
set -e

# Replace $PORT placeholder in nginx template if present and write final conf
TEMPLATE=/etc/nginx/conf.d/default.conf.template
DEST=/etc/nginx/conf.d/default.conf
PORT=${PORT:-80}

if [ -f "$TEMPLATE" ]; then
  echo "Using nginx template: substituting PORT=${PORT}"
  # Replace placeholder __PORT__ with the numeric PORT value to avoid envsubst quoting pitfalls
  sed "s/__PORT__/${PORT}/g" "$TEMPLATE" > "$DEST"
else
  echo "No nginx template found at $TEMPLATE, leaving default config as-is"
fi

exec nginx -g 'daemon off;'
