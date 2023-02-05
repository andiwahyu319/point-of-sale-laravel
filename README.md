# point-of-sale-laravel
---
to help record transactions
### 1. clone this repo
```ps
git clone https://github.com/andiwahyu319/point-of-sale-laravel.git
```
### 2. cd into this project
```ps
cd point-of-sale-laravel
```
### 3. install composer dependencies
```ps
composer install
```
### 4. install NPM dependencies
```ps
npm install
```
### 5. create a copy of your .env file
```ps
cp .env.example .env
```
### 6. generate an app encryption key
```ps
php artisan key:generate
```
### 7. create an empty database for our application
### 8. in the .env file, add database information to allow Laravel to connect to the database
### 9. migrate the database
```ps
php artisan migrate
```
### 10. seed the database
```ps
php artisan db:seed
```
### 11. run
when starting, we are asked to log in, the first account [here](https://github.com/andiwahyu319/point-of-sale-laravel/blob/main/database/seeders/StaffSeeder.php#L19)
