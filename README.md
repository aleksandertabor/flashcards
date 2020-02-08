# Flashcards

## Required
```bash
PHP >= 7.3
```

## Installation
```bash
git clone https://github.com/aleksandertabor/flashcards.git flashcards
cd flashcards
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan webpush:vapid
php artisan passport:install
npm install
php artisan storage:link
cd public || mv storage img
mv pdf storage/app/public
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
npm install vuetify
npm install @mdi/font -D
npm install sass sass-loader fibers deepmerge -D
npm install -D css-loader
npm install -D vue-style-loader
npm install -D node-sass
npm uninstall jquery
npm uninstall popper.js
npm uninstall bootstrap
composer require "spatie/laravel-medialibrary:^7.0.0"
sudo apt install jpegoptim optipng pngquant gifsicle
sudo apt-get install webp
composer require --dev barryvdh/laravel-ide-helper
composer require guzzlehttp/guzzle
npm install -g workbox-cli
composer require laravel-notification-channels/webpush
npm uninstall @fortawesome/fontawesome-free
npm install roboto-fontface --save
npm install workbox-window
npm install apollo-upload-client
npm install --save vue-clipboard2
npm i vue-uuid
composer require barryvdh/laravel-dompdf
npm install downloadjs
```