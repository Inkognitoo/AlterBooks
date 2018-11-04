#!/usr/bin/env bash

# На будущее
environment="develop";
if [ $1 ]
then
    if [[ "$1" =~ ^(develop|stage|production)$ ]]; then
        environment=$1
    fi
fi

if [ $environment = "develop" ]
then
    # Блок работы с системой
    echo -e "\n\033[1;36mСоздаю файл переменных окружения\033[0m\n"
    cp .env.example .env

    #echo -e "\n\033[1;36mУстанавливаю необходимые права к папкам\033[0m\n"
    #chgrp -R www-data storage bootstrap/cache

    # Блок работы с php
    echo -e "\n\033[1;36mВыкачиваю зависимости для php\033[0m\n"
    composer install

    echo -e "\n\033[1;36mГенерирую секретный ключ приложения\033[0m\n"
    php artisan key:generate

    echo -e "\n\033[1;36mНакатываю миграции\033[0m\n"
    php artisan migrate

    echo -e "\n\033[1;36mНакатываю сиды\033[0m\n"
    php artisan db:seed

    # Блок работы с node js
    echo -e "\n\033[1;36mВыкачиваю зависимости для node js\033[0m\n"
    npm i

    echo -e "\n\033[1;36mКомипилирую js и css для разработки\033[0m\n"
    npm run dev
fi