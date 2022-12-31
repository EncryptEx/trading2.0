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
checkVal($_POST['ntickets']);

$ticketQ = $_POST['ntickets'];
$ticketTotalCost = $ticketQ * getLotteryTicketPrice();
$result = canAfford($ticketTotalCost, $userid, 0);
if ($result[0]) {
    // remove $
    substract(0, $userid, $ticketTotalCost);
    // add funds inverted into tickets to the jackpot
    jackpotDeposit($ticketTotalCost, $userid);
    // create db entry of ownership
    buyLotteryTickets($userid, $ticketQ);

    header("location:lottery.php?s=1&v=".$ticketQ);
} else {
    header("location:lottery.php?e=2&v=".$result[1]);
}


