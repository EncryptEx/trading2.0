<nav class="navbar navbar-expand-md bg-light" id="myTopnav ">
	<b>Tradin'Zone</b>
	<button style="background-color:#ededed" class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
		<i class="fas fa-bars text-gray"></i>
	</button>

	<!-- Navbar links -->
	<div class="collapse navbar-collapse" id="collapsibleNavbar">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" href="/index.php">Markets</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/leaderboard/top.php">Leaderboard</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/airdrops/airdrop.php">Airdrops</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/lottery/lottery.php">Lottery</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/transfer/transfer.php">Transfer</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/map/map.php">World Map</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="/dino/dino.php">Dino Game</a>
			</li>
			<!-- <li class="nav-item">
			<a class="nav-link" href="swap.php">Swap</a>
		</li> -->

		</ul>
		<?php if (isset($_SESSION['usr'])) : ?>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item">
					<a class="nav-link" style="color:black;">Account: $<?php echo getOwnership(0, $userid) * getValue(0); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" style="color:black;">Total: $<?php echo getBalance($userid); ?></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="/logout.php">Logout</a>
				</li>

			<?php endif; ?>
			</ul>
	</div>
	<!-- <script>
function myFunction() {
	var x = document.getElementById("myTopnav");
	if (x.className === "topnav") {
		x.className += " responsive";
	} else {
		x.className = "topnav";
	}
}
</script> -->
</nav>