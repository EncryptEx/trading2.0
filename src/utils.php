<?php
//
// Utils File --> Main File where magic happens
//
require 'credentials.php';

// error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);


// Start DB
$dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
$options = [
	PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // highly recommended
	PDO::ATTR_EMULATE_PREPARES => false // ALWAYS! ALWAYS! ALWAYS!
];
$pdo = new PDO($dsn, $user, $pass, $options);

function Islogged($usr)
{
	if (!isset($usr)) {
		header("location:login.php");
		die();
	}
}


// global used constant
$countryAcronyms = ["AF", "AO", "AL", "AE", "AR", "AM", "AU", "AT", "AZ", "BI", "BE", "BJ", "BF", "BD", "BG", "BH", "BA", "BY", "BZ", "BO", "BR", "BN", "BT", "BW", "CF", "CA", "CH", "CL", "CN", "CI", "CM", "CD", "CG", "CO", "CR", "CU", "CZ", "DE", "DJ", "DK", "DO", "DZ", "EC", "EG", "ER", "EE", "ET", "FI", "FJ", "GA", "GB", "GE", "GH", "GN", "GM", "GW", "GQ", "GR", "GL", "GT", "GY", "HN", "HR", "HT", "HU", "ID", "IN", "IE", "IR", "IQ", "IS", "IL", "IT", "JM", "JO", "JP", "KZ", "KE", "KG", "KH", "KR", "XK", "KW", "LA", "LB", "LR", "LY", "LK", "LS", "LT", "LU", "LV", "MA", "MD", "MG", "MX", "MK", "ML", "MM", "ME", "MN", "MZ", "MR", "MW", "MY", "NA", "NE", "NG", "NI", "NL", "NO", "NP", "NZ", "OM", "PK", "PA", "PE", "PH", "PG", "PL", "KP", "PT", "PY", "PS", "QA", "RO", "RU", "RW", "EH", "SA", "SD", "SS", "SN", "SL", "SV", "RS", "SR", "SK", "SI", "SE", "SZ", "SY", "TD", "TG", "TH", "TJ", "TM", "TL", "TN", "TR", "TW", "TZ", "UG", "UA", "UY", "US", "UZ", "VE", "VN", "VU", "YE", "ZA", "ZM", "ZW", "SO", "GF", "FR", "ES", "AW", "AI", "AD", "AG", "BS", "BM", "BB", "KM", "CV", "KY", "DM", "FK", "FO", "GD", "HK", "KN", "LC", "LI", "MF", "MV", "MT", "MS", "MU", "NC", "NR", "PN", "PR", "PF", "SG", "SB", "ST", "SX", "SC", "TC", "TO", "TT", "VC", "VG", "VI", "CY", "RE", "YT", "MQ", "GP", "CW", "IC"];


// Starts main utilities file

/** 
 * Returns the DB row of the market
 * @return array|int can be 0 if market is not found
 */
function getValue($markid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-value` WHERE marketid=:marketid ORDER BY `id` DESC ";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['marketid' => $markid];
	$selectStmt->execute($input);
	if ($markid == 0) {
		return 1;
	}
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			return $row['value'];
		}
	}
	return 0;
}
function getName($markid)
{
	global $pdo;
	if ($markid == 0) {
		return 'USD';
	}
	$SQL_SELECT = "SELECT * FROM `market-list` WHERE id=:id LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $markid];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			return $row['name'];
		}
	}
	return NULL;
}


function getImageUrl($markid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-list` WHERE id=:id LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $markid];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			return $row['logo'];
		}
	}
	return 0;
}

function getBalance($userid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-balances` WHERE ownerid=:ownerid";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerid' => $userid];
	$selectStmt->execute($input);
	$total = 0;
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			$total = $total + ($row['quantity'] * getValue($row['marketid']));
		}
	}
	// apart from adding balance in coins, also add made offers
	$SQL_SELECT = "SELECT * FROM `market-offers` WHERE ownerId=:ownerId";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerId' => $userid];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			$total = $total + ($row['USD']);
		}
	}
	return $total;
}
function getArrayBalances($userid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-balances` WHERE ownerid=:ownerid";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerid' => $userid];
	$selectStmt->execute($input);
	$total = [];
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			array_push($total, array(getName($row['marketid']), $row['quantity'] * getValue($row['marketid'])));
		}
	}
	return $total;
}
function getUserName($userid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-users` WHERE id=:id";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $userid];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			return $row['name'];
		}
	}
	return false;
}
function getLogo($marketid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-list` WHERE id=:id";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $marketid];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			return $row['logo'];
		}
	}
	return false;
}
function getOwnership($marketid, $userid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-balances` WHERE ownerid=:ownerid AND marketid=:marketid LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerid' => $userid, 'marketid' => $marketid];
	$selectStmt->execute($input);
	$total = 0;
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			$total = $row['quantity'];
		}
	}
	return $total;
}
function doesOwnershipRowExist($marketid, $userid)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-balances` WHERE ownerid=:ownerid AND marketid=:marketid LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerid' => $userid, 'marketid' => $marketid];
	$selectStmt->execute($input);
	$total = false;
	if ($selectStmt->rowCount() > 0) {
		$total = true;
	}
	return $total;
}
function hasClaimedAirdrop($userid, $airID)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-airdrops-claim` WHERE userid=:userid AND airdropID=:airdropID";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['userid' => $userid, 'airdropID' => $airID];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return true;
	}
	return false;
}
function getLastAirdrop()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-airdrops` WHERE active=1 ORDER BY `timestamp` DESC LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$selectStmt->execute([]);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			return ['status' => true, 'timestamp' => $row['timestamp'], 'quantity' => $row['quantity'], 'marketid' => $row['marketid'], 'ftimestamp' => $row['ftimestamp'], 'id' => $row['id']];
		}
	}
	return ['status' => false];
}
function expireAirdrop()
{
	global $pdo;
	$statement = "UPDATE `market-airdrops` SET active=0 WHERE active=1 LIMIT 1";
	$preparedstmt = $pdo->prepare($statement);
	$preparedstmt->execute([]);
}
function inject($userid, $marketid, $quantity)
{
	global $pdo;
	$ownershipmarket = doesOwnershipRowExist($marketid, $userid);
	if (!$ownershipmarket) {
		// not saved into db
		// insert:
		$SQL_INSERT = "INSERT INTO `market-balances` (id, ownerid, marketid, quantity) VALUES (NULL, :ownerid, :marketid, :quantity)";
		$insrtstmnt = $pdo->prepare($SQL_INSERT);
		return $insrtstmnt->execute(['quantity' => $quantity, 'ownerid' => $userid, 'marketid' => $marketid]);
	} else {
		$ActualQuantity = getOwnership($marketid, $userid);
		$money = $ActualQuantity + $quantity;
		$statement = "UPDATE `market-balances` SET quantity=:quantity WHERE ownerid=:ownerid AND marketid=:marketid LIMIT 1";
		$preparedstmt = $pdo->prepare($statement);
		return $preparedstmt->execute(['quantity' => $money, 'ownerid' => $userid, 'marketid' => $marketid]);
	}
}
function claimAirdrop($userid)
{
	global $pdo;
	$aird = getLastAirdrop();
	$isExpired = false;
	$IsAirdrop = false;
	if ($aird['status']) {
		if ($aird['timestamp'] <= time()) {
			$isNextAirdropReady = true;
		}
		$IsAirdrop = true;
		if ($aird['ftimestamp'] <= time()) {
			// invalidate airdrop
			// echo 'airdrop expired.';
			expireAirdrop();
			$isExpired = true;
		}

		if ($isNextAirdropReady && $IsAirdrop && !$isExpired && !hasClaimedAirdrop($userid, $aird['id'])) {
			$SQL_INSERT = "INSERT INTO `market-airdrops-claim` (id, airdropID, userid, quantity) VALUES (NULL, :airdropID, :userid, :quantity)";
			$insrtstmnt = $pdo->prepare($SQL_INSERT);
			$input = ['airdropID' => $aird['id'], 'userid' => $userid, 'quantity' => $aird['quantity'] * getValue($aird['marketid'])];
			$insrtstmnt->execute($input);
			header('location:airdrop.php?s=1');
		} else {
			header('location:airdrop.php?e=7');
		}
	} else {
		header('location:airdrop.php');
	}
}
function generateAirdrop($marketid, $quantity, $uses, $timestamp, $ftimestamp)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-airdrops` (id, marketid, quantity, uses, timestamp, ftimestamp, active) VALUES (NULL, :marketid, :quantity, :uses, :timestamp, :ftimestamp, :active)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['marketid' => $marketid, 'quantity' => $quantity, 'uses' => $uses, 'timestamp' => $timestamp, 'ftimestamp' => $ftimestamp, 'active' => 1];
	$insrtstmnt->execute($input);
}
function getUserIDs()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-users`";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt;
	}
	return false;
}

