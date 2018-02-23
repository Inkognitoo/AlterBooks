#!/usr/bin/env bash
if [ $1 ]
then
    echo -e "\n\033[1;36mПереключаюсь на ветку $1\033[0m\n"
    git checkout $1
fi

echo -e "\n\033[1;36mПроверяю обновления приложения в репозитории\033[0m\n"
git pull

echo -e "\n\033[1;36mПроверяю обновления композера\033[0m\n"
php composer.phar self-update

echo -e "\n\033[1;36mПроверяю обновления зависимостей для композера\033[0m\n"
php composer.phar install

echo -e "\n\033[1;36mПроверяю обновления зависимостей для npm\033[0m\n"
npm i

echo -e "\n\033[1;36mПроверяю наличие новых миграций\033[0m\n"
php artisan migrate --force

echo -e "\n\033[1;36mПроверяю наличие новых сидов\033[0m\n"
php artisan db:seed --force

echo -e "\n\033[1;36mКомипилирую js и css для production\033[0m\n"
npm run production
