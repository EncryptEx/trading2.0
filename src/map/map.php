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

	<script src="./../ui/maps/mapdata.php"></script>
	<script src="./../ui/maps/worldmap.js"></script>

	<style>
		.flagIcon {
			width: 5rem;
		}

		.bodyCard {
			padding: 0.5rem;
		}
	</style>
</head>
<?php require './../ui/navbar.php'; ?>
<div class="container" style="margin-top:30px;">
	<?php
	if (isset($_GET['e'])) {
		echo RetrieveError($_GET['e']);
	}
	?>
	<div class="row">
		<div class="col-sm-12">
			<h1>Conquer the world</h1>
			<p class="text-muted">Buy a country with USD by clicking it. All owned countries pay in random crypocurrencies every day</p>
			<?php if (isset($_GET['s']) && isset($_GET['v'])) {
				if ($_GET['s'] == 1) {
					echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Success!</strong> You have successfully bought <b>" . htmlentities($_GET['v']) . "</b>!</div>";
				}
				if ($_GET['s'] == 2) {
					echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Success!</strong> You have successfully placed an auction of <b>" . htmlentities($_GET['v']) . "</b>!</div>";
				}
			}
			if (isset($_GET['s']) && $_GET['s'] == 3) {
				echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Success!</strong> You have successfully cancelled the auction.</div>";
			}
			?>
			<div id="map"></div>


		</div>
		<div class="col-sm-12 mt-4">
			<h3 class="d-inline-block">Country Auction</h3>
			<button class="btn btn-success float-right mb-3" style="margin-right:15px;" data-toggle="modal" data-target="#createAuction">
				<span class="material-symbols-outlined align-middle">
					add
				</span>
			</button>

			<div class="modal fade" id="createAuction">
				<div class="modal-dialog">
					<div class="modal-content">

						<form action="createAuction.php" method="POST">
							<!-- Modal Header -->
							<div class="modal-header">
								<h4 class="modal-title">Create a Country Auction</h4>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<!-- Modal body -->
							<div class="modal-body">
								<div class="form-group">
									<label for="country">I want to auction:</label>
									<select class="form-control" id="country" required name="countryCode">
										<?php
										$countriesOwned = getOwnedCountries($userid);
										if (count($countriesOwned) > 0) {
											foreach ($countriesOwned as $country) {
												// skip countries that are in auction already
												if (boolval(doesAuctionExist($country['countryCode']))) {
													continue;
												} ?>
												<option value='<?php echo htmlentities($country['countryCode']); ?>'><?php echo htmlentities(getCountryName($country['countryCode'])); ?></option>
											<?php }
										} else { ?>
											<option disabled>You do not own any country.</option>
										<?php } ?>
									</select>
								</div>
								<div class="form-group">
									<label for="price">At the starting price of:</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">$</span>
										</div>
										<input type="number" class="form-control" id="price" placeholder="10000" required name="startingPrice">
									</div>
								</div>
							</div>

							<!-- Modal footer -->
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary">Create Auction!</button>
							</div>
						</form>

					</div>
				</div>
			</div>


			<?php
			$auctions = getCountriesAuction();
			if (count($auctions) == 0) {
				echo '<br>No auctions running right now';
			} else {
			?>
				<div class="col">
					<?php foreach ($auctions as $auction) {
						if (checkAuctionExpiration($auction['countryCode'])) { //if has expired, skip it and do the backend	
							continue;
						} ?>
						<div class="col-12 card">
							<a class="bodyCard text-decoration-none text-dark" href="auction.php?c=<?php echo htmlentities($auction['countryCode']) ?>">
								<div class="row">
									<div class="col-4 col-sm-2 col-xl-1">
										<div class="d-block my-2">
											<img class="flagIconrounded img-fluid shadow-sm" crossorigin="anonymous" src="https://countryflagsapi.com/png/<?php /* thanks flag api*/ echo htmlentities($auction['countryCode']); ?>" alt="">
										</div>
									</div>
									<div class="col-8 col-sm-10 col-xl-11">
										<h4 class="align-middle"><?php echo htmlentities(getCountryName($auction['countryCode'])); ?></h4>
										<?php
										$getLastBet = getLastBet($auction['countryCode']);
										if ($getLastBet != NULL) { ?>
											<p><?php echo "Last bid: <b>$" . htmlentities(number_format($getLastBet['bet'], 0)) . "</b> by " . getUserName($getLastBet['ownerId']) . " made " . time_since(time() - $getLastBet['timestamp']) . " ago"; ?></p>
										<?php } else { ?>
											<p class="text-muted">Starting Price: $<?php echo htmlentities(number_format($auction['startingPrice'], 0)); ?></p>
										<?php } ?>
									</div>
								</div>
							</a>
						</div>
					<?php } ?>
				</div>

			<?php }
			?>
		</div>
	</div>


	<div class="modal fade" id="Modal">
		<div class="modal-dialog">
			<div class="modal-content">
				<form action="buyCountry.php" method="POST">
					<!-- Modal Header -->
					<div class="modal-header">
						<h4 class="modal-title">Confirm purchase of <span class="countryName"></span></h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>

					<!-- Modal body -->
					<div class="modal-body row">
						<div class="col-4 mt-2">
							<img  crossorigin="anonymous" src="" alt="country that is being purchased" id="countryImagePreview" style="width: 100%;" class="rounded shadow">
						</div>
						<div class="col-8">
							You are going to spend a total of <code id="basePrice">$<?php echo htmlentities(number_format($countryBasePrice, 0)); ?></code> to buy the country of <b class="countryName"></b>
							<p class="text-muted">Are you sure?</p>
						</div>
					</div>

					<!-- form data -->
					<input type="hidden" name="countryCode" id="countryCodeToBuy" value="12">

					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Confirm purchase</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		function confirmBuy(countryCode, countryName, price) {
			$('#countryImagePreview').attr("src", 'https://countryflagsapi.com/png/' + countryCode); // thanks countryFlagsAPI
			$('.countryName').text(countryName);
			$('#basePrice').text("$"+price.toLocaleString('en-US'));
			$('#countryCodeToBuy').attr("value", countryCode);
			setTimeout(() => {
				$('#Modal').modal('show');
			}, 200);
		}
	</script>

</div>
</body>
<?php require './../ui/footer.php'; ?>

</html>