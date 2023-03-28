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
if ((isset($uri[2]) && $uri[2] == 'market') && isset($uri[3]) && $uri[3] != 'all' && is_numeric($uri[3])) {
    $requestedID = $uri[3];
    $preMarkets = getMarkets()->fetchAll();
    foreach ($preMarkets as $market) {
        if($market['id'] == $requestedID){
            echo json_encode(['id'=>$market['id'], 'name'=>$market['name'], "icon" => $market['logo']]);
            exit();
        }
    }
    echo json_encode(['error'=>TRUE, 'message'=>'Market not found']);
    header("HTTP/1.1 404 Not Found");
    exit();

}

// /api/stats/airdop
if ((isset($uri[2]) && $uri[2] == 'stats') && isset($uri[3]) && $uri[3] == 'airdrop') {
    $topAirdrop = getTopAirdropClaim();
    if($topAirdrop[0]){
        echo json_encode(['topAirdrop'=>$topAirdrop[1]]);
    } else {
        echo json_encode(['error'=>TRUE, 'message'=>'Market no airdrops claimed so far']);
        header("HTTP/1.1 404 Not Found");
    }
    exit();

}

// /api/user/all
if ((isset($uri[2]) && $uri[2] == 'user') && isset($uri[3]) && $uri[3] == 'all') {
    $final = [];
    $users = getUserIDs()->fetchAll();
    foreach ($users as $user) {
        array_push($final, ['username'=> $user['username'], 'name'=> $user['name'], 'balance'=>getBalance($user['id'])]);
    }
    header("HTTP/1.1 404 Not Found");
    echo json_encode($final);
    exit();

}
// /api/user/{username}
if ((isset($uri[2]) && $uri[2] == 'user') && isset($uri[3]) && $uri[3] != 'all') {
    $usernameToCheck = $uri[3];
    $id = getIdFromUserName($usernameToCheck);
    if($id != false && is_numeric($id)){
        echo json_encode(
            [
                'username'=> htmlentities($usernameToCheck),
                'name'=> getUserName($id),
                "balance" => getBalance($id),
            ]
            );
    } else {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(['error'=>TRUE, 'message'=>'User not found']);
    }
    exit();

}
header("HTTP/1.1 404 Not Found");
echo json_encode(['error'=>TRUE, 'message'=>'Endpoint not found']);
exit();