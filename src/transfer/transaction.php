<?php
session_start();
// script to transfer USD funds to other users
require './../utils.php';

function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			header("location:transfer.php?e=1");
			die();
		}
	}
}


checkVal($_POST['money'], $_POST['destinatary'], $_SESSION['usr']);

//  check if destinatary is not itself
if ($_POST['destinatary'] == $_SESSION['usr']) {
	header("transfer.php?e=15");
	die();
}


// check if has money to afford
// pdo select
// marketid 0 = USD STABLE WITHDRAWED MONEY
$moneySpent = $_POST['money'];
if ($moneySpent == 0 || $moneySpent == "0") {

	header("location:transfer.php?e=3&v=$" . $_POST['money']);
	die();
}
$toUserID = $_POST['destinatary'];
$fee = $FEE * $moneySpent;
//buy fee but a little fee.
$canAfford = canAfford($moneySpent+$fee, $_SESSION['usr'], 0);
if ($canAfford[0]) { //bol value is [0]
	$quantity = $moneySpent;
	$fromUserID = $_SESSION['usr'];
	transfer($fromUserID, $toUserID, $quantity, $fee); //always working with market id 0 (USD)
	header("location:transfer.php?s=1&v=sent $" . (round($_POST['money'], 10) . " to the user " . getUserName($toUserID) . " with a transaction cost of $" . round($_POST['money'] * $FEE, 3)));
} else {

	header("location:transfer.php?e=2&v=" . $canAfford[1]);
	die();
}