/**
 * Retrieves the user ID from a username. 
 * @return string|bool false when none found, 
 */
function getIdFromUserName(string $username)
{
	global $pdo;
	$SQL_SELECT = "SELECT id FROM `market-users` WHERE username=:username";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['username' => $username];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt->fetchAll()[0]['id'];
	}
	return false;
}
function getTopMarket()
{
	global $pdo;
	$c = 0;
	// foreach (getMarkets() as $row) {
	//   $c++;
	// }
	$SQL_SELECT = "SELECT MAX(`value`), `marketid` FROM `market-value`";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		// array_push($total, $row['value']);
		foreach ($selectStmt as $row) {
			// array_push($prices, $row['marketid'], $row['value']);
			return [$row['marketid'], $row['MAX(`value`)']];
		}
		// $value = max($prices);
		// return [$value,array_search($value, $prices)];
		// return $prices;
	}
}
function getTopAirdropClaim()
{
	global $pdo;
	$c = 0;
	// foreach (getMarkets() as $row) {
	//   $c++;
	// }
	$SQL_SELECT = "SELECT MAX(`quantity`) FROM `market-airdrops-claim`";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		// array_push($total, $row['value']);
		foreach ($selectStmt as $row) {
			if ($row['MAX(`quantity`)'] == null) {
				return [false];
			}
			// array_push($prices, $row['marketid'], $row['value']);
			return [true, $row['MAX(`quantity`)']];
		}
		// $value = max($prices);
		// return [$value,array_search($value, $prices)];
		// return $prices;
	}
	return [false];
}


function getMarkets()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-list`";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$selectStmt->execute([]);

	if ($selectStmt->rowCount() > 0) {
		return $selectStmt;
	}
	return false;
}
function doesExistUser($username)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-users` WHERE username=:username";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$selectStmt->execute(['username' => $username]);

	if ($selectStmt->rowCount() > 0) {
		return true;
	}
	return false;
}
function getPercentage($marketid)
{
	global $pdo;

	$SQL_SELECT = "SELECT * FROM `market-value` WHERE marketid=:marketid ORDER BY `id` DESC ";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$selectStmt->execute(['marketid' => $marketid]);
	$isf = true;
	$new = 0;
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			if ($isf) {
				$isf = false;
				$new = $row['value'];
				continue;
			}
			$old = $row['value'];
			if ($old <= 0 && $new <= 0 || $old == 0) {
				return 0;
			}
			// old - - 100
			//  new -x
			// real value  = 100 -x
			// echo ((($old*100)/$new+0.0001)-100)." ";
			// return -round((($old*100)+1)/($new+1)-102,2);
			return -round(
				100 - (($new * 100) / ($old)),
				2
			);
			// old ---- 100
			//  new ---- x
			// x = new * 100 / old
		}
	}
	return false;
}

