#!/bin/bash
set -e

# Clear composer cache and remove vendor to force fresh install
rm -rf vendor
composer clear-cache
composer install --no-dev --prefer-dist --optimize-autoloader

npm ci
npm run build
