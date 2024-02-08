<?php
session_start();
// script to buy and sell actions from markets 
require './../utils.php';

function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			if (isset($_POST['referrer'])) {
				header("location:market.php?marketid=" . $_POST['referrer'] . "&e=1");
				die();
			} else {
				header("location:market.php?e=1");
				die();
			}
		}
	}
}


checkVal($_POST['coins'], $_POST['money'], $_POST['referrer'], $_SESSION['usr'], $_GET['m']);


$userid = $_SESSION['usr'];

// check if wants to buy
if ($_GET['m'] == 1) {

	// check if has money to afford
	// pdo select
	// marketid 0 = USD STABLE WITHDRAWED MONEY
	$moneySpent = $_POST['money'];
	if ($moneySpent == 0 || $moneySpent == "0") {
		if (isset($_POST['referrer'])) {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=3");
			die();
		} else {
			header("location:market.php?e=3&v=" . $_POST['coins'] . "($" . $_POST['money'] . ")");
			die();
		}
	}
	$marketId = base64_decode($_POST['referrer']);
	// $fee = 0.001 * getValue($toID);
	//buy fee but a little fee.

	$canAfford = canAfford($moneySpent, $userid, 0);
	if ($canAfford[0]) { //bool value is [0]

		// $quantity = $moneySpent;
		// if ($fee >= $quantity) {
		// 	header("location:market.php?marketid=" . $_POST['referrer'] . "&e=4");
		// 	die();
		// }
		$fee = 0;
		$dollars = $_POST['money'];
		$coins = $_POST['coins'];
		if (!is_numeric($dollars) || !is_numeric($coins)) {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=12");
			die();
		}
		
		
		$pricePerUnit = $dollars / $coins;
		
		$offerResult = exchange(0, $marketId, $fee, $_SESSION['usr'], $dollars);
		
		// $offerResult = newOffer("BUY", $userid, $marketId, $coins, $dollars, $pricePerUnit);
		// remove quantity from USD wallet

		if ($offerResult) {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&s=1&v=" . (round($_POST['coins'], 10)) . "&v2=" . (round($moneySpent, 10)) . "&v3=" . (round($pricePerUnit, 10)));
		} else {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=16");
		}
	} else {
		if (isset($_POST['referrer'])) {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=2&v=" . $canAfford[1]);
			die();
		} else {
			header("location:market.php?e=2&v=" . $canAfford[1]);
			die();
		}
	}
} else if ($_GET['m'] == 2) {
	$marketId = base64_decode($_POST['referrer']);
	$dollars = $_POST['money'];
	$coins = $_POST['coins'];
	// $fee = 0.01 * getValue($marketid);
	// if ($fee >= $dollars) {
	// 	header("location:market.php?marketid=" . $_POST['referrer'] . "&e=5");
	// 	die();
	// }
	$mrktVal = getValue($marketId);
	$coins = $dollars / $mrktVal;
	$fee = 0;
	$hasMoney = canAfford($coins, $userid, $marketId);
	if ($hasMoney[0]) {
		if (!is_numeric($dollars) || !is_numeric($coins)) {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=12");
			die();
		}
		// $pricePerUnit = $dollars / $coins;

		$offerResult = exchange($marketId, 0, $fee, $userid, $dollars);
		// $result = newOffer("SELL", $userid, $marketId, $coins, $dollars, $pricePerUnit);
		
		if ($offerResult) {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&sell=true&s=2&v=" . (round($_POST['coins'], 10)) . "&v2=" . (round($dollars, 10)) . "&v3=" . (round($pricePerUnit, 10)));
		} else {
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=16");
		}
	} else {
		if (isset($_POST['referrer'])) {
			$n = getName($marketId);
			header("location:market.php?marketid=" . $_POST['referrer'] . "&e=3&sell=true&v=" . $hasMoney[1] . " " . $n . "s and you requested: " . $coins . " " . $n . "/s ($" . $dollars . ")");
			die();
		} else {
			header("location:market.php?e=3&sell=true&v=" . $canAfford[1]);
			die();
		}
	}
}
