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


checkVal($_POST['countryCode'], $_POST['startingPrice']);



$userId = $_SESSION['usr'];
$countryCode = $_POST['countryCode'];
$startingPrice = $_POST['startingPrice'];


//check if country does not exist or is not the owner.
if(!doesCountryExist($countryCode) || !doesUserOwnThisCountry($userId, $countryCode)) {
    header('location:map.php?e=18');
}



// create auction
$result = createCountryAuction($countryCode, $userId, $startingPrice);

if($result) {
    header("location:map.php?s=2&v=" . getCountryName($countryCode));

} else {
    header('location:map.php?e=16');
}
