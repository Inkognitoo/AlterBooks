#!/bin/bash

echo "__________ start deployment __________"
echo "--> перехожу в каталог"
cd /var/www/develop.alterbooks.ru/

echo "--> откатываю все несанкционированые изменения"
git stash save --keep-index
git stash drop

echo "--> получаю данные с github"
git checkout develop
git pull

echo "--> накатываю последнюю версию библиотек"
php composer.phar update

echo "--> накатываю миграции"
php artisan migrate
echo "___________ end deployment ____________"