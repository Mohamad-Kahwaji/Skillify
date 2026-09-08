#!/bin/sh

set -e

php-fpm -D

sed -i "s/listen 8080;/listen ${PORT};/" /etc/nginx/sites-available/default

nginx -g "daemon off;"
