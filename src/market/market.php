<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

if (!isset($_GET['marketid'])) {
	die("No market id recieved.");
}

$marketid = base64_decode($_GET['marketid']);
if (getName($marketid) == NULL) {
	// wrong base64 id, or market does not exist
	header("location:index.php?e");
}
$p = getPercentage($marketid);
if ($p > 0) {
	$b = "success";
} else if ($p < 0) {
	$b = "danger";
} else {
	$b = "primary";
}
$userid = $_SESSION['usr'];
$n = getname($marketid);
$v = getValue($marketid);
$o = getOwnership($marketid, $userid);
$f = getLogo($marketid);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Tradin' Zone</title>
	<?php require './../meta.php'; ?>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@48,400,0,0" />
	<style>
		@media (max-width: 425px) {
			.badge {
				width: 100%;
				overflow-x: auto;
			}
		}

		table.transactions tr td.indicator {
			font-weight: bold;
		}

		.text-down {
			color: #e56846;
		}

		.text-up {
			color: #23d886;
		}

		.text-eq {
			color: #239ed8;
		}
	</style>
</head>
<?php require './../ui/navbar.php'; ?>

<body>
	<div class="container" style="margin-top:10px;">
		<?php
		if (isset($_GET['s']) && isset($_GET['v']) && isset($_GET['v2']) && isset($_GET['v3'])) {
			if ($_GET['s'] == 1) {
				echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
				<strong>Success!</strong> You successfully placed a 'buy' offer of " . htmlentities($_GET['v']) . " " . $n . "/s, which worth $" . htmlentities($_GET['v2']) . " in total. (<b>$" . htmlentities($_GET['v3']) . "/u</b>)" .
					"<button type='button' class='close' data-dismiss='alert' aria-label='Close'>&times;</button>"
					. "</div>";
			} else if ($_GET['s'] == 2) {
				echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
				<strong>Success!</strong> You successfully placed a 'sell' offer of " . htmlentities($_GET['v']) . " " . $n . "/s, which worth $" . htmlentities($_GET['v2']) . " in total. (<b>$" . htmlentities($_GET['v3']) . "/u</b>)"
					. "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>&times;</button>"
					. "</div>";
			}
		}
		if (isset($_GET['s']) && $_GET['s'] == 3 && isset($_GET['v'])) {
			echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
				<strong>Success!</strong> You successfully refunded $" . htmlentities($_GET['v']) . " by deleting the offer."
				. "<button type='button' class='close' data-dismiss='alert' aria-label='Close'>&times;</button>"
				. "</div>";
		}

		?>
		<?php
		if (isset($_GET['e'])) {
			echo RetrieveError($_GET['e']);
		}
		?>
		<img src="<?php echo $f; ?>" alt="" class="logo rounded-circle" width="40" height="40" style="margin-bottom:15px">
		<h1 style="display:inline;">

			<?php echo $n; ?>
			<span id="valuee" class="badge badge-primary" style="margin-left:10px;"><?php echo "$" . $v; ?></span>
			<span id="percentagee" class="badge badge-<?php echo $b; ?>" style="margin-left:10px;"><?php echo $p . "%"; ?></span>
			<a class="btn btn-light" href="./history.php?marketid=<?php echo htmlentities($_GET['marketid']); ?>"><span class="material-symbols-outlined">
					timeline
				</span></a>
			<?php if ($o != 0) : ?>
		</h1>
		<h3 style="display:inline;">
			<span id="ownershipp" class="badge badge-secondary" style="margin-left:10%;">
				<?php echo number_format($o, 3) . " " . $n . "s ($" . number_format($o * $v, 2) . ")"; ?>

			</span><?php endif; ?>
		</h3><br>
		<div class="row">
			<div class="col-md-8">
				<br>
				<canvas id="chart_div4" class="table-responsive" style="width:100%;"></canvas>
				<div id="api">

				</div>
			</div>
			<div class="col-md-4" id="buy">
				<h3>Buy <a href="#" onclick="turnToSell();">or Sell</a> <?php echo $n ?></h3>
				<!-- <h6>Buy fee: <code>0.001%</code> ($<span id="fee1"></span>) </h6> -->

				<form action="currency.php?m=1" method="POST">

					<label for="money">I want to spend:</label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text">$</span>
						</div>
						<input id="money" class="form-control" type="number" placeholder="<?php echo $v ?>" step="any" onchange="moneY();" required name="money" min="0.01">
					</div>
					<label for="coins">For this much:</label>
					<div class="input-group mb-3">
						<input id="coins" class="form-control" type="number" placeholder="1" step="any" onchange="coinS();" required name="coins">
						<div class="input-group-append">
							<span class="input-group-text"><?php echo $n . "/s"; ?></span>
						</div>
					</div>
					<br>
					<input type="hidden" value="<?php echo htmlentities($_GET['marketid']); ?>" name="referrer">
					<button type="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i> Buy</button>

				</form>
			</div>
			<div class="col-md-4" id="sell" style="display:none;">
				<h3><a href="#" onclick="turnToBuy();">Buy</a> or Sell <?php echo $n ?></h3>
				<!-- <h6>Sell fee: <code>0.01%</code> ($<span id="fee2"></span>) </h6> -->
				<form action="currency.php?m=2" method="POST">

					<label for="money">I want to sell:</label>
					<div class="input-group mb-3">
						<div class="input-group-prepend">
							<span class="input-group-text">$</span>
						</div>
						<input id="money2" class="form-control" type="number" placeholder="<?php echo $v ?>" step="any" onchange="moneY2();" required name="money" min="0.01">
					</div>
					<label for="coins">For this much:</label>
					<div class="input-group mb-3">
						<input id="coins2" class="form-control" type="number" placeholder="1" step="any" onchange="coinS2();" required name="coins">
						<div class="input-group-append">
							<span class="input-group-text"><?php echo $n . "/s"; ?></span>
						</div>
					</div>
					<br>
					<input type="hidden" value="<?php echo htmlentities($_GET['marketid']); ?>" name="referrer">
					<button type="submit" class="btn btn-primary"><i class="fas fa-money-bill-wave"></i> Sell</button>

				</form>
			</div>
		</div>
	</div>

	<script>
		<?php
		if (isset($_GET['sell'])) {
			echo 'turnToSell();';
		}
		?>

		function turnToSell() {
			$('#sell').show();
			$('#buy').hide();
		}

		function turnToBuy() {
			$('#sell').hide();
			$('#buy').show();
		}
		var oneceqmoney = <?php echo $v; ?>;

		function moneY2() {
			var money = document.getElementById('money2');
			var coins = document.getElementById('coins2');
			oneceqmoney = chart.data.datasets[0].data[14];

			coins.value = money.value / oneceqmoney;
		}

		function moneY() {
			var money = document.getElementById('money');
			var coins = document.getElementById('coins');
			oneceqmoney = chart.data.datasets[0].data[14];

			coins.value = money.value / oneceqmoney;
		}

		function coinS() {
			var money = document.getElementById('money');
			var coins = document.getElementById('coins');
			oneceqmoney = chart.data.datasets[0].data[14];

			money.value = coins.value * oneceqmoney;
		}

		function coinS2() {
			var money = document.getElementById('money2');
			var coins = document.getElementById('coins2');
			oneceqmoney = chart.data.datasets[0].data[14];

			money.value = coins.value * oneceqmoney;
		}

		function addData(chart, label, data) {
			chart.data.labels.push(label);
			chart.data.datasets.forEach((dataset) => {
				dataset.data.push(data);
			});
			chart.update();
		}

		function removeData(chart) {
			chart.data.labels.pop();
			chart.data.datasets.forEach((dataset) => {
				dataset.data.pop();
			});
			chart.update();
		}
		var yValues = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
		var chart = new Chart("chart_div4", {
			type: "line",
			data: {
				labels: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
				datasets: [{
					fill: false,
					lineTension: 0,
					backgroundColor: "rgba(60,145,230,1.0)",
					borderColor: "rgba(60,145,230,0.1)",
					data: yValues
				}]
			},
			options: {
				legend: {
					display: false
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
		var timestamps = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14];
		$.ajax({
			url: "graphapi.php?marketid=<?php echo base64_encode($marketid); ?>",
			method: "GET",
			success: function(data) {
				percentage = data.split("||")[1];
				yValues = data.split("||")[0].split(",");
				// values = values.split(",");
				timestamps[14] = data.split("||")[2].split(",")[14];
				yValues.reverse();
				yValues.reverse();
				// removeData(chart);
				yValues.forEach((e) => {
					// console.log(e);
					chart.data.labels.shift();
					chart.data.datasets[0].data.shift();
					chart.data.labels.push(e);
					$('#fee1').html((Math.round((e * 0.001) * 100) / 100).toString().replace(".", ","));
					$('#fee2').html((Math.round((e * 0.01) * 100) / 100).toString().replace(".", ","));

					chart.data.datasets[0].data.push(e);
				});
				// chart.data.datasets.data=yValues;
				chart.update();

			}
		});
		setInterval(() => {
			// removeData(chart);
			$.ajax({
				url: "graphapi.php?marketid=<?php echo base64_encode($marketid); ?>",
				method: "GET",
				success: function(data) {

					if (data.split("||")[2].split(",")[14] != timestamps[14]) {

						timestamps[14] = data.split("||")[2].split(",")[14];
						percentage = data.split("||")[1];
						yValues = data.split("||")[0].split(",");

						// values = values.split(",");
						yValues.reverse();
						yValues.reverse();
						// removeData(chart);
						yValues.forEach((e) => {
							// console.log(e);
							chart.data.labels.shift();
							chart.data.datasets[0].data.shift();
							chart.data.labels.push(e);

							chart.data.datasets[0].data.push(e);
						});
						console.log("Reloading");
						oneceqmoney = chart.data.datasets[0].data[14];
						$('#valuee').html("$" + oneceqmoney);
						$('#money').attr('placeholder', oneceqmoney);
						$('#percentagee').html(percentage + "%");
						if (percentage > 0) {
							document.getElementById("percentagee").className = "badge badge-success";
						} else if (percentage < 0)
							document.getElementById("percentagee").className = "badge badge-danger";
						else {
							document.getElementById("percentagee").className = "badge badge-primary";
						}
						// chart.data.datasets.data=yValues;
						chart.update();
					}
				}

			});

		}, 30000);

		function addData(chart, label, data) {
			// removeData(chart);

			chart.data.labels.push(label);
			chart.data.datasets[0].data.push(data);

			// });
			chart.update();
		}

		function removeData(chart) {
			chart.data.labels.pop();
			chart.data.datasets[0].data.shift();


			chart.update();
		}
	</script>
</body>
<!-- <script defer>
	// EDIT js

	submit = false

	function edit($title1, $title) {
		var title = document.getElementById('price1');
		var input = document.getElementById('triggerTitle');
		var button = document.getElementById('editBtn');
		var cancel = document.getElementById('editBtncancel');
		var deleteBtn = document.getElementById('deleteButton');
		if (!submit) {
			cancel.classList.add('d-inline-block');
			cancel.classList.remove('d-none');
			input.classList.add('d-block');
			input.classList.remove('d-none');
			title.classList.add('d-none');
			title.classList.remove('d-block');
			deleteBtn.classList.add('d-none');
			deleteBtn.classList.remove('d-inline-block');
			button.innerHTML = "<span class=\"material-symbols-outlined align-middle\">done</span>";
			submit = true;
		} else {
			document.getElementById('editForm').submit()
		}
	}

	function cancelEdit() {
		cancel.classList.remove('d-inline-block');
		cancel.classList.add('d-none');
		input.classList.remove('d-block');
		input.classList.add('d-none');
		title.classList.remove('d-none');
		title.classList.add('d-block');
		deleteBtn.classList.remove('d-none');
		deleteBtn.classList.add('d-inline-block');
		button.innerHTML = "<span class=\"material-symbols-outlined align-middle\">edit</span>";
		submit = false;
	}
</script> -->
<br><br><br>
<?php require './../ui/footer.php'; ?>

</html>