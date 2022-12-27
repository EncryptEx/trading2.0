<?php
session_start();
// script to buy countries
require 'utils.php';
Islogged($_SESSION['usr']);

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


checkVal($_POST['countryCode'], $_POST['bet']);



$userId = $_SESSION['usr'];
$countryCode = $_POST['countryCode'];
$bet = $_POST['bet'];


//check if country exists.
if (!doesCountryExist($countryCode) || !boolval(doesAuctionExist($countryCode))) {
    header('location:map.php?&e=19');
}

// get last bet and check if was the same user, if yes, throw error
$lastBet = getLastBet($countryCode);
if($lastBet != NULL && $lastBet['ownerId'] == $userId){
    header('location:auction.php?c='. $countryCode . '&e=21');
    die();
}

// throw error if bidder is auction owner.
$auctionInfo = getAuctionInfo($countryCode);
if($auctionInfo['ownerId'] == $userId) {
    header('location:auction.php?c='. $countryCode . '&e=22');
    die();
}

// check if has already ended
if($auctionInfo['endAuction'] != NULL && $auctionInfo['endAuction'] <= time()) {
    header('location:map.php?e=24');
    die();
}

// check if has money to afford in USD, so mrktId = 0
// pdo select

$canAfford = canAfford($bet, $userId, 0);
if ($canAfford[0]) { //bol value is [0]

    //check if bet is higher than last bet or minimum price. 
    if ($lastBet != NULL) {
        $minimumBet = $lastBet['bet'];
    } else {
        $minimumBet = $auctionInfo['startingPrice'];
    }
    if ($bet <= $minimumBet) {
        // not a bet bc is smaller than last bet or starting price
        header('location:auction.php?c=' . $countryCode . "&e=20");
        die();
    }


    // refund the money to the older bidder.
    if($lastBet != NULL) {
        // there's an older bet
        inject($lastBet['ownerId'], 0, $lastBet['bet']);
    }
    

    substract(0, $userId, $bet);
    $result = createBet($countryCode, $userId, $bet);
    if ($result) {
        header("location:auction.php?c=" . $countryCode ."&s=1&v=" . $bet);
    } else {
        header("location:auction.php?c=" . $countryCode . "&e=16");
    }
} else {

    header("location:auction.php?c=" . $countryCode . "&e=2&v=" . $canAfford[1]);
    die();
}
