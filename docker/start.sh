#!/bin/sh

set -e

PORT="${PORT:-8080}"

php-fpm -D

sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/sites-available/default

nginx -t

exec nginx -g "daemon off;"
