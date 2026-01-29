<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr'] ?? null);
$userid = $_SESSION['usr'] ?? null;

$isNextAirdropReady = FALSE;
$airdrop = getLastAirdrop();
$isExpired = FALSE;
$IsAirdrop = FALSE;
if ($airdrop['status']) {
	if ($airdrop['timestamp'] <= time()) {
		$isNextAirdropReady = TRUE;
	}
	$IsAirdrop = TRUE;
	if ($airdrop['ftimestamp'] <= time()) {
		// invalidate airdrop 
		// echo 'airdrop expired.';
		expireAirdrop();
		$isExpired = TRUE;
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tradin' Zone</title>
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
	<?php require './../meta.php';?>
	<link rel="stylesheet" href="./../ui/golden-btn.css">
</head>
<?php require './../ui/navbar.php'; ?>
<div class="container" style="margin-top:30px; height:60em;">
	<?php
	if (isset($_GET['e'])) {
		echo RetrieveError($_GET['e']);
	}
	?>
	<div class="earnings">
		<h1>Crypto Airdrops</h1>
		<?php if ($isNextAirdropReady && $IsAirdrop && !$isExpired) :
			if (!hasClaimedAirdrop($userid, $airdrop['id'])) :
		?>
				<H3>Airdrop Ready to claim!</h3><br>
				<div class="row">
					<div class="col-sm-4">
						<img src="https://www.nicepng.com/png/full/34-345186_pubg-air-drop-sticker-pubg-airdrop-png.png " alt="" width="100%">
					</div>
					<div class="col-sm-8">
						<div style="display: flex; align-items: center;">
							<img style="display:inline; margin-right:10px;" src="<?php echo getImageUrl($airdrop['marketid']); ?>" alt="" class="logo rounded-circle" width="40" height="40">
							<h4 style="display:inline;"><?php echo $airdrop['quantity'] . " " . getName($airdrop['marketid']) . "s ($" . $airdrop['quantity'] * getValue($airdrop['marketid']) . ")"; ?></b></h4>
						</div><br>
						<form action="claim.php" method="post">
							<div class="g-recaptcha" data-sitekey="<?php echo $captchaPublic;?>" style="padding-top: 5px;"></div>
							<br> <button type="submit" class="golden-btn">Claim</button>
						</form>
						<br> <br>

						Airdrop will expire in: <b id="countdown"></b>
					</div>

				</div><br><br><br><br><br>
				<script>
					var c = <?php echo $airdrop['ftimestamp'] ?>;
					x();
					var t = setInterval(x, 1000);

					function x() {
						var n = Math.round(new Date().getTime() / 1000);
						var d = c - n;
						var da = Math.floor(d / (60 * 60 * 24));
						var h = Math.floor((d % (60 * 60 * 24)) / (60 * 60));
						var m = Math.floor((d % (60 * 60)) / (60));
						var s = Math.floor((d % (60)) / 1);
						document.getElementById("countdown").innerHTML = da + "d " + h + "h " +
							m + "m " + s + "s ";
						if (d <= 0) {
							clearInterval(t);
							window.location.reload();
						}
					}
				</script>


			<?php else : ?>
				<div class="row">
					<div class="col-sm-4">
						<img src="https://www.nicepng.com/png/full/34-345186_pubg-air-drop-sticker-pubg-airdrop-png.png " alt="" width="100%">
					</div>
					<div class="col-sm-8">
						<h4>You have claimed the airdrop!</h4>
						<h5>Enjoy your prize! ;)</h5>
						Airdrop will expire (for the rest) in: <b id="countdown"></b>
						<script>
							var c = <?php echo $airdrop['ftimestamp'] ?>;
							x();
							var t = setInterval(x, 1000);

							function x() {
								var n = Math.round(new Date().getTime() / 1000);
								var d = c - n;
								var da = Math.floor(d / (60 * 60 * 24));
								var h = Math.floor((d % (60 * 60 * 24)) / (60 * 60));
								var m = Math.floor((d % (60 * 60)) / (60));
								var s = Math.floor((d % (60)) / 1);
								document.getElementById("countdown").innerHTML = da + "d " + h + "h " +
									m + "m " + s + "s ";
								if (d <= 0) {
									clearInterval(t);
									window.location.reload();
								}
							}
						</script>
					</div>
				</div>
				<br><br><br><br><br>

			<?php endif;
		elseif (!$isNextAirdropReady && $IsAirdrop && !$isExpired) :  ?>
			<div class="row">
				<div class="col-sm-4">
					<img src="https://www.nicepng.com/png/full/34-345186_pubg-air-drop-sticker-pubg-airdrop-png.png " alt="" width="100%">
				</div>
				<div class="col-sm-8">
					<h3>An airdrop is comming!</h3> <br>
					Airdrop size:
					<br>

					<div style="display: flex; align-items: center;">
						<img style="display:inline; margin-right:10px;" src="<?php echo getImageUrl($airdrop['marketid']); ?>" alt="" class="logo rounded-circle" width="40" height="40">
						<h4 style="display:inline">
							<?php echo $airdrop['quantity'] . " " . getName($airdrop['marketid']) . "s ($" . $airdrop['quantity'] * getValue($airdrop['marketid']) . ")"; ?></h4> <br>
					</div>
					Airdrop ready to claim in: <br>
					<h5 id="countdown"></h5>
					<script>
						var c = <?php echo $airdrop['timestamp'] ?>;
						x();
						var t = setInterval(x, 1000);

						function x() {
							var n = Math.round(new Date().getTime() / 1000);
							var d = c - n;
							var da = Math.floor(d / (60 * 60 * 24));
							var h = Math.floor((d % (60 * 60 * 24)) / (60 * 60));
							var m = Math.floor((d % (60 * 60)) / (60));
							var s = Math.floor((d % (60)) / 1);
							document.getElementById("countdown").innerHTML = da + "d " + h + "h " +
								m + "m " + s + "s ";
							if (d <= 0) {
								clearInterval(t);
								window.location.reload();
							}
						}
					</script>
				</div>
			</div>
			<?php else : ?>
				<h4>No airdrops available for now.</h4>
				<h5>Return back later.</h5>
				<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

			<?php endif; ?>
			</div>

	</div>
	<?php require './../ui/footer.php'; ?>
	</body>

</html>