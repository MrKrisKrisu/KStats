# KStats

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/4dfdd959ca7f4b07b7b6fd57f1f92c91)](https://www.codacy.com/gh/MrKrisKrisu/KStats/dashboard)
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