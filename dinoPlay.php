<?php
session_start();
require 'utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

if(!isset($_SESSION['dino']) || $_SESSION['dinoMaxMult'] == "") {
    header("location:dino.php");
    die();   
}
$dinoMaxMult = $_SESSION['dinoMaxMult'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tradin' Zone</title>
	<?php require 'meta.php'; ?>
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
<?php require 'ui/navbar.php'; ?>
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
                
            </div>
		</div>
	</div>

</div>
</body>
<?php require './ui/footer.php'; ?>

</html>