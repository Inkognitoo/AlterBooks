#!/bin/bash

echo "__________ start updating __________"
echo "накатываю последнюю версию библиотек"
php composer.phar update

echo "накатываю миграции"
php artisan migrate
echo "___________ end updating ____________"