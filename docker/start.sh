#!/bin/sh

set -e

php-fpm -D

nginx -t

exec nginx -g "daemon off;"
