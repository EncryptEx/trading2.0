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
$maxMult = ($_SESSION['dinoMaxMilis'])/1000;
if($multiplier == 0){
	// player lost all. depositing all to jackpot
	$lostMaxMoney = $coinsSpent*$marketVal*$maxMult;
	jackpotDeposit($lostMaxMoney, $userid);
	header("location:dino.php?s=2&v=($". ($lostMaxMoney));
	die();
	
}
unset($_SESSION['dinoCoinsSpent']);
unset($_SESSION['dinoMarket']);
unset($_SESSION['dinoMaxMilis']);

$newDollars = $newCoins*$marketVal;
inject($userid, 0, $newDollars);

header("location:dino.php?s=1&v=".$newCoins." ". getName($marketIdToInject). " ($". ($newDollars) .") = ". number_format($multiplier, 2) ." x ". $coinsSpent) . ' coins';

