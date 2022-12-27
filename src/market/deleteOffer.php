<?php 
session_start();
// script to buy and sell actions from markets 
require './../utils.php';
Islogged($_SESSION['usr']);

function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			if (isset($_POST['marketId'])) {
				header("location:market.php?marketid=" . $_POST['marketId'] . "&e=1");
				die();
			} else {
				header("location:market.php?e=1");
				die();
			}
		}
	}
}


checkVal($_POST['id'], $_POST['marketId']);


$userid = $_SESSION['usr'];


$offerId = $_POST['id'];


// remove offer
$removeResult = removeOffer($offerId, $userid);
if($removeResult == NULL) {
    header("location:market.php?marketid=".$_POST['marketId']."&e=16");
    die();
}

// add quantity to USD wallet  IF type = 0, else, return the coins in the market
$injectRes = inject($userid, $removeResult['marketId'], $removeResult['quantity']);

if ($injectRes) {
    header("location:market.php?marketid=" . $_POST['marketId'] . "&s=3&v=" . ($removeResult['quantity']));
} else {
    header("location:market.php?marketid=" . $_POST['marketId'] . "&e=16");
}
