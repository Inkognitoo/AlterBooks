# AlterBooks
После запуска, для асинхронной отправки почты необходимо запустить демона:   
`php artisan queue:listen --delay=1 --tries=3`
