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


$airdrop = getLastAirdrop();
$IsAirdrop = FALSE;
if ($airdrop['status'] != FALSE) {
	$IsAirdrop = TRUE;
}
$markets = getMarkets()->fetchAll();

if (!$IsAirdrop) {
	$mkid = $markets[random_int(0, count($markets) - 1)]['id'];
	// marketid $quantity $uses $timestamp $ftimestamp 
	$startingTime = time() + random_int(120, 3600*6);
	generateAirdrop($mkid, random_int(1000, 6500) / getValue($mkid), 0, $startingTime, $startingTime + random_int(60, 3600 * 2));
}
