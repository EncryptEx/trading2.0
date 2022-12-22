<?php
session_start();
// script to buy countries
require 'utils.php';

function checkVal($v)
{
    $args = func_get_args();
    foreach ($args as $v) {
        if (!isset($v)) {
            if (isset($_POST['countryCode'])) {
                header("location:auction.php?e=1&c=" . $_POST['countryCode']);
            } else {
                header("location:map.php?e=1&");
            }
            die();
        }
    }
}


checkVal($_POST['countryCode']);



$userId = $_SESSION['usr'];
$countryCode = $_POST['countryCode'];


//check if country exists.
if (!doesCountryExist($countryCode) || !boolval(doesAuctionExist($countryCode))) {
    header('location:map.php?&e=19');
}

// check if is owner of auction
if(getAuctionInfo($countryCode)['ownerId'] != $userId){
    header('location:auction.php?c='.$countryCode.'&e=23');
}
// check that does not have an existing endtimestamp
if(getAuctionInfo($countryCode)['endAuction'] != NULL){
    header('location:auction.php?c='.$countryCode.'&e=24');
}


// if auction is empty of bids, do not transfer property and delete everything without timeout.
$lastBet = getLastBet($countryCode);

if($lastBet != NULL){
    // start timestamp countdown
    $tomorrowTimestamp = time() + 86400;

    $result = auctionSetEndTimestmp($countryCode, $userId, $tomorrowTimestamp);
    if($result) {
        header('location:auction.php?c='.$countryCode.'&s=2');
    } else {
        header('location:map.php?e=16');
    }
} else {
    $result = removeAuction($countryCode, FALSE);
    

    if($result) {
        header('location:map.php?s=3');
    } else {
        header('location:map.php?e=16');
    }
}
?>
se fini
