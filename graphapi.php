<?php
require 'utils.php';

if (!isset($_GET['marketid'])) {
    die("No market id recieved.");
}
$markid = base64_decode($_GET['marketid']);
$SQL_SELECT = "SELECT * FROM `market-value` WHERE marketid=:marketid ORDER BY `id` DESC LIMIT 15";
$selectStmt = $pdo->prepare($SQL_SELECT);
$input =   ['marketid' => $markid];
$selectStmt->execute($input);
$end = [];
$tmps = [];
if ($selectStmt->rowCount() > 0) {
    foreach ($selectStmt as $row) {
        array_push($end, $row['value']);
        array_push($tmps, $row['timestamp']);
    }
}
$end = array_reverse($end);
$tmps = array_reverse($tmps);
?>
<?php
$isf = TRUE;
foreach ($end as $value) {
    if ($isf) {
        $isf = FALSE;
        echo $value;
        continue;
    }
    echo "," . $value;
}
echo "||" . getPercentage($markid) . "||";
foreach ($tmps as $date) {
    echo $date . ",";
}
?>