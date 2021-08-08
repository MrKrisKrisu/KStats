# KStats

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4dfdd959ca7f4b07b7b6fd57f1f92c91)](https://www.codacy.com/gh/MrKrisKrisu/KStats/dashboard)
![PHPUnit](https://github.com/MrKrisKrisu/KStats/workflows/PHPUnit/badge.svg)
<a href="http://weblate.k118.de/engage/kstats/">
<img src="http://weblate.k118.de/widgets/kstats/-/web/svg-badge.svg" alt="Translation status" />
</a>

![Screenshot](screenshot.png)

## Features

* Track your listened songs from Spotify
    * See your Top tracks and artists
    * See how many minutes you have listened to music
    * Create playlists with "lost tracks" you like but haven't heard in a while
    * Create playlists with your most popular songs - updated daily
* Analyze what you bought in the supermarket
    * Send your receipts automatically and user-friendly to an e-mail address
    * See your top Products
    * Get an overview of what you could buy
    * See how much money you have spent by category
    * Currently only compatible with digital receipts from REWE (in Germany)

## Requirements

* PHP 8.0
* NodeJS / NPM
* Composer (and the requirements mentioned in composer.json)

## Contributing

### Translation

We use Weblate for managing
translations. [Click here to help with translations](https://weblate.k118.de/projects/kstats/web/).

<a href="https://weblate.k118.de/engage/kstats/">
<img src="https://weblate.k118.de/widgets/kstats/-/web/multi-auto.svg" alt="Translation status" />
</a>

### Development
Glad you want to help! :) Feel free to work your magic and create a PullRequest. To install KStats, just follow these
steps:

1. Clone the repository
2. Run ``npm install`` and ``npm run dev`` to parse CSS and JS
3. Run ``composer install`` to install all dependencies
4. Copy ``.env.example`` to ``.env`` and adapt the values
5. Run ``php artisan serve`` to start built-in developement Webserver (or use your own)