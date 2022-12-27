<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tradin' Zone</title>
    <?php require './../meta.php'; ?>
    <style>
        #prize {
            font-size: 55px;
        }

        @media (min-width:600px) {
            #prize {
                font-size: 100px !important;
            }
        }

        /* portrait tablets, portrait iPad, e-readers (Nook/Kindle), landscape 800x480 phones (Android) */

        @media (min-width:801px) {

            /* tablet, landscape iPad, lo-res laptops ands desktops */
            #prize {
                font-size: 140px !important;
            }
        }

        #prize {
            /* font-size: 200px; */
            letter-spacing: 5px;
            /* font-size: 900%; */
            font-weight: bold;
            background-image: linear-gradient(to right,
                    #462523 0,
                    #cb9b51 22%,
                    #f6e27a 45%,
                    #f6f2c0 50%,
                    #f6e27a 55%,
                    #cb9b51 78%,
                    #462523 100%);
            color: transparent;
            -webkit-background-clip: text;
        }
    </style>
</head>
<?php require './../ui/navbar.php'; ?>
<div class="container" style="margin-top:30px;min-height:50em;">


    <h1>Lottery: Win the jackpot! </h1>
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (isset($_GET['e'])) {
                echo RetrieveError($_GET['e']);
            }
            ?>
            <?php if (isset($_GET['s']) && isset($_GET['v'])) {
                if ($_GET['s'] == 1) {
                    echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Congrats!</strong> You have successfully bought " . htmlentities($_GET['v']) . " ticket/s </div>";
                }
            }
            ?>
        </div>
        <div class="col-12">
            <div class="d-block">
                <h1 id="prize">
                    $<?php echo number_format(getJackPotValue()); ?>
                </h1>
            </div>
        </div>

        <div class="col-12">
            <form action="lotteryBackend.php" method="POST" id="fm">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <b>Cost of a single ticket: </b>
                    <h5 id="cost">$0</h5>
                </div>
                <?php 
                $countTickets = getLotteryTicketCount($userid);
                if($countTickets != false):?>
                <div class="col-12 col-sm-6">
                    <b>You aleady own:</b>
                    <h5><?php echo number_format($countTickets); ?> ticket/s</h5>
                </div>
                <?php endif; ?>
            </div>    
                <label for="money">I want to buy:</label>
                <div class="row">
                    <div class="col-8">
                        <div class="input-group mb-3">
                            <input id="dollars" class="form-control" type="number" value="1" step="1" oninput="updateUsdValue();" required name="ntickets" min="1">
                            <div class="input-group-append">
                                <span class="input-group-text">tickets</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <span>Total: $</span><span id="coinConvers">0</span>
                        <script>
                            // Start coin convert
                            var ticketPrice = <?php echo getLotteryTicketPrice(); ?>;
                            $('#cost').text("$" + ticketPrice.toLocaleString("en-US", {
                                'minimumFractionDigits': 4,
                                'maximumFractionDigits': 4
                            }));

                            function updateUsdValue() {

                                document.getElementById('coinConvers').innerHTML = (document.getElementById('dollars').value * ticketPrice).toLocaleString("en-US", {
                                    'minimumFractionDigits': 2,
                                    'maximumFractionDigits': 2
                                });
                            }
                            updateUsdValue()
                        </script>
                    </div>
                </div>
                <button class="btn btn-success" type="submit">🍀 Play</button>
            </form>
        </div>
    </div>
</div>


</div>
</body>
<?php require './../ui/footer.php'; ?>

</html>