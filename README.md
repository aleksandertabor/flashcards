<a href="https://flashcards.alexprojects.pl/">
    <img src="https://aleksandertabor.pl/wp-content/uploads/2020/04/icon-128x128-1.png" alt="Flashcards logo" title="Flashcards" align="right" height="100" />
</a>

🗃️ Flashcards
======================

> [Flashcards](https://flashcards.alexprojects.pl/) - create your decks and add flashcards.

## 🖥️ Demo

Live demo: [flashcards.alexprojects.pl](https://flashcards.alexprojects.pl/)

![flashcards](https://aleksandertabor.pl/wp-content/uploads/2020/04/mainflashcards.gif)

# 🚩 Table of Contents

1. [Requirements](#-requirements)
2. [Installation](#-installation)
3. [Helpers](#ℹ️-helpers)
4. [Built with](#-built-with)
5. [Frontend structure](#-frontend-structure)
6. [GraphQL API](#-graphql-api)
7. [Third Party APIs](#-third-party-apis)
8. [Logs](#-logs)
9. [Notifications](#-notifications)
10. [Features](#-features)
11. [Screenshots](#%EF%B8%8F-screenshots)
12. [To-do](#-to-do)
13. [Issues](#-issues)

## 🔌 Requirements

- PHP version: 7.3+
- PHP additional xtensions: gmp, sqlite
- Composer
- Node.js

## 🧾 Installation

1. `git clone https://github.com/aleksandertabor/flashcards YOURPROJECTNAME`
2. `cd YOURPROJECTNAME`
3. Install dependencies:

    `composer install`

    `npm install`

4. `cp .env.example .env`
5. `php artisan key:generate`
6. Set your `.env` with credentials to your database server (`DB_*` settings) and your domain config (`APP_URL`, `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`). Remember about [3rd party APIs](#-third-party-apis) for which keys are required.
7. `php artisan webpush:vapid`
8. `php artisan migrate --seed`
    - You can specify an amount of seeds. Available seeders:

    `LanguagesTableSeeder::class`

    ![LanguagesTableSeeder](https://aleksandertabor.pl/wp-content/uploads/2020/04/language-seeder-1024x92.png)

    `UsersTableSeeder::class`

    ![UsersTableSeeder](https://aleksandertabor.pl/wp-content/uploads/2020/04/user-seeder.png)

    `DecksTableSeeder::class`

    ![DecksTableSeeder](https://aleksandertabor.pl/wp-content/uploads/2020/04/deck-seeder.png)

9. `php artisan storage:link` then `cd public || mv storage img`

     Why? [Custom URL in Laravel Storage - Tutorial](https://medium.com/@aleksander.tabor/custom-url-in-laravel-storage-8392a32ac955)

    - All storage links now have /img/ prefix e.g.:


        > `http://localhost:8000/img/example-image.jpg`

10. `chmod -R 777 pdf` then `mv pdf storage/app/public`
11. Build frontend with `npm run production` for production.

> ⚠️ Caution: Remember about giving proper permissions to the project directory e.g.:
```bash
sudo chgrp -R www-data /var/www/YOURPROJECTNAME
sudo chmod -R 775 /var/www/YOURPROJECTNAME/storage
```

## ℹ️ Helpers

- `npm build-pwa` for precaching files from `workbox.config.js`

    originally: `workbox injectManifest workbox.config.js`

## 🧰 Built with

- Laravel 7
- Vue
- Vuetify
- Vuex
- Vue Router
- GraphQL as API
- Lighthouse as GraphQL Server
- Apollo Client
- Axios
- Laravel Sanctum/Airlock for Auth
- Laravel Scout for searching decks
- Workbox for Progressive Web App (PWA) and working offline (caching) [compatibility](https://caniuse.com/#feat=mdn-api_serviceworker)
- WebPush Notifications [compatibility](https://caniuse.com/#feat=mdn-api_notification)
- Web Share API to share decks [compatibility](https://caniuse.com/#feat=web-share)
- Web Devices API (Camera) to change avatar [compatibility](https://caniuse.com/#feat=mdn-api_mediadevices)
- Babel for polyfills and browser compatibilities (.babelrc)


## 📁 Frontend structure

Main file: `resources/js/app.js`

Configuration files (routes, service-worker, store, vuetify): `resources/js/config`

HTTP clients: `resources/js/httpClients`

Vuex modules: `resources/js/store/modules.js`

GraphQL queries: `resources/js/queries`

You can manage PWA and Notifications with plugins: `resources/js/plugins/`

Shared components, mixins and utils: `resources/js/shared`

Components: `resources/js/views`

## 💜 GraphQL API

All schemas: `routes/graphql/*.graphql`

Endpoint: `api/graphql`

Playground (Live API): `api/graphql-playground`

![GraphQL Playground](https://aleksandertabor.pl/wp-content/uploads/2020/04/graphql-playground-1024x502.png)

## 🧻 Third Party APIs
To fill your flashcards you can add your content or third party resources. The app is configured to use:
- Google Translation API for translations (need key in JSON) - `GOOGLE_API_CREDENTIALS`

- Twinword API for example sentences (need key) - `TWINWORD_API_ENDPOINT`, `TWINWORD_API_KEY`

- Wikipedia API for images (free) - `WIKIPEDIA_API_ENDPOINT`

> ⚠️ Caution: if none of APIs has keys, don't worry the app still working

APIs clients are binded with Singleton in: `app/Providers/AppServiceProvider.php`

You can use Facades to easily getting data from APIs everywhere you want:

`TranslationFacade::translate(string $toTranslate, array $languages)` : `string`

`TranslationFacade::langauges()` : `array`

`ExampleFacade::example(string $wordToFind, string $sourceLanguage, string $targetLanguage)` : `array`

`ImageFacade::random(string $imageToFind, string $sourceLanguage = null)` : ` string`

## 📝 Logs

You can log everything you want by using special `'app'` channel that logs into `storage/logs/app.log`. Either local or production.

E.g.: `Log::channel('app')->info("{$currentUser->username} logged in.");`

Currently logging:

- User logged in.

- User registered.

- New deck added.

- New card added.

- Calling to Google Translation API.

## 💬 Notifications

If your browser supports Notification API you will receive Welcome Notification (PushRegistered):

![PushRegistered](https://aleksandertabor.pl/wp-content/uploads/2020/04/welcomenotification.png)

When added a new public deck or updated the current deck to visibility as `'public'`, you will receive notification (DeckPublished):

![DeckPublished](https://aleksandertabor.pl/wp-content/uploads/2020/04/deckpublished.png)

### Backend config:

PushRegistered `app/Notifications/PushRegistered.php`

DeckPublished `app/Notifications/DeckPublished.php`

### Frontend config:

`/resources/js/config/service-worker.js`

`/resources/js/plugins/NotificationSystem.js`

## 🎨 Features

- Login with username or e-mail
- Installable app - Add To Home Screen (A2HS)
- Offline mode (data is limited to your memory size for cache)
- Deck visibilities:
    - public - anybody can see
    - unlisted - only with link
    - private - only you

- Each deck is limited to 50 cards
- Multi tabs logout - if you logout at one tab, you will be out of all currently running tabs in your browser
- Add images from file or URL
- Change avatar with your camera
- Infinite scroll in searching decks
- Find decks by filters (latest, oldest, random, cards count) and phrase
- Two modes for creating flashcards with API (auto mode) or manually
- Share your deck
- Print your decks with "Print Deck" button

    > ⚠️ Caution: not supported all languages

## 🖼️ Screenshots

### Home:

![Home](https://aleksandertabor.pl/wp-content/uploads/2020/04/flashcards-main.png)

### Search:

![Search](https://aleksandertabor.pl/wp-content/uploads/2020/04/searchdecks.png)

### Profile:

![Profile](https://aleksandertabor.pl/wp-content/uploads/2020/04/profile.png)

### Deck:

![Deck](https://aleksandertabor.pl/wp-content/uploads/2020/04/deck.png)

### Deck editor:

![Deck Editor](https://aleksandertabor.pl/wp-content/uploads/2020/04/deckeditor.png)

### Card editor:

![Card Editor](https://aleksandertabor.pl/wp-content/uploads/2020/04/cardeditor.png)


### Print deck:

![Print Deck](https://aleksandertabor.pl/wp-content/uploads/2020/04/printeddeck.png)

### Installable App:

<img src="https://aleksandertabor.pl/wp-content/uploads/2020/04/instalabble.gif" width="70%" alt="Install App"/>

### Share deck:

 <img src="https://aleksandertabor.pl/wp-content/uploads/2020/04/sharedeck.gif" width="70%" alt="Share Deck"/>

### Web Devices API (Camera):

<img src="https://aleksandertabor.pl/wp-content/uploads/2020/04/changeavatar.gif" width="70%" alt="Change Avatar"/>

## 📋 To-do

- Use Background Sync API for creating decks offline

- Show users which decks are currently cached

- Live preview for deck and card editor

- Auto mode for each input seperately in card editor

- Gamification with quizzing

- More social engagement with "liking" decks and filtering them by popularity

- Easier installation with Docker

## 🔴 Issues

- If you have seen any bugs please report it by making an [issue](https://github.com/aleksandertabor/flashcards/issues).
- You can PM me directly at [Facebook](https://www.facebook.com/aleksander.tabor.79), [Twitter](https://twitter.com/AlekTabor)
