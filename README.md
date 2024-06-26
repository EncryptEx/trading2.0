<p align="center"><a href="#"><img src="https://www.iconpacks.net/icons/1/free-bitcoin-icon-798-thumb.png" alt="Coin" height="60"/></a></p>
<h1 align="center">Tradin'Zone</h1>
<p align="center">The funniest fake-trading platform for friends. Now reversioned.</p>
<p align="center">
<img src="https://img.shields.io/github/repo-size/EncryptEx/trading2.0"/>
<img src="https://img.shields.io/github/languages/top/EncryptEx/trading2.0"/>
<img src="https://img.shields.io/github/last-commit/EncryptEx/trading2.0"/>
<img src="https://img.shields.io/badge/License-MIT-green"/>

## Arquitecture

Project designed using PHP, SQL, JS, CSS, HTML.

Used Bootstrap 4 framework. Migration to BS5 TBD.

Also used some tools:
Google Recaptcha, ChartJS, Particles.js, CountryFlags API (deprecated, now using it self-hosted), SimpleMaps.</p>

## Story

Have you ever wondered to have a little trading platform in which you could bet with your friends (fake money) and learn some basics of the economy? Well, from the moment where I started thinking that, I decided that I had to code something for us to have fun, and that's where Tradin'Zone comes.

This is the second version and public one. The first one didn't use the offer-demand function basis.

## Installation

1. First, you'll have to paste the code inside the public folder of your hosting provider.

2. Then you'll have to create the ``src/credentials.php`` file, you can do it by changing the file name:

```sh
cd src && mv credentials.example.php credentials.php
```

Next, you'll have to add your secret keys, as explained inside the credentials.php file.


3. Once credentials are correct and saved into the file, you can run the website and access the installation path (let's say your website is example.com, the installation location would be:
**example.com/install**

4. You'll see a form asking you to create a user with its password. You may also check if the DB credentials are correct.

5. After creating the user, the installation wizard will report if there was any problem while installing Tradin'Zone.

6. Once everything is ok, you can click the **DELETE INSTALLATION FILE** button to conclude the installation and avoiding anyone to expose your database credentials.

## Automatizations
These actions need to be preformed automatically and periodically, so I recommend you to use [UptimeRobot](https://uptimerobot.com/).

Regarding the __airdrops__: Make an HTTP request **hourly** to:

``example.com/airdrops/genereateAirdrops.php?auth=YourPasswordSavedInCredentialsFile``

Regarding the __passive income country service__: Make an HTTP request **daily** to:

``example.com/map/passiveIncome.php?auth=YourPasswordSavedInCredentialsFile``


## Extra

(Work in Progress) I'm using Conventional Commits to create [standarized commits](https://www.conventionalcommits.org/en/v1.0.0/). 



<p align="center"><a href="https://github.com/EncryptEx/trading/"><img src="http://randojs.com/images/barsSmallTransparentBackground.gif" alt="Animated footer bars" width="100%"/></a></p>