function RetrieveError($errorid)
{
	global $marketid;
	switch ($errorid) {
		case 1:
			$e = 'No values recieved';
			break;
		case 2:
			$e = "You don't have enough money to buy that! You have: $" . htmlentities($_GET['v']);
			break;
		case 3:
			$e = "You don't have enoguh coins of this market to withdraw that quantity! You have: " . htmlentities($_GET['v']);
			break;
		case 4:
			$e = "The actual fee is higher than the quantity you were wanting to buy.";
			break;
		case 5:
			$e = "The actual fee is higher than the quantity you were wanting to sell.";
			break;
		case 7:
			$e = "You have already claimed this airdrop.";
			break;
		case 8:
			$e = "Your Acconut has been banned";
			break;
		case 9:
			$e = "The password or username do not match";
			break;
		case 10:
			$e = "You can't afford that quantity. Try something lower.";
			break;
		case 11:
			$e = "The number of tickets must not exceed 100";
			break;
		case 12:
			$e = "Recieved a non-numeric value.";
			break;
		case 13:
			$e = "That username already exists";
			break;
		case 14:
			$e = "The username or name is too long";
			break;
		case 15:
			$e = "You can't transfer money to you";
			break;
		case 16:
			$e = "A database error ocurred";
			break;
		case 17:
			$e = "That country is not in auction.";
			break;
		case 18:
			$e = "The country is not valid or has an actual owner";
			break;
		case 19:
			$e = "The country is not valid or is not in auction right now";
			break;
		case 20:
			$e = "The entered bet is smaller than the starting price or last bet. Please increase your bet to proceed.";
			break;
		case 21:
			$e = "You can't bid if your last bid was yours.";
			break;
		case 22:
			$e = "You can't bid in your own auction.";
			break;
		case 23:
			$e = "You can't cancel an auction that's not yours.";
			break;
		case 23:
			$e = "You can't end an auction that's is already ending";
			break;
		case 24:
			$e = "That auction has already ended.";
			break;
		case 25:
			$e = "You can't aford that! Remember that the fee is included in what you enter (you would be playing with coins-fee), coins you just needed: " . htmlentities($_GET['v']);
			break;
		case 26:
			$e = "The market doesn't exist";
			break;
		case 27:
			$e = "The coins entered can't be 0";
			break;
		case 999:
			$e = "Something went really bad. Please contact an administrator. " . htmlentities($_GET['v']);
			break;
		default:
			$e = 'Something went wrong';
			# code...
			break;
	}
	return "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  <strong>Error!</strong>  " . $e . "</div>";
}

function canAfford($quantity, $userid, $marketid)
{
	// market id is always 0 bc is USD withdrawed
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-balances` WHERE ownerid=:ownerid AND marketid=:marketid";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$selectStmt->execute(['ownerid' => $userid, 'marketid' => $marketid]);

	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			if (bcdiv(floatval($row['quantity']), 1, 8) >= bcdiv(floatval($quantity), 1, 8)) { // if money USD > SOMETHING then OK
				return [true, $row['quantity']];
			} else {
				return [false, $row['quantity'], floatval($quantity)];
			}
		}
	}
	return [false, 0];
}


function transfer($fromUserID, $toUserID, $quantity, $feeDollars)
{
	inject($toUserID, 0, $quantity);

	substract(0, $fromUserID, $quantity + $feeDollars);
	return;
}

function substract($marketid, $ownerid, $quantityInCoins)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-balances` WHERE ownerid=:ownerid AND marketid=:marketid LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$selectStmt->execute(['ownerid' => $ownerid, 'marketid' => $marketid]);

	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			$newQ = floatval($row['quantity'] - ($quantityInCoins));
			if ($newQ < 0) {
				// is negative. something went wrong.
				$error = $newQ;
				$newQ = 0;
			}
			$statement = "UPDATE `market-balances` SET quantity=:quantity WHERE ownerid=:ownerid AND marketid=:marketid";
			$preparedstmt = $pdo->prepare($statement);
			$input = ['quantity' => $newQ, 'ownerid' => $ownerid, 'marketid' => $marketid];
			$res = $preparedstmt->execute($input);

			if (isset($error)) {
				header("location:./../index.php?e&v=" . $error);
				die();
			}
			return $res;
		}
	}
}

