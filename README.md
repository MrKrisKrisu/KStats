# KStats

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/e2ba9f2772e24d8d9fae5f9e8955d70c)](https://app.codacy.com/manual/MrKrisKrisu/KStats?utm_source=github.com&utm_medium=referral&utm_content=MrKrisKrisu/KStats&utm_campaign=Badge_Grade_Dashboard)
![PHPUnit](https://github.com/MrKrisKrisu/KStats/workflows/PHPUnit/badge.svg)

![Screenshot](screenshot.png)

## Features

* Track your heared Songs from Spotify
    * See your Top tracks, artists
    * See how many minutes you've listened to music
    * Generate Playlists with "Lost Tracks" which you like but haven't heard for a long time
    * Generate Playlists with your TopTracks - updated daily
* Analyze what you've bought in the supermarket
    * Send your Receipts to an E-Mail Catcher
    * See your top Products
    * Get an prediction of what you could buy
    * See how many money you spent by categories
    * Currently only compatible with digital receipts from REWE (in Germany)

## Requirements

* PHP >= 8.0
* NodeJS / NPM
* Composer

## Installation and Contributing

Nice, that you want to help! :) Feel free to make your magic und create a PullRequest. To install KStats just follow
these steps:

1. Clone the repository
2. Run ``npm install`` and ``npm run dev`` to parse CSS and JS
3. Run ``composer install`` to install all dependencies
4. Run ``php artisan serve`` to start built-in developement Webserver (or use your own)