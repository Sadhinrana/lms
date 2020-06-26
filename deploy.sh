git reset --hard;
git pull;
composer install;
php artisan cache:clear;
cd frontend;
npm install;
npm run build;