function addition($userid, $marketid, $quantityInCoins)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-balances` (id, ownerid, marketid, quantity) VALUES (NULL, :ownerid, :marketid, :quantity)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['ownerid' => $userid, 'marketid' => $marketid, 'quantity' => $quantityInCoins];
	$insrtstmnt->execute($input);
}
function logInUser($username, $password)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-users` WHERE username=:username AND password=:password LIMIT 1 ";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['username' => $username, 'password' => hash('SHA256', $password)];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			if ($row['status'] == 1) {
				// not banned
				return [true, $row['id']];
			} else {
				// banned
				return [false, 1];
			}
		}
	} else {
		// not correct
		return [false, 0];
	}
	return 0;
}
function loginUserById($id)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-users` WHERE id=:id LIMIT 1 ";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $id];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			if ($row['status'] == 1) {
				// not banned
				return [true, $row['id']];
			} else {
				return [false, 1];
			}
		}
	} else {
		return [false, 0];
	}
	return 0;
}
function anyUser()
{
	global $pdo;
	$SQL_SELECT = "SELECT id FROM `market-users` LIMIT 1 ";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return true;
	} else {
		return false;
	}
}

function CheckClamp($current, $min, $max)
{
	if ($current < $min) {
		return false;
	}
	if ($current > $max) {
		return false;
	}
	return true;
}

function CreateUser($username, $name, $password, $status)
{
	global $pdo;
	$sqlInsert = "INSERT INTO `market-users` (id, name, username, password, status, color) VALUES (NULL, :name, :username, :password, :status, :color)";
	$selectStmt = $pdo->prepare($sqlInsert);
	$input = ['username' => $username, 'password' => hash('SHA256', $password), 'status' => $status, 'name' => $name, 'color' => dechex(random_int(0, 16777215))];
	$selectStmt->execute($input);
	$SQL_SELECT = "SELECT `id` FROM `market-users` WHERE username=:username LIMIT 1";
	$selectStmtSEL = $pdo->prepare($SQL_SELECT);
	$inputS = ['username' => $username];
	$selectStmtSEL->execute($inputS);
	if ($selectStmtSEL->rowCount() > 0) {
		foreach ($selectStmtSEL as $row) {
			return $row['id'];
		}
	}
	return false;
}



function encrypt($data)
{
	// import private key saved into credentials.php
	global $privkey;

	$encrypted = base64_encode($data);
	$encrypted .= "||";
	$encrypted .= hash("SHA256", $privkey . $data);
	return $encrypted;
}

function decrypt($data)
{
	global $privkey;

	$userid = base64_decode(explode("||", $data)[0]);
	if (hash("SHA256", $privkey . $userid) == explode("||", $data)[1]) {
		return $userid;
	}
	return false;
}


function exchange($fromID, $toID, $fee, $userid, $quantityInDollars)
{
	global $pdo;
	$fromval = getValue($fromID);
	$MarketValueInDollars = getValue($toID);



	$toBuyCoins = (floatval($quantityInDollars) - $fee) / $MarketValueInDollars;
	$toSellCoins = (floatval($quantityInDollars) - $fee) / $fromval;

	$r1 = inject($userid, $toID, bcdiv(floatval($toBuyCoins), 1, 8));

	$r2 = substract($fromID, $userid, bcdiv(floatval($toSellCoins), 1, 8));

	return $r1 && $r2;
}


function checkMatch($marketId)
{
	global $pdo;
	// buy
	$SQL_SELECT = "SELECT * FROM `market-offers` WHERE type=:type AND marketId=:marketId ORDER BY `pricePerUnit` DESC";
	$buyOffers = $pdo->prepare($SQL_SELECT);
	$input = ['type' => 0, 'marketId' => $marketId];
	$buyOffers->execute($input);

	// sell 
	$SQL_SELECT2 = "SELECT * FROM `market-offers` WHERE type=:type AND marketId=:marketId ORDER BY `pricePerUnit` ASC";
	$sellOffers = $pdo->prepare($SQL_SELECT2);
	$input = ['type' => 1, 'marketId' => $marketId];
	$sellOffers->execute($input);

	$buyOffers = $buyOffers->fetchAll();
	$sellOffers = $sellOffers->fetchAll();

	if (count($buyOffers) == 0 || count($sellOffers) == 0) {
		// no suficient offers to continue.
		return;
	}

	$sellPrices = array();
	$buyPrices = array();
	foreach ($sellOffers as $sellOffer) {
		array_push($sellPrices, $sellOffer['pricePerUnit']);
	}
	foreach ($buyOffers as $buyOffer) {
		array_push($buyPrices, $buyOffer['pricePerUnit']);
	}

	if (min($sellPrices) == max($buyPrices) || min($sellPrices) < max($buyPrices)) {
		// match found
		// get index to retrieve the owners
		$lowestSellIndex = array_search(min($sellPrices), $sellPrices);
		$highestBuyIndex = array_search(max($buyPrices), $buyPrices);

		$sellObject = $sellOffers[$lowestSellIndex];
		$buyObject = $buyOffers[$highestBuyIndex];

		$sellerId = $sellObject['ownerId'];
		$buyerId = $buyObject['ownerId'];

		if ($sellerId == $buyerId) {
			// same user can't buy and sell itself. that would establish the desired price.
			return;
		}

		// basic main data
		$pricePerUnit = $sellObject['pricePerUnit'];
		$quantityBought = $buyObject['quantity'];
		$dollarsSpent = $quantityBought * $pricePerUnit;

		// check quantities requested and selled
		if ($sellObject['quantity'] > $buyObject['quantity']) {
			// offering more than requested. 
			// substract selled the bought quantity to the sell quantity 
			// eg:  3@100  			4@100
			// 					--> 1@100 remaining

			$quantityRemaining = $sellObject['quantity'] - $buyObject['quantity'];

			//generate new balance (num of remaining quantities times PPU)
			$newUSD = $pricePerUnit * $quantityRemaining;
			// update sell offer
			updateOfferQuantity($sellObject['id'], $sellerId, $quantityRemaining, $newUSD);

			// remove buy offer
			removeOffer($buyObject['id'], $buyerId);
		} else if ($sellObject['quantity'] == $buyObject['quantity']) {
			// exact match in terms of quantity

			//remove both offers since they are the same quantity
			removeOffer($buyObject['id'], $buyerId);
			removeOffer($sellObject['id'], $sellerId);
		} else if ($sellObject['quantity'] < $buyObject['quantity']) {
			// selling less than wanted. 
			// delete sell offer and search for another match with same price,
			// also substract quantity of buy eg: 
			// 3@100 			1@100
			// 2@100 <---		search for more @100, else do nothing
			$quantityRemaining = $buyObject['quantity'] - $sellObject['quantity'];

			//generate new balance (num of remaining quantities times PPU)
			$newUSD = $pricePerUnit * $quantityRemaining;
			// update sell offer
			updateOfferQuantity($buyObject['id'], $buyerId, $quantityRemaining, $newUSD);

			// remove buy offer
			removeOffer($sellObject['id'], $sellerId);
		}


		// do transaction
		// seller wants dollars, so marketId is 0.
		inject($sellerId, 0, $dollarsSpent); // seller recieves the dollars
		// buyer recieves coins
		inject($buyerId, $marketId, $quantityBought);

		// log transaction
		logTransaction($buyerId, $sellerId, $marketId, $quantityBought, $dollarsSpent);

		// update price
		insertValue($marketId, $pricePerUnit);
	} else {
		// no match.
	}
}

function getOffers($type, $marketId)
{
	checkMatch($marketId);
	if ($type == "BUY") {
		$encType = 0;
		$filerPPU = "DESC";
	} else if ($type == "SELL") {
		$encType = 1;
		$filerPPU = "ASC";
	} else {
		throw new Error("Offer type unknown");
	}
	global $pdo;
	$SQL_SELECT = "SELECT id, ownerId, quantity, USD FROM `market-offers` WHERE type=:type AND marketId=:marketId ORDER BY `pricePerUnit` " . $filerPPU;
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['type' => $encType, 'marketId' => $marketId];
	$selectStmt->execute($input);

	return $selectStmt;
}

function removeOffer($offerId, $userId)
{
	global $pdo;

	$SQL_SELECT = "SELECT * FROM `market-offers` WHERE id=:id AND ownerId=:ownerId LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $offerId, 'ownerId' => $userId];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() == 0) {
		return NULL;
	}


	$SQL_DELETE = "DELETE FROM `market-offers` WHERE id=:id AND ownerId=:ownerId LIMIT 1";

	$deleteStmnt = $pdo->prepare($SQL_DELETE);

	$input = ['id' => $offerId, 'ownerId' => $userId];

	if ($deleteStmnt->execute($input)) {
		$data = $selectStmt->fetchAll()[0];
		if ($data['type'] == 0) {
			// if is buy, marketId will be 0 since the money will be returned, 
			return ['saveToUSD' => TRUE, 'quantity' => $data['USD'], 'marketId' => 0];
		}
		// otherwise, the returned coins will be in the market that they originally were before the sell offer.
		return ['saveToUSD' => FALSE, 'quantity' => $data['quantity'], 'marketId' => $data['marketId']];
	} else {
		return NULL;
	}
}

function updateOfferQuantity($offerId, $userId, $newQuantity, $USD)
{
	global $pdo;
	$statement = "UPDATE `market-offers` SET quantity=:quantity, USD=:USD WHERE id=:id AND ownerId=:ownerId";

	$preparedstmt = $pdo->prepare($statement);

	$input = ['quantity' => $newQuantity, 'USD' => $USD, 'id' => $offerId, 'ownerId' => $userId];

	return $preparedstmt->execute($input);
}


function logTransaction($buyerId, $sellerId, $marketId, $coinsBought, $dollarsSpent)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-transactions` (id, buyerId, sellerId, marketId, coins, dollars, timestamp) VALUES (NULL, :buyerId, :sellerId, :marketId, :coins, :dollars, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['buyerId' => $buyerId, 'sellerId' => $sellerId, 'marketId' => $marketId, 'coins' => $coinsBought, 'dollars' => $dollarsSpent, 'timestamp' => time()];
	return $insrtstmnt->execute($input);
}


