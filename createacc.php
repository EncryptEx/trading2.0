<?php
session_start();
require 'utils.php';
if (isset($_SESSION['usr'])) {
	header("location:index.php");
	die();
}
function checkVal($v)
{
	$args = func_get_args();
	foreach ($args as $v) {
		if (!isset($v)) {
			header("location:login.php?e=1");
			die();
		}
	}
}

checkVal($_POST['username'], $_POST['password'], $_POST['name']);
$usernm = $_POST['username'];
$passwd = $_POST['password'];
$name = $_POST['name'];
if(strlen($usernm) > 20 || strlen($name) > 20) {
	header("location:register.php?e=14");
	die();
}

if (!doesExistUser($usernm)) {
	$userid = CreateUser($usernm, $name, $passwd, 1);
	addition($userid, 0, 1000);

} else {
	header('location:register.php?e=13'); // not correct 
	die();
}

$result = loginUser($usernm, $passwd);
if ($result[0]) {
	$_SESSION['usr'] = $result[1];
	setcookie("userid", encrypt($result[1]), time() + (86400 * 7), "/");
	header("location:index.php"); //login success
	die();
} else {
	switch ($result[1]) {
		case 1:
			header("location:register.php?e=8"); // account banned
			break;

		default:
			header('location:register.php?e=9'); // not correct 
			break;
	}

	die();
}
