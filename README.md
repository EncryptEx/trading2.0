<p align="center"><a href="#"><img src="https://www.iconpacks.net/icons/1/free-bitcoin-icon-798-thumb.png" alt="Coin" height="60"/></a></p>
<h1 align="center">Tradin'Zone</h1>
<p align="center">The funniest fake-trading platform for friends. Now reloaded.</p>
<p align="center">
<img src="https://img.shields.io/github/repo-size/EncryptEx/trading2.0"/>
<img src="https://img.shields.io/github/languages/top/EncryptEx/trading2.0"/>
<img src="https://img.shields.io/github/last-commit/EncryptEx/trading2.0"/>
<img src="https://img.shields.io/badge/License-MIT-green"/>

## Architecture

Project designed using PHP, SQL, JS, CSS, HTML.

Used Bootstrap 4 framework. Migration to BS5 TBD.

Also used some tools:
Google Recaptcha, ChartJS, Particles.js, CountryFlags API (deprecated, now using it self-hosted), SimpleMaps.</p>

## Story

Have you ever wondered to have a little trading platform in which you could bet with your friends (fake money) and learn some basics of the economy? Well, from the moment where I started thinking that, I decided that I had to code something for us to have fun, and that's where Tradin'Zone comes.

This is the second version and public one. The first one didn't use the offer-demand function basis.

## Installation

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/EncryptEx/trading2.0.git
   cd trading2.0
   ```

2. **Configure Environment Variables**
   Copy the example environment file and configure it:
   ```bash
   cp .env.example .env
   ```
   Edit `.env` and fill in your database credentials, port settings, and API keys.

3. **Start with Docker**
   Build and start the containers:
   ```bash
   docker-compose up -d --build
   ```

4. **Web Installation**
   Once the containers are running, access the installation wizard in your browser:
   `http://localhost:<TRADING_PORT>/install`
   *(Replace `<TRADING_PORT>` with the port you defined in your `.env` file)*

   Follow the on-screen instructions to create your admin user and initialize the database.

5. **Cleanup**
   After the installation is complete, click the **DELETE INSTALLATION FILE** button in the wizard to secure your installation.

## Automatizations
These actions need to be preformed automatically and periodically, so I recommend you to use [UptimeRobot](https://uptimerobot.com/).

Regarding the __airdrops__: Make an HTTP request **hourly** to:

``example.com/airdrops/genereateAirdrops.php?auth=YourPasswordSavedInCredentialsFile``

Regarding the __passive income country service__: Make an HTTP request **daily** to:

``example.com/map/passiveIncome.php?auth=YourPasswordSavedInCredentialsFile``


## Extra

(Work in Progress) I'm using Conventional Commits to create [standardized commits](https://www.conventionalcommits.org/en/v1.0.0/). 



<p align="center"><a href="https://github.com/EncryptEx/trading/"><img src="http://randojs.com/images/barsSmallTransparentBackground.gif" alt="Animated footer bars" width="100%"/></a></p>