#!/bin/bash
YELLOW='\033[0;33m'
RESET='\033[0m'

echo "__________ start deployment __________"
echo "накатываю последнюю версию библиотек"
php composer.phar update

echo "накатываю миграции"
php artisan migrate:install
php artisan migrate

echo "накатываю сиды"
php artisan db:seed --class=RarsTableSeeder
echo -e "${YELLOW}Seeding: ${RESET}RarsTableSeeder"
php artisan db:seed --class=UserStatusesTableSeeder
echo -e "${YELLOW}Seeding: ${RESET}UserStatusesTableSeeder"
php artisan db:seed --class=LanguageTableSeeder
echo -e "${YELLOW}Seeding: ${RESET}LanguageTableSeeder"
php artisan db:seed --class=BookStatusesTableSeeder
echo -e "${YELLOW}Seeding: ${RESET}BookStatusesTableSeeder"
echo "___________ end deployment ____________"