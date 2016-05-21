#!/bin/bash
php artisan migrate:install
php artisan migrate

php artisan db:seed --class=RarsTableSeeder
php artisan db:seed --class=UserStatusesTableSeeder
