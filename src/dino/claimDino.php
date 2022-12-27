<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			header("location:dino.php");
			die();
		}
	}
}
checkVal($_POST['m'], $_SESSION['dinoCoinsSpent'], $_SESSION['dinoMarket']);

$multiplier = $_POST['m'];

$coinsSpent = $_SESSION['dinoCoinsSpent'];

$newCoins = $multiplier * $coinsSpent;
$marketIdToInject = $_SESSION['dinoMarket'];
$marketVal = getValue($marketIdToInject);
if($multiplier == 0){
	// player lost all. depositing all to jackpot
	jackpotDeposit($coinsSpent*$marketVal, $userid);
	jackpotDeposit(getJackPotValue()*($_SESSION['dinoMaxMilis']/1000), -1);
	header("location:dino.php?s=2&v=($". ($newCoins*$marketVal));
	die();
	
}
unset($_SESSION['dinoCoinsSpent']);
unset($_SESSION['dinoMarket']);
unset($_SESSION['dinoMaxMilis']);

inject($userid, $marketIdToInject, $newCoins);

header("location:dino.php?s=1&v=".$newCoins." ". getName($marketIdToInject). " ($". ($newCoins*$marketVal) .") = ". $multiplier ." x ". $coinsSpent);

