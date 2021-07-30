echo "Renegerating the autoload file"
composer dumpautoload -o

echo "Starting the service"
php -S 127.0.0.1:8000 -t .
