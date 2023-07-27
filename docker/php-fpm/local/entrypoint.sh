#!/bin/bash

echo >&3 "$0: Deploy"

RUN chown -R www-data:www-data /var/www/html
chmod -R 0777 /var/www/html/var