#!/bin/sh
set -e

# Replace $PORT placeholder in nginx template if present and write final conf
TEMPLATE=/etc/nginx/conf.d/default.conf.template
DEST=/etc/nginx/conf.d/default.conf
# Ensure we bind explicitly to 0.0.0.0:<PORT>. Railway provides a numeric PORT.
# If PORT is not provided, default to 8080.
NUM_PORT=${PORT:-8080}
# Build the listen address as 0.0.0.0:<numeric-port>
LISTEN_ADDR="0.0.0.0:${NUM_PORT}"

if [ -f "$TEMPLATE" ]; then
  echo "Using nginx template: substituting LISTEN_ADDR=${LISTEN_ADDR}"
  # Replace placeholder __PORT__ with the listen address (e.g. 0.0.0.0:8080)
  sed "s/__PORT__/${LISTEN_ADDR}/g" "$TEMPLATE" > "$DEST"
else
  echo "No nginx template found at $TEMPLATE, leaving default config as-is"
fi

exec nginx -g 'daemon off;'
