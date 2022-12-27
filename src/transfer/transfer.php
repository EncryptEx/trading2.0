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
	<?php require './../meta.php';?>
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
		<h1>Transfer money (<?php echo getName(0); //USD in this case
							?>)</h1>
		<div class="row">
			<div class="col-sm-12">

				<h6>Transfer fee: <code><?php echo htmlentities($FEE * 100); ?>%</code> ($<span id="fee1"><?php echo htmlentities($FEE * 100); ?></span>) </h6>
				<?php if (isset($_GET['s']) && isset($_GET['v'])) {
					if ($_GET['s'] == 1) {
						echo "<div class='alert alert-success alert-dismissible'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				<strong>Success!</strong> You have successfully " . htmlentities($_GET['v']) . "</div>";
					}
				}
				?>
				<form action="transaction.php" method="POST" id="fm">

					<label for="money">I want to send:</label>
					<div class="input-group mb-3">

						<div class="input-group-prepend">
							<span class="input-group-text">$</span>
						</div>
						<input id="money" class="form-control" type="number" placeholder="100" step="any" onchange="moneY();" required name="money" min="0.01" value="100">
					</div>
					<div id="dialog-confirm" title="Please confirm this transaction." style="display:none;">
						<p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
						You are going to send $<span id="tv">---</span> to <span id="td">---</span> 
						<p class="text-muted">This will cost you: <code>$<span id="tv2"></span></code></p>
						<br>Are you sure?</p>
					</div>
					<input type="hidden" id="destinatary" name="destinatary">
					<script>
						function moneY() {
							document.getElementById('fee1').innerHTML = document.getElementById('money').value * <?php echo htmlentities($FEE); ?>;
						}

						function submit() {
							updateModal();
							$("#dialog-confirm").dialog({
								resizable: false,
								height: "auto",
								width: 400,
								modal: true,
								buttons: {
									"Send": function() {
										$(this).dialog("close");
										document.getElementById('fm').submit();
									},
									Cancel: function() {
										$(this).dialog("close");
									}
								}
							});
						}

						function updateModal() {
							document.getElementById('tv').innerHTML = document.getElementById('money').value + " (with a fee: $" + document.getElementById('money').value * <?php echo htmlentities($FEE); ?>.toString() + ")";
							var selected = document.getElementById('userselect').value;
							var realvalue = document.getElementById('userselect').options[selected].getAttribute('uid');
							var realname = document.getElementById('userselect').options[selected].getAttribute('meta-info');



							document.getElementById('td').innerHTML =  (realname).toString();
							document.getElementById('tv2').innerHTML = +(document.getElementById('money').value) + (document.getElementById('money').value * <?php echo htmlentities($FEE); ?>);
							document.getElementById('destinatary').value = realvalue;
						}
						$(document).ready(function() {
							$(window).keydown(function(event) {
								if (event.keyCode == 13) {
									event.preventDefault();
									return false;
								}
							});
						});
					</script>
					<!-- <img class="input-group-text" style="text-align:left;"> -->
					<label for="userselect">To the user:</label>
					<select class="input-group-text" style="text-align:left;" id="userselect">
						<?php $c = 0;
						$allUsers = getUserIDs();
						if ($allUsers->rowCount() > 1) { // check if there's more than 1 user
							foreach (getUserIDs() as $user) {
								if ($user['id'] == $userid || $user['status'] == 0) {
									continue;
								}
								echo "<option uid='" . $user['id'] . "'value='" . $c . "' meta-info='" . htmlentities($user['username']) . " (" . htmlentities($user['name']) . ")'>" . htmlentities($user['username']) . " (" . htmlentities($user['name']) . ")</option>";
								$c++;
							}
						} else {
							echo "<option disabled class='disabled'>No users found</option>";
						}
						?>
					</select><br>
					<a class="btn btn-primary" type="button" onclick="submit();"><i class="fas fa-paper-plane"></i> Send</a>
				</form>
			</div>
		</div>
	</div>

</div>
</body>
<?php require './../ui/footer.php'; ?>

</html>