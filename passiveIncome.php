<?php
require 'utils.php';


// check if request is auth
if(!isset($_GET['auth'])){
	header("HTTP/1.1 403 Forbidden");
	die('No code given');
}
if($_GET['auth'] != $desiredAuthKey){
	header("HTTP/1.1 403 Forbidden");
	die('No code given');
}


$lastPayAll = getLastCountryPayAll();
if (time() - $lastPayAll['timestamp'] <= 86400) {
    // save to db payAll timestamp
    savePayAllRecord(time());

	// Pay each user
	foreach (getAllOwnedCountries() as $ownedCountry) {
        $valueToPay = round(getCountryBasePrice($ownedCountry['countryCode'])/1000,0);
        // TODO: insert a new way to get funds, not only USD to make more fun the game and introduce criptos in it. 
        // $marketToPay = $ownedCountry['marketReturn'];
        // TODO: convert USD to pay to market price. to pay in nº coins
        $marketToPay = 0;
        $recipientId = $ownedCountry['ownerId'];
        inject($recipientId, $marketToPay, $valueToPay);
    }
}
