<?php
session_start();
require 'utils.php';
if (isset($_SESSION['usr'])) {
	header("location:index.php");
	die();
}
if (isset($_COOKIE['userid'])) {
	$id = decrypt(urldecode($_COOKIE['userid']));
	if (is_numeric($id)) {
		$cookieResult = loginUserById($id);
		if ($cookieResult[0]) {
			$_SESSION['usr'] = $cookieResult[1];
			header("location:index.php"); //login success
			die();
		} else {
			switch ($cookieResult[1]) {
				case 1:
					header("location:login.php?e=8"); // account banned
					break;

				default:
					header('location:login.php?e=9'); // not correct 
					break;
			}

			die();
		}
	}
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

checkVal($_POST['username'], $_POST['password']);

$result = loginUser($_POST['username'], $_POST['password']);
if ($result[0]) {
	$_SESSION['usr'] = $result[1];
	setcookie("userid", encrypt($result[1]), time() + (86400 * 7), "/");
	header("location:index.php"); //login success
	die();
} else {
	switch ($result[1]) {
		case 1:
			header("location:login.php?e=8"); // account banned
			break;

		default:
			header('location:login.php?e=9'); // not correct 
			break;
	}

	die();
}
