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
	<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
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
	?>
	<div class="earnings">
		<h1>The Dino Game</h1>
		<div class="row">
			<div class="col-sm-12">

				<h6>Game fee: <code><?php echo htmlentities($FEE * 100); ?>%</code> ($<span id="fee1"><?php echo htmlentities($FEE * 100); ?></span>) deposited into the main JACKPOT</h6>
				<script>
					// Fee manager

					var fee = <?php echo htmlentities($FEE); ?>;

				</script>
				<?php if (isset($_GET['s']) && isset($_GET['v'])) {
					if ($_GET['s'] == 1) {
						echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Congrats!</strong> You have successfully won " . htmlentities($_GET['v']) . "</div>";
					}
					if ($_GET['s'] == 2) {
						echo "<div class='alert alert-danger alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Oops!</strong> You have lost: " . htmlentities($_GET['v']) . " in total, that were added to the jackpot as well.</div>";
					}
				}
				?>
				<form action="dinoBackend.php" method="POST" id="fm">

					<label for="money">I want to play with:</label>
					<div class="row">
						<div class="col-8">
							<div class="input-group mb-3">
								<input id="dollars" class="form-control" type="number" value="1" step="any" oninput="updateUsdValue();" required name="coins">
								<div class="input-group-append">
									<select class="input-group-text" style="text-align:left;" id="marketSelector" name="marketId" onchange="updateUsdValue()">
										<?php foreach (getMarkets() as $markets) {
											echo "<option value='" . $markets['id'] . "'>" . $markets['name'] . "s</option>";
										} ?>
									</select>
								</div>
							</div>
						</div>
						<div class="col-4 alert alert-info">
							<span>That equals: $</span><span id="coinConvers">0</span>
							<script>
								// Start coin convert
								var UsdPrices = {
									<?php foreach (getMarkets() as $markets) {
											echo $markets['id'] . ": " . getValue($markets['id']) . ",";
										} ?>
								};

								function updateUsdValue(){
									dollars = UsdPrices[document.getElementById('marketSelector').value]*document.getElementById('dollars').value;
									document.getElementById('fee1').innerHTML = (fee*dollars).toLocaleString("en-US", {'minimumFractionDigits':2,'maximumFractionDigits':2});

									document.getElementById('coinConvers').innerHTML = dollars.toLocaleString("en-US") + " + $" + (fee*dollars).toLocaleString("en-US", {'minimumFractionDigits':2,'maximumFractionDigits':2}) +  " fee";
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