<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

if (!isset($_GET['marketid'])) {
    die("No market id recieved.");
}
$marketid = base64_decode($_GET['marketid']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradin' Zone</title>
    <?php require './../meta.php';?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <style>
        .name {
            margin-left: 10px;
        }

        a.links,
        a.link:hover {
            text-decoration: none;
            color: #000000 !important;
        }
    </style>
</head>
<?php require './../ui/navbar.php'; ?>
<div class="container" style="margin-top:30px;min-height:50em;">
    <?php
    if (isset($_GET['e'])) {
        echo RetrieveError($_GET['e']);
    }

    if (!isset($_GET['marketid'])) {
        die("No market id recieved.");
    }
    $markid = base64_decode($_GET['marketid']);
    $SQL_SELECT = "SELECT * FROM `market-value` WHERE marketid=:marketid ORDER BY `id` DESC";
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
    $v = getValue($marketid);
    $oldv = $end[0];
    ?>
    <div class="earnings">
        <a class="btn" style="border: 1px solid grey;" href="./market.php?marketid=<?php echo htmlentities($_GET['marketid']); ?>">Return Back</a>
        <h1><?php echo getName($marketid) ?>'s Historical Data <span id="valuee" class="badge badge-primary" style="margin-left:10px;">All: <?php echo -round(
                                                                                                                                                100 - (($v * 100) / ($oldv)),
                                                                                                                                                2
                                                                                                                                            ); ?>%</span></h1>
        <canvas id="chart_div4" class="table-responsive" style="width:100%;"></canvas>
    </div>
    <div class="markethistoric"><br>

        <script>
            var yValues = [<?php $isf = TRUE;
                            foreach ($end as $value) {
                                if ($isf) {
                                    $isf = FALSE;
                                    echo $value;
                                    continue;
                                }
                                echo "," . $value;
                            } ?>];

            var labels = [<?php
                            $isf = TRUE;
                            foreach ($tmps as $date) {
                                if ($isf) {
                                    $isf = FALSE;
                                    echo "'" . date("d-m-Y H:i", $date) . "',";
                                    continue;
                                }
                                echo "'" . date("d-m-Y H:i", $date) . "',";
                            }
                            ?>];
        </script>
        <script type="module">
    



            window.onload = function() {
                var ctx = document.getElementById('chart_div4').getContext('2d', {
                    alpha: false
                });
                window.myScatter = new Chart(ctx, {
                    type: "line",
                    data: {
                        labels: labels,
                        datasets: [{
                            fill: false,
                            lineTension: 0,
                            backgroundColor: "rgba(60,145,230,1.0)",
                            borderColor: "rgba(60,145,230,0.1)",
                            data: yValues
                        }]
                    },
                    plugins: [{
                        beforeDraw: function(chart, options) {
                            ctx.fillStyle = "white";
                            ctx.fillRect(0, 0, document.getElementById('chart_div4').offsetWidth, document.getElementById('chart_div4').offsetHeight);
                        }
                    }],
                    options: {
                        responsive: true,
                        intersect: true,
                        legend: {
                            display: false
                        },
                        tooltips: {
                            callbacks: {
                                title: () => {},
                                label: (tooltipItem, data) => {
                                    return `$${tooltipItem.yLabel} at ${tooltipItem.xLabel}}`;
                                }
                            }
                        },


                    }
                });
            };

        </script>
    </div>
</div>
</body>
<?php require './../ui/footer.php'; ?>

</html>