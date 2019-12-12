# Flashcards

## Installation
```bash
composer update
npm install
```

## My steps to create this project (from zero):
```bash
composer create-project --prefer-dist laravel/laravel flashcards
composer require laravel/ui --dev
php artisan ui vue
npm install vue-router
composer require barryvdh/laravel-debugbar --dev
composer require laravel/passport
php artisan passport:install
php artisan vendor:publish --tag=passport-components
npm install browser-sync
npm install browser-sync-webpack-plugin
npm install vuex --save
composer require google/cloud-translate
npm install --save-dev @fortawesome/fontawesome-free
npm install vanilla-lazyload
```
