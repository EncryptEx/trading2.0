<?php
session_start();
require 'utils.php';
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
	<?php require 'meta.php';?>
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

		.title {
			margin-left: 10px;
		}
	</style>
</head>
<?php require 'ui/navbar.php'; ?>
<div class="container" style="margin-top:30px">
	<?php
	if (isset($_GET['e'])) {
		echo RetrieveError($_GET['e']);
	}
	?>
	<div class="row">
		<div class="col-lg-8">
			<div class="earnings">
				<div class="row order-sm-12 order-lg-1">
					<div class="col-md-7  order-2 order-md-1" style="margin-bottom:10px">
						<canvas id="balancepie"></canvas>
					</div>
					<div class="col-md-5  order-1 order-md-2" style="margin-bottom:10px">
						<h3>Balance: $<?php echo number_format(getBalance($userid), 2); ?></h3>
					</div>
				</div>
				<script>
					var chart = new Chart("balancepie", {
						type: "doughnut",
						data: {
							labels: [<?php
										$userBalances = getArrayBalances($userid);
										foreach ($userBalances as $marketArray) {
											if ($marketArray[1] != 0) {
												echo "'" . $marketArray[0] . "',";
											}
										} ?>],
							datasets: [{
								backgroundColor: ["#98DFAF", "#3F88C5", "#FFBA08", "#D00000", "#A2AEBB", "#51A3A3", "#419D78", "#C04ABC"],
								fill: false,
								lineTension: 0,

								data: [<?php foreach ($userBalances as $marketArray) {
											if ($marketArray[1] != 0) {
												echo "'" . $marketArray[1] . "',";
											}
										} ?>],
							}]
						},
						options: {
							legend: {
								display: true
							},
							animation: {
								duration: 0, // general animation time
							},
							hover: {
								animationDuration: 1000, // duration of animations when hovering an item
							},
							responsiveAnimationDuration: 0,
							scales: {
								// yAxes: [{ticks: {min: Math.min.apply(Math,yValues)-100, max:Math.max.apply(Math, yValues)+100}}],
							}
						}
					});
				</script>


			</div>
			<div class=" marketlist">


				<table class="table table-hover">
					<thead>
						<tr>
							<th>Market</th>
							<th>Owned Coins</th>
							<th>Actual Value</th>
							<th>Last Value</th>
						</tr>
					</thead>
					<tbody>

						<?php
						$marketLists = getMarkets();
						if (!$marketLists) {
							echo "No markets available for now.";
						} else {
							foreach ($marketLists as $market) :
								$p = getPercentage($market['id']);
								$marketVal = getValue($market['id']); ?>

								<tr>
									<td>
										<a class="links text-decoration-none" href="./market/market.php?marketid=<?php echo base64_encode($market['id']); ?>">
											<div style="flex:auto;">
												<img src="<?php echo $market['logo']; ?>" alt="" class="logo rounded-circle" width="30" height="30">
												<span class="title"><?php echo $market['name']; ?></span>
											</div>
										</a>
									</td>
									<td>
										<span class="ownership"><?php
																$coins = getOwnership($market['id'], $userid);
																if ($coins != 0) {
																	echo number_format($coins, strlen((string)$coins)) . " " . $market['name'] . "s  ($" . number_format($coins * $marketVal, 2) . ")";
																} ?></span>
									</td>
									<td><span class="actualvalue"><?php echo "$" . $marketVal; ?></span></td>
									<td><span class="percentage"><?php if ($p != 0) {
																		if ($p > 0) {
																			echo "<i class='fas fa-long-arrow-alt-up' style='color:#16C784;margin-right:10px;'></i><b style='color:#16C784;'>";
																		}
																		if ($p < 0) {
																			echo "<i class='fas fa-long-arrow-alt-down' style='color:#EA3943;margin-right:10px;'></i><b style='color:#EA3943;'>";
																		}

																		echo number_format($p, 2) . "%</b>";
																	} else {
																		echo "0%";
																	} ?></span>
									</td>
								</tr>



						<?php
							endforeach;
						} ?>
					</tbody>
				</table>
				<script>
					if ($(window).width() <= 720) {
						$('table').addClass('table-responsive');
					}
				</script>


			</div>
		</div>
		<div class="col-lg-4 d-none d-lg-inline">
			<h4>📈 Top market ever: </h4>
			<h5 class="text-secondary">
				<?php
				$topm = getTopMarket();
				echo ("$" . number_format($topm[1], 0) . " in ");
				echo (htmlentities(getName($topm[0])));
				?>
			</h5>
			<br>
			<h4>💲 Richest Player:</h4>
			<h5 class="text-secondary">
				<?php
				$userIDS = getUserIDs();
				if (!$userIDS) {
					echo "No users available for now.";
				} else {
					$usersBAL = [];
					foreach ($userIDS as $row) {
						$userID = $row['id'];
						$ValUser = getBalance($userID);
						$usersBAL[$userID] = $ValUser;
					}

					$richest = max($usersBAL);
					$rid = array_search($richest, $usersBAL);
					echo htmlentities(getUserName($rid)) . " with $" . number_format($richest, 2);
				}
				?>
			</h5><br>
			<?php
			$topm = getTopAirdropClaim();
			if ($topm[0]) : ?>
				<h4>🎈 Biggest Airdrop Claimed: </h4>
				<h5 class="text-secondary">
					<?php echo ("$" . number_format($topm[1], 0));
					?>
				</h5>
				<br><?php endif; ?>
			
		</div>
	</div>
</div>
<?php require './ui/footer.php'; ?>