function insertValue($markid, $value)
{
	global $pdo;

	if ($value < 0) {
		$value = 0;
	}

	$SQL_INSERT = "INSERT INTO `market-value` (marketid, value, timestamp) VALUES (:marketid, :value, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['marketid' => $markid, 'value' => $value, 'timestamp' => time()];
	$insrtstmnt->execute($input);
}

function insertPercentages($markid, $ph, $pd, $pw, $pm, $p2m, $p3m, $marketcap)
{
	global $pdo;

	$SQL_INSERT = "INSERT INTO `market-percentages` (id, marketid, ph, pd, pw, pm, p2m, p3m, marketcap) VALUES (NULL, :marketid, :ph, :pd, :pw, :pm, :p2m, :p3m, :marketcap)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['marketid' => $markid, 'ph' => $ph, 'pd' => $pd, 'pw' => $pw, 'pm' => $pm, 'p2m' => $p2m, 'p3m' => $p3m, 'marketcap' => $marketcap];
	$insrtstmnt->execute($input);
}

function getAllTransactions($marketId)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-transactions` WHERE  marketId=:marketId ORDER BY `timestamp` DESC";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['marketId' => $marketId];
	$selectStmt->execute($input);

	return $selectStmt;
}


// map section
function isCountryOwned($countryCode)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map` WHERE countryCode=:countryCode AND ownerId IS NOT NULL LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['countryCode' => $countryCode];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return ['bool' => True, 'data' => $selectStmt->fetchAll()[0]];
	}
	return ['bool' => False];
}

function getCountryName($countryCode)
{
	global $countryAcronyms;
	$names = ["Afghanistan", "Angola", "Albania", "United Arab Emirates", "Argentina", "Armenia", "Australia", "Austria", "Azerbaijan", "Burundi", "Belgium", "Benin", "Burkina Faso", "Bangladesh", "Bulgaria", "Bahrain", "Bosnia and Herzegovina", "Belarus", "Belize", "Bolivia", "Brazil", "Brunei Darussalam", "Bhutan", "Botswana", "Central African Republic", "Canada", "Switzerland", "Chile", "China", "Côte d'Ivoire", "Cameroon", "Democratic Republic of the Congo", "Republic of Congo", "Colombia", "Costa Rica", "Cuba", "Czech Republic", "Germany", "Djibouti", "Denmark", "Dominican Republic", "Algeria", "Ecuador", "Egypt", "Eritrea", "Estonia", "Ethiopia", "Finland", "Fiji", "Gabon", "United Kingdom", "Georgia", "Ghana", "Guinea", "The Gambia", "Guinea-Bissau", "Equatorial Guinea", "Greece", "Greenland", "Guatemala", "Guyana", "Honduras", "Croatia", "Haiti", "Hungary", "Indonesia", "India", "Ireland", "Iran", "Iraq", "Iceland", "Israel", "Italy", "Jamaica", "Jordan", "Japan", "Kazakhstan", "Kenya", "Kyrgyzstan", "Cambodia", "Republic of Korea", "Kosovo", "Kuwait", "Lao PDR", "Lebanon", "Liberia", "Libya", "Sri Lanka", "Lesotho", "Lithuania", "Luxembourg", "Latvia", "Morocco", "Moldova", "Madagascar", "Mexico", "Macedonia", "Mali", "Myanmar", "Montenegro", "Mongolia", "Mozambique", "Mauritania", "Malawi", "Malaysia", "Namibia", "Niger", "Nigeria", "Nicaragua", "Netherlands", "Norway", "Nepal", "New Zealand", "Oman", "Pakistan", "Panama", "Peru", "Philippines", "Papua New Guinea", "Poland", "Dem. Rep. Korea", "Portugal", "Paraguay", "Palestine", "Qatar", "Romania", "Russia", "Rwanda", "Western Sahara", "Saudi Arabia", "Sudan", "South Sudan", "Senegal", "Sierra Leone", "El Salvador", "Serbia", "Suriname", "Slovakia", "Slovenia", "Sweden", "Swaziland", "Syria", "Chad", "Togo", "Thailand", "Tajikistan", "Turkmenistan", "Timor-Leste", "Tunisia", "Turkey", "Taiwan", "Tanzania", "Uganda", "Ukraine", "Uruguay", "United States", "Uzbekistan", "Venezuela", "Vietnam", "Vanuatu", "Yemen", "South Africa", "Zambia", "Zimbabwe", "Somalia", "France", "France", "Spain", "Aruba", "Anguilla", "Andorra", "Antigua and Barbuda", "Bahamas", "Bermuda", "Barbados", "Comoros", "Cape Verde", "Cayman Islands", "Dominica", "Falkland Islands", "Faeroe Islands", "Grenada", "Hong Kong", "Saint Kitts and Nevis", "Saint Lucia", "Liechtenstein", "Saint Martin (French)", "Maldives", "Malta", "Montserrat", "Mauritius", "New Caledonia", "Nauru", "Pitcairn Islands", "Puerto Rico", "French Polynesia", "Singapore", "Solomon Islands", "São Tomé and Principe", "Saint Martin (Dutch)", "Seychelles", "Turks and Caicos Islands", "Tonga", "Trinidad and Tobago", "Saint Vincent and the Grenadines", "British Virgin Islands", "United States Virgin Islands", "Cyprus", "Reunion (France)", "Mayotte (France)", "Martinique (France)", "Guadeloupe (France)", "Curaco (Netherlands)", "Canary Islands (Spain)"];
	return $names[array_search($countryCode, $countryAcronyms)];
}

