#!/bin/bash
echo "__________ start deployment __________"
echo "накатываю миграции"
php artisan migrate:install
php artisan migrate

echo "накатываю сиды"
php artisan db:seed --class=RarsTableSeeder
php artisan db:seed --class=UserStatusesTableSeeder
php artisan db:seed --class=LanguageTableSeeder
echo "___________ end deployment ____________"