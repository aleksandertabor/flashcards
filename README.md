# Flashcards

## Installation
```bash
composer update
npm install
php artisan passport:client --password
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
composer require cviebrock/eloquent-sluggable
composer require laravel/scout
composer require teamtnt/laravel-scout-tntsearch-driver
php artisan scout:import "App\Deck"
npm install vue-infinite-loading -S
composer require nuwave/lighthouse
composer require mll-lab/laravel-graphql-playground
npm install --save vue-apollo graphql apollo-client apollo-link apollo-link-http apollo-cache-inmemory graphql-tag
npm i apollo-link-error
npm i apollo-link-context
composer require laravel/telescope
php artisan telescope:install
npm install vue-multiselect --save
```
