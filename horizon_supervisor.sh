#!/bin/sh
php artisan config:cache
php artisan route:cache
php artisan horizon:purge
php artisan horizon:terminate
php artisan horizon