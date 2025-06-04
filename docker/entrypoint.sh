#!/bin/sh

echo "reserving mysql"
until nc -z saas_app_mysql 3306; do
  sleep 1
done
echo "mysql ready"

if [ ! -f .env ]; then
  echo "copied .env"
  cp .env.example .env
fi

if [ ! -d vendor ]; then
  echo "install dependencies with composer"
  composer install --optimize-autoloader --no-interaction
fi

if [ ! -d node_modules ]; then
  echo "installing NPM dependencies..."
  npm install
fi

echo "running Vite dev server in background..."
npm run dev -- --host 0.0.0.0 &

php artisan config:clear
php artisan key:generate

chmod -R 777 storage

php artisan migrate --force
php artisan db:seed
php artisan optimize

echo "Starting supervisord..."
exec supervisord -c /etc/supervisord.conf
