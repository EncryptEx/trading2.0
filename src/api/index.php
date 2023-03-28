<?php 
require './../utils.php';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );


header('Content-type: application/json; charset=utf-8');
// /api/market/all
if ((isset($uri[2]) && $uri[2] == 'market') && isset($uri[3]) && $uri[3] == 'all') {
    
    $markets = [];
    $preMarkets = getMarkets()->fetchAll();
    foreach ($preMarkets as $market) {
        
        array_push($markets, ['id'=>$market['id'], 'name'=>$market['name'], "icon" => $market['logo']]);
    }
    echo json_encode($markets);
    exit();
}

// /api/market/{id}
if ((isset($uri[2]) && $uri[2] == 'market') && isset($uri[3]) && $uri[3] != 'all') {
    $requestedID = $uri[3];
    $preMarkets = getMarkets()->fetchAll();
    foreach ($preMarkets as $market) {
        if($market['id'] == $requestedID){
            echo json_encode(['id'=>$market['id'], 'name'=>$market['name'], "icon" => $market['logo']]);
            exit();
        }
    }
    echo json_encode(['error'=>TRUE, 'message'=>'Market not found']);
    exit();

}
header("HTTP/1.1 404 Not Found");
echo json_encode(['error'=>TRUE, 'message'=>'Endpoint not found']);
exit();