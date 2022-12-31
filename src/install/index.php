<?php
$isInstalling = isset($_GET['start']);
if (isset($_GET['delete'])) {
	unlink("./markets.sql");
	unlink("./index.php");
	header("location:./../index.php");
	die();
}
?>
<!DOCTYPE html> 
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	

	<title>Install Tradin'Zone</title>
</head>
<?php require './../ui/navbar.php'; ?>

<body>
	<div class="container" style="margin-top:30px">
		<?php if (!$isInstalling) : ?>
			<h3>Install Tradin' Zone</h3>
			Before starting, make sure the credentials of <code>utils.php</code> (lines 10-15) <br>
			<hr>
			<?php require './../utils.php';
			echo "<b>Actual Database: </b><code>" . $dbname . "</code> (needs to be created) <br>Actual User <code>" . $user . "</code><br>Actual password: <code>" . $pass . "</code>";

			?>
			<hr>


			If you have checked db credentials you can now start: <br> <br>
			<form action="/install/index.php?start" method="POST">
				<div class="form-group required">
					<label for="username"> Enter your Username (Won't have superpowers) </label>
					<input type="text" class="form-control text-lowercase" id="username" required="" name="username" placeholder="johnSnow">
				</div>
				<div class="form-group required">
					<label for="username"> Enter your Password (The longer, the better) </label>
					<input type="password" class="form-control text-lowercase" id="password" required="" name="password" placeholder="GameOfThrones2022">
				</div>
				<button class="btn btn-success">INSTALL</button>
			</form>
			<br>

		<?php
		endif;
		if ($isInstalling): ?>
			<h3>Installing Tradin' Zone...</h3>

			<?php

			require './../utils.php';
			$sql = file_get_contents('./markets.sql');

			try {
				$qr = $pdo->exec($sql);
			} catch (\Throwable $th) {
				echo "<b>" . $th . "</br>";
				$qr = 1;
			}
			echo "<b>Database Installed:</b> " . (!boolval($qr) ? 'Success ✅' : 'Failed ❌'); ?>
			<br>
			<b>User created:</b>
			<?php
			if (!anyUser()) {
				$userid = CreateUser($_POST['username'], "Founder", $_POST['password'], 1);
				addition($userid, 0, 1000);
				echo "Success ✅";
			} else {
				echo "Failed ❌. A user is already created";
			}
			?>
			<br>
			<b>Started lottery countdown:</b>
			<?php
			if (addNewJackpotDeadline()) {
				echo "Success ✅";
			} else {
				echo "Failed ❌. Something went bad.";
			}
			?>
			<h4>Now you can delete this file to prevent cybersec incidents.</h4>
			<a href="./?delete" class="btn btn-danger">DELETE INSTALLATION FILE</a>
		<?php endif; ?>
	</div>
	
	<br>
	
</div>
</body>

<?php require './../ui/footer.php'; ?>

</html>