## AlterBooks
MVP версия сервиса

[![Build Status](https://travis-ci.com/Inkognitoo/AlterBooks.svg?token=V7aGHpzpxaTBxpAyxbAB&branch=master_mvp)](https://travis-ci.com/Inkognitoo/AlterBooks)

Документация доступна по адресу:  
[github.com/Inkognitoo/AlterBooks/wiki](https://github.com/Inkognitoo/AlterBooks/wiki)
 
# Первичные команды
Необходимо последовательно выполнить:  
`pip install -r python/requirements.txt --upgrade` (убедитесь, что в системе установлен pip)  
`composer install` (убедитесь, что у вас в системе есть composer или скачайте его)   
`npm i` (убедитесь, что у вас установлена nodejs выше 6.0 версии)   
Затем следующие команды:  
`php artisan migrate`  
`php artisan db:seed`  
`sudo chgrp -R www-data storage bootstrap/cache`

Для заполнения базы данных фейковыми данными:
`php artisan db:seed --class=FixtureSeeder`  
(для генерации аккаунта авторизации, заполните как минимум env переменные `SEEDER_USER_EMAIL` и `SEEDER_USER_PASSWORD`)

После чего, для работы с [css](https://github.com/Inkognitoo/AlterBooks/wiki/%D0%9E%D0%B1%D1%89%D0%B8%D0%B5-%D0%BF%D0%BE%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D1%8F#%D0%A0%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81-css) или [javascript](https://github.com/Inkognitoo/AlterBooks/wiki/%D0%9E%D0%B1%D1%89%D0%B8%D0%B5-%D0%BF%D0%BE%D0%BB%D0%BE%D0%B6%D0%B5%D0%BD%D0%B8%D1%8F#%D0%A0%D0%B0%D0%B1%D0%BE%D1%82%D0%B0-%D1%81-javascript) необходимо запустить:
`npm run watch` для компиляции файлов после каждого изменения, либо `npm run dev` для компиляции текущего состояния.
