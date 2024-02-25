<?php
require './../utils.php';


// check if request is auth
if(!isset($_GET['auth'])){
	header("HTTP/1.1 403 Forbidden");
	die('No code given');
}
if($_GET['auth'] != $desiredAuthKey){
	header("HTTP/1.1 403 Forbidden");
	die('No code given');
}


$lastPrize = getLastJackpotWinner();
// if in 1 week actual times's higher, lottery has to be runned.
if($lastPrize['data']['timestamp'] + 604.800 < time()){
	// get all ticket count
	$tickets = [];
	foreach (getUserIDs()->fetchAll() as $user) {
		$tickets[$user['id']] = getLotteryTicketCount($user['id']);
	}
	$maxTickets = array_sum($tickets);
	
	if($maxTickets < 1){
		die('Cannot run a lottery. watiting for more tickets');
	}

	$winnerNumber = random_int(1,$maxTickets);

	$iterator=0;
	foreach ($tickets as $ownerId => $ticketsOwned) {
		// if the number is higher than the user tickets and the last one checked, there's no need to loop through them.
		if($winnerNumber > $ticketsOwned+$iterator ){
			// can skip user
			$iterator = $iterator + $ticketsOwned;
			continue;
		}
		
		if($winnerNumber>$iterator and $ticketsOwned+$iterator >=$winnerNumber) { 
			$userWinner = $ownerId;
			break;
		}
	}
	$priceWon = getJackPotValue();
	$err = inject($userWinner, 0, $priceWon);
	// save win lottery action
	$timestamp = time();
	addLotteryWinner($userWinner,$priceWon, $timestamp);
	// remove all tickets
	wipeTicketOwnership($timestamp);
	
	// set jackpot to 0
	clearJackpot();
	
} else {
	echo "Lottery not ready to roll.";
}

