## AlterBooks
MVP версия сервиса

[![Build Status](https://travis-ci.com/Inkognitoo/AlterBooks.svg?token=V7aGHpzpxaTBxpAyxbAB&branch=master_mvp)](https://travis-ci.com/Inkognitoo/AlterBooks)

Документация доступна по адресу:  
[github.com/Inkognitoo/AlterBooks/wiki](https://github.com/Inkognitoo/AlterBooks/wiki)
 
# Первичные команды
В случае, если вы хотите установить окружение с помощью docker, переходите к разделу [Виртуальное окружение laradock](#Виртуальное-окружение-laradock)

Необходимо последовательно выполнить:  
`pip install -r python/requirements.txt --upgrade --user` (убедитесь, что в системе установлен pip)  
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

Для работы с нечётким поиском, необходимо под пользователем **postgresql** зайти в базу данных проекта и выполнить команду:  
`CREATE EXTENSION pg_trgm;`

# Виртуальное окружение laradock
Убедитесь, что у вас установлены docker и docker-compose.   
Убедитесь, что папка laradock-alterbooks не пуста. В противном случае выполните:   
`git submodule update --init
`  
Так же для обновления можно использовать команду: `git submodule foreach git pull origin master`

Все команды выполняются из корня проекта

1. `cp laradock-alterbooks/env-example laradock-alterbooks/.env`
2. `cd laradock-alterbooks && docker-compose up -d nginx postgres redis php-worker`
3. `cd laradock-alterbooks && docker-compose exec --user=laradock workspace bash init.sh`
 
Для работы внутри контейнера необходимо запустить  
`cd laradock-alterbooks && docker-compose exec --user=laradock workspace bash`

Для доступа к проекту необходимо добавить в файл `/etc/hosts` строки:
```
127.0.0.1	alterbooks admin.alterbooks
```

Для работы с XDebug необходимо в PhpStorm открыть:   
File | Settings | Languages & Frameworks | PHP | Servers  
И добавить сервер примерно следующего вида
![Xdebug settings](https://preview.ibb.co/hNEU80/Screenshot-from-2018-10-17-23-35-47.png)

После чего нужно включить XDebug и расставить breakpoints в коде

## Решение проблем 
В случае, если команда №2 из предыдущего блока завершается с ошибкой вида: "address \<service name\> already in use", необходимо изменить порт соответствующего сервиса в файле `laradock-alterbook/.env` на свободный.  
 Например, если в системе уже установлен nginx слушающий 80 порт, то в в файле `laradock-alterbook/.env` можно прописать `NGINX_HOST_HTTP_PORT=8080` либо любой другой свободный порт
 
 В случае, если во время решения предыдущей проблемы был изменён порт nginx контейнера, необходимо прописать проксирование адресов приложения для системного nginx.      
 (**Обратите внимание** `proxy_pass http://127.0.0.1:<NGINX_HOST_HTTP_PORT>` то есть, тот который прописали nginx в файле `laradock-alterbook/.env`)   
 Для этого необходимо в папке `/etc/nginx/sites-available/` создать файлы  
 
 **alterbooks**
 ```nginx
server {
    listen 80;
    server_name alterbooks;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```
 **admin.alterbooks**
 ```nginx
server {
    listen 80;
    server_name admin.alterbooks;

    location / {
        proxy_pass http://127.0.0.1:8080;
        proxy_set_header Host $host;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

После чего зайти в папку `/etc/nginx/sites-enabled/`и выполнить  
`sudo ln -s ../sites-available/alterbooks alterbooks && sudo ln -s ../sites-available/admin.alterbooks admin.alterbooks`

Альтернативным решением может быть, просто на время работы отключить сервисы занимающие необходимый порт.  
Например: `sudo service nginx stop`