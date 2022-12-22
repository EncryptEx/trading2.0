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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
	<script src="https://kit.fontawesome.com/df57820da4.js" crossorigin="anonymous"></script>
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
		<h1>Top Richest Players</h1>
		<h6>Your Balance: <?php echo number_format(getBalance($userid), 2); ?>$</h6>
	</div>
	<div class="marketlist">


		<table class="table table-hover">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Balance</th>
				</tr>
			</thead>
			<tbody>

				<?php
				$userIDS = getUserIDs();
				if (!$userIDS) {
					echo "No users available for now.";
				} else {
					$usersBAL = [];
					foreach ($userIDS as $row) {
						if ($row['status'] == 0) { // skip banned users
							continue;
						}
						$userID = $row['id'];
						$ValUser = getBalance($userID);
						$usersBAL[$userID] = $ValUser;
					}


					ksort($usersBAL);
					asort($usersBAL);
					$values = array_reverse($usersBAL);
					$userids = array();
					$values = array();

					foreach ($usersBAL as $userID => $ValUser) {
						$userids[] = $userID;
						$values[] = $ValUser;
					}
					$userids = array_reverse($userids);
					$values = array_reverse($values);
					$usersBAL = [];
					$c = 0;
					foreach ($userids as $uid) {
						$usersBAL[$uid] = $values[$c];
						$c++;
					}


					$r = 1;
					foreach ($usersBAL as $usrid => $balance) {
						echo '<tr><td>' . $r . '</td><td>' . htmlentities(getUserName($usrid)) . "</td><td>$" . number_format($balance, 2) . "</td>";
						$r++;
					}
				}


				?>


			</tbody>
		</table>


		<script>
			if ($(window).width() <= 720) {
				$('table').addClass('table-responsive');
			}
		</script>
	</div>
</div>
</body>
<?php require './ui/footer.php'; ?>

</html>