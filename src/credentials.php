<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$dotenv->required([
    "DB_HOST",
    "DB_PORT",
    "DB_NAME",
    "DB_USER",
    "DB_PASS",
    "DB_CHARSET",
    "PRIV_KEY",
]);

// Database Credentials
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$dbname = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];
$charset = $_ENV['DB_CHARSET'];

// App Config
$privkey = $_ENV['PRIV_KEY'];
$captchaSecret = $_ENV['GOOGLE_CAPTCHA_SECRET'] ?? '';
$captchaPublic = $_ENV['GOOGLE_CAPTCHA_PUBLIC'] ?? '';

// Other Configs found in env list
$tradingPort = $_ENV['TRADING_PORT'] ?? 80;
$desiredAuthKey = $_ENV['DESIRED_AUTH_KEY'] ?? '';
$countyBasePrice = $_ENV['COUNTY_BASE_PRICE'] ?? 10000;
$FEE = $_ENV['TRANSACTION_FEE'] ?? 0.01;