/** 
 * Return all countries that a specific user has 
 * @param int userid
 * @return array countries owned
 */
function getOwnedCountries($userId)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map` WHERE ownerId=:ownerId";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerId' => $userId];
	$selectStmt->execute($input);
	return $selectStmt->fetchAll();
}
/** 
 * Return if a user owns a specific country 
 * @param int userid
 * @param string countryCode
 * @return bool result if it owns it
 */
function doesUserOwnThisCountry($userId, $countryCode)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map` WHERE ownerId=:ownerId AND countryCode=:countryCode LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerId' => $userId, 'countryCode' => $countryCode];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return true;
	}
	return false;
}


/** 
 * Sets a new country owner, either via insert or update
 */
function setCountryOwner($countryCode, $userId)
{
	global $pdo;
	// check if row exists
	$SQL_SELECT = "SELECT * FROM `market-map` WHERE countryCode=:countryCode LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['countryCode' => $countryCode];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		//update it
		$statement = "UPDATE `market-map` SET ownerId=:ownerId WHERE countryCode=:countryCode LIMIT 1";
		$preparedstmt = $pdo->prepare($statement);
		$input = ['ownerId' => $userId, 'countryCode' => $countryCode];
		return $preparedstmt->execute($input);
	} else {
		// insert it
		$SQL_INSERT = "INSERT INTO `market-map` (id, countryCode, ownerId, timestamp) VALUES (NULL, :contryCode, :ownerId, :timestamp)";
		$insrtstmnt = $pdo->prepare($SQL_INSERT);
		$input = ['timestamp' => time(), 'contryCode' => $countryCode, 'ownerId' => $userId];
		return $insrtstmnt->execute($input);
	}
}

/** 
 * Get the colorName of a certain user
 */
function getUserColor($userId)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-users` WHERE id=:id LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $userId];
	$selectStmt->execute($input);
	return $selectStmt->fetchAll()[0]['color'];
}

// auction section 
function getAllOwnedCountries()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map` WHERE ownerId IS NOT NULL";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	return $selectStmt->fetchAll();
}

/** 
 * Returns the auctions countries
 * @return array 
 * @author EncryptEx
 */
function getCountriesAuction()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map-auctions`";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	return $selectStmt->fetchAll();
}

/** 
 * Function that returns the lastest timestamp in which the countryOwners where payed
 * @return array|null DB object or null if none
 * @author EncryptEx
 */
function getLastCountryPayAll()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map-log` WHERE `action`=:action ORDER BY `timestamp` DESC LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	// action = 0 stands for passive income actions
	$input = ['action' => 0];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt->fetchAll()[0];
	} else {
		return NULL;
	}
}

/** 
 * Function that saves into db the timestamp given into the passiveIncome process
 * @return bool result of DB save action
 * @author EncryptEx
 */
function savePayRecord(int $recipientId, string $countryCode, float $quantity, int $marketId, int $timestamp)
{
	global $pdo;
	$SQL_SELECT = "INSERT INTO `market-map-log`(id, action, userAffectedId, countryCode, quantity, marketId, timestamp) VALUES (NULL, :action, :userAffectedId, :countryCode, :quantity, :marketId, :timestamp)";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['action' => 0, 'userAffectedId' => $recipientId, 'countryCode' => $countryCode, 'quantity' => $quantity, 'marketId' => $marketId, 'timestamp' => $timestamp];
	return $selectStmt->execute($input);
}

function doesAuctionExist($countryCode)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map-auctions` WHERE countryCode=:countryCode LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['countryCode' => $countryCode];
	$selectStmt->execute($input);
	return $selectStmt->rowCount();
}

function removeAuction($countryCode, $entireVanish = false)
{
	global $pdo;
	$SQL_DELETE = "DELETE FROM `market-map-auctions` WHERE countryCode=:countryCode LIMIT 1";

	$deleteStmnt = $pdo->prepare($SQL_DELETE);

	$input = ['countryCode' => $countryCode];

	if (!$deleteStmnt->execute($input)) {
		return false;
	}
	if (!$entireVanish) {
		return true;
	} // skip bet deletion. used in cancel action.

	$SQL_DELETE = "DELETE FROM `market-map-bets` WHERE countryCode=:countryCode";

	$deleteStmnt2 = $pdo->prepare($SQL_DELETE);

	$input = ['countryCode' => $countryCode];

	if (!$deleteStmnt2->execute($input)) {
		return false;
	}

	return true;
}

function doesCountryExist($countryCode)
{
	global $countryAcronyms;
	return array_search($countryCode, $countryAcronyms);
}


function getAuctionInfo($countryCode)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map-auctions` WHERE countryCode=:countryCode LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['countryCode' => $countryCode];
	$selectStmt->execute($input);
	return $selectStmt->fetchAll()[0];
}

function checkAuctionExpiration($countryCode)
{
	$data = getAuctionInfo($countryCode);
	$lastBet = getLastBet($countryCode);
	if ($data['endAuction'] != NULL && $data['endAuction'] <= time()) {
		$errors = [];
		$errors[0] = inject($data['ownerId'], 0, $lastBet['bet']);
		$errors[1] = setCountryOwner($countryCode, $lastBet['ownerId']);
		$errors[2] = removeAuction($countryCode, TRUE);

		//check for any error
		$res = array_unique($errors);
		if (count($res) !== 1) {
			header('location:map.php?e=16');
		}
	}
}

function auctionSetEndTimestmp($countryCode, $userId, $endTtimestamp)
{
	global $pdo;
	$statement = "UPDATE `market-map-auctions` SET endAuction=:endAuction WHERE countryCode=:countryCode AND ownerId=:ownerId LIMIT 1";
	$preparedstmt = $pdo->prepare($statement);
	$input = ['countryCode' => $countryCode, 'ownerId' => $userId, 'endAuction' => $endTtimestamp];
	return $preparedstmt->execute($input);
}

