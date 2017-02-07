#!/bin/bash
YELLOW='\033[0;33m'
RESET='\033[0m'

echo "__________ start deployment __________"
echo "${YELLOW}--> перехожу в каталог${RESET}"
cd /var/www/develop.alterbooks.ru/

echo "${YELLOW}--> откатываю все несанкционированые изменения${RESET}"
git stash save --keep-index
git stash drop

echo "${YELLOW}--> получаю данные с github${RESET}"
git checkout develop
git pull

echo "${YELLOW}--> накатываю последнюю версию библиотек${RESET}"
php composer.phar update

echo "${YELLOW}--> накатываю миграции${RESET}"
php artisan migrate
echo "___________ end deployment ____________"