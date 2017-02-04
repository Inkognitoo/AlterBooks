# AlterBooks
При первом запуске проекта необходимо выполнить:  
`sh ./deployment/init.sh`

После запуска, для асинхронной отправки почты необходимо запустить демона:   
`php artisan queue:listen --delay=1 --tries=3`
