<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

$isNextAirdropReady = FALSE;
$airdrop = getLastAirdrop();
$isExpired = FALSE;
$IsAirdrop = FALSE;
if ($airdrop['status']) {
	if ($airdrop['timestamp'] <= time()) {
		$isNextAirdropReady = TRUE;
	}
	$IsAirdrop = TRUE;
	if ($airdrop['ftimestamp'] <= time()) {
		// invalidate airdrop 
		// echo 'airdrop expired.';
		expireAirdrop();
		$isExpired = TRUE;
	}
}
$response = $_POST["g-recaptcha-response"];
$url = 'https://www.google.com/recaptcha/api/siteverify';
$data = array(
	'secret' => $captchaSecret,
	'response' => $_POST["g-recaptcha-response"]
);
$options = array(
	'http' => array(
		'method' => 'POST',
		'content' => http_build_query($data)
	)
);
$context  = stream_context_create($options);
$verify = file_get_contents($url, false, $context);
$captcha_success = json_decode($verify);
var_dump($captcha_success);

$recaptcha = $captcha_success->success; //bool


if ($isNextAirdropReady && $IsAirdrop && !$isExpired && $recaptcha) {
	$quantity = $airdrop['quantity'];
	$marketid = $airdrop['marketid'];
	inject($userid, $marketid, $quantity);
	jackpotDeposit($quantity, $userid);
	claimAirdrop($userid);
} else {
	header('location:airdrop.php');
}