function getLastBet($countryCode)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map-bets` WHERE countryCode=:countryCode ORDER BY timestamp DESC LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['countryCode' => $countryCode];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt->fetchAll()[0];
	}
	return NULL;
}

function getBets($countryCode)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-map-bets` WHERE countryCode=:countryCode ORDER BY timestamp DESC";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['countryCode' => $countryCode];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt->fetchAll();
	}
	return NULL;
}
function createBet($countryCode, $userId, $bet)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-map-bets` (id, ownerId, bet, countryCode, timestamp) VALUES (NULL, :ownerId, :bet, :countryCode, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['ownerId' => $userId, 'bet' => $bet, 'countryCode' => $countryCode, 'timestamp' => time()];
	return $insrtstmnt->execute($input);
}
function createCountryAuction($countryCode, $userId, $startingPrice)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-map-auctions` (id, ownerId, countryCode, startingPrice, timestamp, endAuction) VALUES (NULL, :ownerId, :countryCode, :startingPrice, :timestamp, NULL)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['ownerId' => $userId, 'countryCode' => $countryCode, 'startingPrice' => $startingPrice, 'timestamp' => time()];
	return $insrtstmnt->execute($input);
}

/** 
 * Returns a human redable format date (10 min ago)
 * @param int seconds elapsed
 * @return string
 */
function time_since($since)
{
	$chunks = array(
		array(31536000, 'year'),
		array(2592000, 'month'),
		array(604800, 'week'),
		array(86400, 'day'),
		array(3600, 'hour'),
		array(60, 'minute'),
		array(1, 'second')
	);

	for ($i = 0, $j = count($chunks); $i < $j; $i++) {
		$seconds = $chunks[$i][0];
		$name = $chunks[$i][1];
		if (($count = floor($since / $seconds)) != 0) {
			break;
		}
	}

	$print = ($count == 1) ? '1 ' . $name : "$count {$name}s";
	return $print;
}

/**
 * Increases or decreases the brightness of a color by a percentage of the current brightness.
 *
 * @param   string  $hexCode        Supported formats: `#FFF`, `#FFFFFF`, `FFF`, `FFFFFF`
 * @param   float   $adjustPercent  A number between -1 and 1. E.g. 0.3 = 30% lighter; -0.4 = 40% darker.
 *
 * @return  string
 *
 * @author  maliayas - stackoverflow question
 */
function adjustBrightness($hexCode, $adjustPercent)
{
	$hexCode = ltrim($hexCode, '#');

	if (strlen($hexCode) == 3) {
		$hexCode = $hexCode[0] . $hexCode[0] . $hexCode[1] . $hexCode[1] . $hexCode[2] . $hexCode[2];
	}

	$hexCode = array_map('hexdec', str_split($hexCode, 2));

	foreach ($hexCode as &$color) {
		$adjustableLimit = $adjustPercent < 0 ? $color : 255 - $color;
		$adjustAmount = ceil($adjustableLimit * $adjustPercent);

		$color = str_pad(dechex($color + $adjustAmount), 2, '0', STR_PAD_LEFT);
	}

	return '#' . implode($hexCode);
}

/**
 * Bug-fix betweeen Php versions
 */
if (!function_exists('str_starts_with')) {
	function str_starts_with($haystack, $needle, $case = true)
	{
		if ($case) {
			return strpos($haystack, $needle, 0) === 0;
		}
		return stripos($haystack, $needle, 0) === 0;
	}
}


/** 
 * Get the base price of a country based on its PIB 
 * @author EncryptEx
 * @return int price
 */
function getCountryBasePrice(string $countryCode)
{
	global $countryBasePrice;
	$prices = ["US" => 20893746, "CN" => 14722801, "JP" => 5057759, "DE" => 3846414, "GB" => 2764198, "IN" => 2664749, "FR" => 2630318, "IT" => 1888709, "CA" => 1644037, "KR" => 1637896, "RU" => 1483498, "BR" => 1444733, "AU" => 1423473, "ES" => 1281485, "MX" => 1073439, "ID" => 1058424, "IR" => 939316, "NL" => 913865, "CH" => 752248, "TR" => 720098, "SA" => 700118, "TW" => 669324, "PL" => 596618, "SE" => 541064, "BE" => 521861, "TH" => 501795, "AT" => 433258, "NG" => 429899, "IE" => 425889, "IL" => 407101, "AR" => 383067, "EG" => 369309, "NO" => 362522, "PH" => 361489, "AE" => 358869, "DK" => 356085, "HK" => 349445, "SG" => 339988, "MY" => 336664, "BD" => 329484, "ZA" => 302141, "CO" => 271347, "VN" => 271158, "FI" => 269751, "PK" => 257829, "CL" => 25294, "RO" => 248716, "CZ" => 245349, "PT" => 228539, "NZ" => 212044, "PE" => 203196, "GR" => 188835, "KZ" => 171082, "IQ" => 166757, "HU" => 155808, "UA" => 155582, "DZ" => 147689, "QA" => 146401, "MA" => 114724, "CU" => 107352, "VE" => 106359, "KW" => 105949, "SK" => 105173, "PR" => 103138, "KE" => 101014, "EC" => 98808, "ET" => 96611, "LK" => 80677, "DO" => 78845, "GT" => 77605, "LU" => 73353, "MM" => 70284, "BG" => 69888, "GH" => 68532, "TZ" => 6474, "LB" => 63546, "OM" => 63368, "AO" => 62307, "SD" => 62057, "CR" => 61521, "CI" => 61143, "BY" => 60259, "UZ" => 57707, "HR" => 57204, "LT" => 56547, "UY" => 53629, "SI" => 5359, "RS" => 53335, "PA" => 52938, "CD" => 45308, "JO" => 43697, "TM" => 42845, "AZ" => 42607, "CM" => 39881, "TN" => 39218, "UG" => 38702, "BO" => 36573, "PY" => 35304, "BH" => 33904, "LV" => 33707, "NP" => 33079, "EE" => 3065, "LY" => 29153, "YE" => 27958, "KH" => 25291, "SV" => 24639, "CY" => 24612, "SN" => 24412, "MO" => 24333, "HN" => 23828, "PG" => 23619, "ZW" => 21787, "IS" => 21718, "TT" => 21393, "BA" => 19801, "AF" => 19793, "LA" => 19082, "ZM" => 18111, "BF" => 17369, "ML" => 17332, "SS" => 15903, "GE" => 15892, "KP" => 15847, "BW" => 15782, "SY" => 15572, "PS" => 15561, "HT" => 15505, "GN" => 1549, "BJ" => 15205, "GA" => 15111, "MT" => 14911, "AL" => 1491, "MZ" => 14029, "JM" => 13812, "NE" => 13741, "MN" => 13137];

	if (!isset($prices[$countryCode])) {
		return $countryBasePrice;
	}
	return $prices[$countryCode];


}

