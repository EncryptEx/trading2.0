<?php
session_start();
// script to buy countries
require 'utils.php';

function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			header("location:map.php?e=1");
			die();
		}
	}
}


checkVal($_POST['countryCode']);



$userId = $_SESSION['usr'];
$countryCode = $_POST['countryCode'];


//check if country exists.
if(!doesCountryExist($countryCode) || isCountryOwned($countryCode)['bool']) {
    header('location:map.php?e=18');
}

// check if has money to afford in USD, so mrktId = 0
// pdo select

$canAfford = canAfford($countryBasePrice, $userId, 0);
if ($canAfford[0]) { //bol value is [0]
	substract(0, $userId, $countryBasePrice);
    setCountryOwner($countryCode, $userId);
	header("location:map.php?s=1&v=" . getCountryName($countryCode));
} else {

	header("location:map.php?e=2&v=" . $canAfford[1]);
	die();
}
