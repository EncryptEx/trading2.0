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
} else {
    $lastTimestamp = $lastPrize['timestamp'];
}

	// Pay each user
	foreach (getAllOwnedCountries() as $ownedCountry) {
        $valueToPay = round(getCountryBasePrice($ownedCountry['countryCode'])/1000,0);
        // get random market to pay
        $markets = getMarkets()->fetchAll();
        $marketToPay = $markets[random_int(0,count($markets)-1)]['id'];
        // convert USD to pay to market price. to pay in nº coins
        $cointsToPay = $valueToPay / getValue($marketToPay);
        // get owner of recipient
        $recipientId = $ownedCountry['ownerId'];
        // pay
        inject($recipientId, $marketToPay, $cointsToPay);
        // save log pay action
        savePayRecord($recipientId, $ownedCountry['countryCode'], $cointsToPay, $marketToPay, time());
    }
}
