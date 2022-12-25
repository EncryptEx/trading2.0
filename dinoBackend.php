<?php
session_start();
// script to dino USD funds to other users
require 'utils.php';

function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			header("location:dino.php?e=1");
			die();
		}
	}
}


checkVal($_POST['coins'], $_POST['marketId'], $_SESSION['usr']);

// check if has money to afford
// pdo select

$coinsSpent = $_POST['coins'];
$marketid = $_POST['marketId'];
$marketName = getName($marketid);
if ($marketName == NULL){
	header("location:dino.php?e=26");
	die();
}
if($coinsSpent == 0 || $coinsSpent == "0") {

	header("location:dino.php?e=27");
	die();
}
$fee = $FEE * $coinsSpent;

// get full value in dollars to spend
$coinsTotal = ($coinsSpent+$fee);
$canAfford = canAfford($coinsTotal, $_SESSION['usr'], $_POST['marketId']);
if ($canAfford[0]) { //bol value is [0]
	$_SESSION['dinoMaxMilis'] = getdinoMaxMilis();
	$_SESSION['dinoCoinsSpent'] = $coinsSpent;
	substract($marketid, $_SESSION['usr'], $coinsTotal);
	header("location:dinoPlay.php");
} else {
	header("location:dino.php?e=25&v=" . $coinsTotal . " " . $marketName);
	die();
}
