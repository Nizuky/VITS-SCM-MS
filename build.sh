#!/bin/bash
set -e

composer install --no-dev --prefer-dist --optimize-autoloader
npm ci
npm run build
