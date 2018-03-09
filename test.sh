#!/usr/bin/env bash

cd `dirname $0`;

echo -e "\n\033[1;36mЗапускаю unit тестирование\033[0m\n"
./vendor/bin/phpunit

echo -e "\n\033[1;36mЗапускаю браузерное тестирование\033[0m\n"
php artisan dusk