/** 
 * Main function to determine miliseconds where app runs, but in the end is the multiplier
 * @return int random miliseconds
 */
function getdinoMaxMilis()
{
	$randomInt = random_int(0, 100);
	if ($randomInt > 95) { // 5%
		$milisMax = random_int(3000, 20000);
	} else if ($randomInt > 85) { // 10%
		$milisMax = random_int(2000, 10000);
	} else if ($randomInt > 60) { // 25%
		$milisMax = random_int(1000, 8000);
	} else { // 60%
		$milisMax = random_int(0000, 8000);
	}
	return $milisMax;
}

/** 
 * Retrieve jackpot value, increased by players at the dinoGame
 * @return float|int
 */
function getJackPotValue()
{
	global $pdo;
	$SQL_SELECT = "SELECT SUM(quantity) FROM `market-lottery-value` WHERE lastClaimed IS NULL";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt->fetchAll()[0]['SUM(quantity)'];
	}
	return false;
}

/** 
 * Function to add funds the jackpot.
 * @return bool true if DB went great.
 */
function jackpotDeposit($jackpotDeposit, $userId)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-lottery-value` (id, ownerId, quantity, lastClaimed, timestamp) VALUES (NULL, :ownerId, :quantity, NULL, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['ownerId' => $userId, 'quantity' => $jackpotDeposit, 'timestamp' => time()];
	return $insrtstmnt->execute($input);
}

/** 
 * Returns the value of the price of a ticket for the jackpot
 * @return float|int
 */
function getLotteryTicketPrice()
{
	// get value of last jackpot win
	// if non-last jackpot win (first jackpot), set value to 20
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-lottery-prizes` ORDER BY `timestamp` DESC LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		$prize = $selectStmt->fetchAll()[0]['prize'];
		if ($prize != 0) {
			return round($prize / 1000000, 4);
		}

	}
	return 20;
}

/** 
 * Function to add lottery tickets to a user
 * @return bool true if DB went great.
 */
function buyLotteryTickets($userId, $quantity)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-lottery-tickets` (id, ownerId, quantity, timestamp) VALUES (NULL, :ownerId, :quantity, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['ownerId' => $userId, 'quantity' => $quantity, 'timestamp' => time()];
	return $insrtstmnt->execute($input);
}

/** 
 * Retrieve lottery ticket count of a player
 * @return float|int
 */
function getLotteryTicketCount($userid)
{
	global $pdo;
	$SQL_SELECT = "SELECT SUM(quantity) FROM `market-lottery-tickets` WHERE ownerId=:ownerId";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['ownerId' => $userid];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		return $selectStmt->fetchAll()[0]['SUM(quantity)'];
	}
	return false;
}

/** 
 * Retrieve all data of lastest jackpot prize won
 */
function getLastJackpotWinner()
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-lottery-prizes` ORDER BY `timestamp` DESC LIMIT 1 ";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = [];
	$selectStmt->execute($input);
	if ($selectStmt->rowCount() > 0) {
		// if installation was ok, it should ALWAYS be at least 1 entry.
		$data = $selectStmt->fetchAll()[0];
		if ($data['userid'] == -1) {
			return ['result' => false, 'data' => $data];
		}

		return ['result' => true, 'data' => $data];
	}
	header("location:/index.php?e=999&v=FailToGetLasJackpotWinner:" . ($selectStmt));
	die();
}

/** 
 * Creates the genesis of lottery, ran once only on installation.
 * @return bool true if insert went great.
 */
function addGenesisJackpotDeadline()
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-lottery-prizes` (id, userid, prize, timestamp) VALUES (NULL, :userid, :prize, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['userid' => -1, 'prize' => 0, 'timestamp' => time()];
	return $insrtstmnt->execute($input);
}


/** 
 * Add to db a winner of a lottery
 * @return bool true if went great the insert to db.
 */
function addLotteryWinner($userId, $prize, $timestamp)
{
	global $pdo;
	$SQL_INSERT = "INSERT INTO `market-lottery-prizes` (id, userid, prize, timestamp) VALUES (NULL, :userid, :prize, :timestamp)";
	$insrtstmnt = $pdo->prepare($SQL_INSERT);
	$input = ['userid' => $userId, 'prize' => $prize, 'timestamp' => $timestamp];
	return $insrtstmnt->execute($input);
}

/** 
 * Remove all tickets from the lottery
 * @return bool true if the action went great when working with the DB.
 */
function wipeTicketOwnership($timestamp)
{
	global $pdo;
	$SQL_DELETE = "DELETE FROM `market-lottery-tickets` WHERE `timestamp` < :timestamp";
	$deleteStmnt = $pdo->prepare($SQL_DELETE);
	$input = ['timestamp' => $timestamp];
	return $deleteStmnt->execute($input);
}

/**
 * Updates all jackpot quantities in order to set them to 0.
 * @return bool true if the action went great when working with the DB.
 */
function clearJackpot($timestamp)
{
	global $pdo;
	$statement = "UPDATE `market-lottery-value` SET lastClaimed=:lastClaimed";
	$preparedstmt = $pdo->prepare($statement);
	$input = ['lastClaimed' => $timestamp];
	return $preparedstmt->execute($input);
}


/**
 * Returns a string with the objective risk level.
 * @return string The risk level of the market.
 */
function getRisk(int $marketId)
{
	global $pdo;
	$SQL_SELECT = "SELECT * FROM `market-list` WHERE id=:id LIMIT 1";
	$selectStmt = $pdo->prepare($SQL_SELECT);
	$input = ['id' => $marketId];
	$selectStmt->execute($input);
	if ($marketId == 0) {
		return "safe";
	}
	if ($selectStmt->rowCount() > 0) {
		foreach ($selectStmt as $row) {
			$fluct = floatval($row['fluctuationValue']);
			$real = boolval($row['isReal']);

		}
	} else {
		return "unknown.";
	}

	
	if ($real) {
		return "in real time.";
	}

	if ($fluct < 0.15) {
		return "begginer-friendly (<15%)";
	} else if ($fluct <= 0.2) {
		return "moderate";
	} else if ($fluct <= 0.5) {
		return "risky";
	} else if ($fluct <= 0.9) {
		return "EXTREME";
	} else {
		return "<span style='text-decoration:line-through;'>CLASSIFIED</span>";
	}
}