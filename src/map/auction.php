<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

if (!isset($_GET['c']) || !boolval(doesAuctionExist($_GET['c']))) {
	header('location:map.php?e=17');
	die();
}

$auction = getAuctionInfo($_GET['c']);
$auctionName = htmlentities(getCountryName($auction['countryCode']));

if(checkAuctionExpiration($auction['countryCode'])){
	header('location:map.php?e=24');
}

$lastBet = getLastBet($auction['countryCode']);
if ($lastBet != NULL) {
	$minimumBet = $lastBet['bet'] + 1;
} else {
	$minimumBet = $auction['startingPrice'] + 1;
}
$parsedMinBet = htmlentities(number_format($minimumBet, 0, ",", "."));
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tradin' Zone</title>
	<?php require './../meta.php'; ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment-with-locales.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
</head>
<?php require './../ui/navbar.php'; ?>
<div class="container" style="margin-top:30px;min-height:50rem;">
	<?php
	if (isset($_GET['e'])) {
		echo RetrieveError($_GET['e']);
	}
	if (isset($_GET['s']) && isset($_GET['v'])) {
		if ($_GET['s'] == 1) {
			echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Success!</strong> You have successfully bid for <b>$" . htmlentities(number_format($_GET['v'], 0)) . "</b>!</div>";
		}
	}
	if (isset($_GET['s']) && $_GET['s'] == 2) {
		echo "<div class='alert alert-success alert-dismissible'>
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			<strong>Success!</strong> You have set the auction's end time to <b>tomorrow</b> at <b>" . htmlentities(date('H:i:s', time())) . "</b>!</div>";
	}
	?>
	<div class="earnings">
		<div class="row">
			<div class="col-8 col-md-10">
				<h1><?php echo "Auction of " . $auctionName; ?></h1>
				<?php if ($auction['endAuction'] != NULL) {
					//countdown active, so let's print it.
				?>
					<h3>Auction ends in: <span id="cntdwn"></span></h3>
					<script defer>
						function updateTimer() {
							var now = new Date().getTime();
							var distance = countDownDate - now;
							var days = Math.floor(distance / (1000 * 60 * 60 * 24));
							var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
							var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
							var seconds = Math.floor((distance % (1000 * 60)) / 1000);
							document.getElementById("cntdwn").innerHTML = /*days + "d " + */hours + "h " +
							minutes + "m " + seconds + "s ";
							if (distance < 0) {
								clearInterval(x);
								document.getElementById("cntdwn").innerHTML = "now (auction locked)"
								window.location.reload();
							}
						}
						
						var countDownDate = new Date(<?php echo $auction['endAuction']*1000;?>).getTime();
						updateTimer();
						var x = setInterval(updateTimer, 1000);
					</script>
				<?php } ?>
				<p><b>Auctioneer:</b> <?php echo htmlentities(getUserName($auction['ownerId'])); ?></p>
				<p><b>Starting Price: </b> $<?php echo htmlentities(number_format($auction['startingPrice'], 0)); ?></p>
				<?php
				if ($auction['ownerId'] == $userid && $auction['endAuction'] == NULL) {
					// own auction and not in countdown
				?>
					<button class="btn btn-light mb-3" data-toggle="modal" data-target="#endAuction">
						<span class="material-symbols-outlined align-middle">
							<?php

							if ($lastBet != NULL) {
								echo 'check</span> End auction (24h)';
							} else {
								echo 'delete</span> Cancel auction (now)';
							} ?>

					</button>
				<?php }
				?>
			</div>
			<div class="col-4 col-md-2">
				<img class="flagIconrounded img-fluid shadow" crossorigin="anonymous" src="./../ui/flags/<?php  echo htmlentities(strtolower($auction['countryCode'])); ?>.png" alt="">
			</div>
		</div>

		<div class="modal fade" id="endAuction">
			<div class="modal-dialog">
				<div class="modal-content">

					<form action="endAuction.php" method="POST">
						<!-- Modal Header -->
						<div class="modal-header">
							<h4 class="modal-title">End Auction of <?php echo $auctionName; ?></h4>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<input type="hidden" value="<?php echo $auction['countryCode']; ?>" name="countryCode">

						<!-- Modal body -->
						<div class="modal-body row">
							<div class="col-4 mt-2">
								<img crossorigin="anonymous" src="./../ui/flags/<?php  echo htmlentities(strtolower($auction['countryCode'])); ?>.png" alt="country flag that is being transfered" id="countryImagePreview" style="width: 100%;" class="rounded shadow">
							</div>
							<div class="col-8">
								<?php if ($lastBet != NULL) { ?>
									<p>You are about to start the end countdown (1d) to auction to the best bidder. The ownership transaction will take place in 24 hours, as well as your payment.</p>
								<?php } else { ?>
									<p>You are about to cancel the auction, due to the fact that nobody has bid for your country. You'll not receive any USD.
									<?php } ?>
									<p class="text-mmuted">Are you sure?</p>
							</div>
						</div>

						<!-- Modal footer -->
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-success">End Auction!</button>
						</div>
					</form>

				</div>
			</div>
		</div>

		<hr>


		<div class="row mt-3">
			<?php $bets = getBets($auction['countryCode']);
			if ($bets != NULL) { ?>
				<div class="col-12">
					<canvas id="lineChart" style="width:80%;height:300px;"></canvas>
				</div>
			<?php } ?>
			<div class="col-12 col-md-6 mb-4 mb-md-0">
				<h4>Bid</h4>
				<form action="bet.php" method="POST">
					<input type="hidden" name="countryCode" value="<?php echo $auction['countryCode']; ?>">
					<div class="form-group">
						<label for="betPrice">Enter your bet (needs to be higher than last bet or starting price):</label>
						<p class="text-muted small">Your bet will be refunded if anyone bids higher than your bet.</p>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<input type="number" class="form-control" id="betPrice" placeholder="<?php echo $parsedMinBet; ?>" min="<?php echo $minimumBet; ?>" name="bet" required>
						</div>
					</div>
					<button type="submit" class="btn btn-success">Bid!</button>
				</form>
			</div>

			<div class="col-12 col-md-6	">
				<h4>Lastest Bids</h4>

				<!-- chart goes here -->

				<?php
				$bets = getBets($auction['countryCode']);
				if ($bets != NULL) { ?>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Bid</th>
								<th>Username</th>
								<th>Time elapsed</th>
							</tr>
						</thead>
						<?php foreach ($bets as $bet) { ?>
							<tr>
								<td>$<?php echo htmlentities(number_format($bet['bet'], 0)); ?></td>
								<td><?php echo htmlentities(getUserName($bet['ownerId'])); ?></td>
								<td><?php echo htmlentities(time_since(time() - $bet['timestamp'])) ?> ago</td>
							</tr>


						<?php } ?>
					</table>
				<?php } else { ?>
					<p class="text-muted">No bids yet.</p>
				<?php } ?>

			</div>
		</div>
	</div>

	<?php $bets = getBets($auction['countryCode']);
	if ($bets != NULL) { ?>
		<script>
			// script to generate the chart
			var ctx = document.getElementById('lineChart').getContext('2d');
			var myChart = new Chart(ctx, {
				type: 'line',
				data: {
					labels: [],
					datasets: [{
						label: 'Price ($)',
						data: [<?php
								foreach ($bets as $bet) {
									echo "{x: new Date(" . $bet['timestamp'] * 1000 . "),";
									echo "y: " . $bet['bet'] . "},";
								} ?>],
						// {
						// x: date ,
						// y: num,
						// ],

						borderColor: [
							'rgba(255, 99, 132, 1)',
						],
						backgroundColor: 'rgba(255, 99, 132,1)',
						borderWidth: 3,
						fill: false,
						lineTension: 0,
					}]
				},
				options: {
					scales: {
						yAxes: [{
							ticks: {
								beginAtZero: true
							}
						}],
						xAxes: [{
							type: 'time',
							distribution: 'series',
							time: {
								unit: 'day',
							}
						}]
					}
				}
			});
		</script>
	<?php } ?>
</div>
</body>
<?php require './../ui/footer.php'; ?>

</html>