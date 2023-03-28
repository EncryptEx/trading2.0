<?php 
require './../utils.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


header('Content-type: application/json; charset=utf-8');
// example.com/api/market/list
if ((isset($uri[2]) && $uri[2] == 'market') && isset($uri[3]) && $uri[3] == 'list') {
    
    $markets = [];
    $preMarkets = getMarkets()->fetchAll();
    foreach ($preMarkets as $market) {

        array_push($markets, ['name'=>$market['name'], "icon" => $market['logo']]);
    }
    echo json_encode($markets);
    exit();
}
header("HTTP/1.1 404 Not Found");
echo json_encode(['error'=>TRUE, 'message'=>'Endpoint not found']);
exit();