#!/bin/sh

# Warten auf MySQL
echo "Waiting for MySQL..."
until nc -z -v -w30 db 3306
do
  echo "Waiting for database connection..."
  sleep 5
done

# Laravel vorbereiten
php artisan config:clear
php artisan migrate --force
php artisan key:generate

# Apache starten
apache2-foreground
