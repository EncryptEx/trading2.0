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


$SQL_SELECT = "SELECT * FROM `market-list`";
$selectStmt = $pdo->prepare($SQL_SELECT);
$input =   [];
$selectStmt->execute($input);
$wantsReal = FALSE;
if (isset($_GET['realMode'])) {
	$wantsReal = TRUE;
}
$marketsID = [];
if ($selectStmt->rowCount() > 0) {
	foreach ($selectStmt as $row) {
		array_push($marketsID, $row['id']);
		if ($row['isReal'] == 0) {
			$val = getValue($row['id']);
			// generate random value
			$min = $val * (1-floatval($row['fluctuationValue']));
			$max = $val * (1+floatval($row['fluctuationValue']));

			
			$newValue = random_int($min,$max);

			insertValue($row['id'],$newValue);

			echo "Generated value for ".$row['name']." with id ".$row['id']."new value: ".$newValue."<br>";

		} else if ($row['isReal'] == 1 && $wantsReal) {
			// https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest
			$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
			$parameters = [
				'id' => $row['url'],
				'convert' => 'USD'
			];

			$headers = [
				'Accepts: application/json',
				'X-CMC_PRO_API_KEY: '.$coinMarketCapKey,
			];
			$qs = http_build_query($parameters); // query string encode the parameters
			$request = "{$url}?{$qs}"; // create the request URL


			$curl = curl_init(); // Get cURL resource
			// Set cURL options
			curl_setopt_array($curl, array(
				CURLOPT_URL => $request,            // set the request URL
				CURLOPT_HTTPHEADER => $headers,     // set the headers 
				CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
			));

			$response = curl_exec($curl); // Send the request, save the response
			$result = json_decode($response, true); // print json decoded response

			if ($result['status']['error_code'] == 0) {
				$price = $result['data'][$row['url']]['quote']['USD']['price'];
				$ph = $result['data'][$row['url']]['quote']['USD']['percent_change_1h'];
				$pd = $result['data'][$row['url']]['quote']['USD']['percent_change_24h'];
				$pw = $result['data'][$row['url']]['quote']['USD']['percent_change_7d'];
				$pm = $result['data'][$row['url']]['quote']['USD']['percent_change_30d'];
				$p2m = $result['data'][$row['url']]['quote']['USD']['percent_change_60d'];
				$p3m = $result['data'][$row['url']]['quote']['USD']['percent_change_90d'];
				$marketcap = $result['data'][$row['url']]['quote']['USD']['market_cap'];
				insertValue($row['id'], $price);
				insertPercentages($row['id'], $ph, $pd, $pw, $pm, $p2m, $p3m, $marketcap);
			} else {
				header("HTTP/1.1 500 Internal Server Error");
				var_dump($response);
			}
			curl_close($curl); // Close request
		}
	}
}
