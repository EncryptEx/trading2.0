<?php
session_start();
require 'utils.php';
if (isset($_SESSION['usr'])) {
	header("location:index.php");
	die();
}
?><html lang="en" data-lt-installed="true">

<head>
	<title>Register | Tradin'Zone</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		body {
			margin: 0;
			overflow-y: hidden;
			font: normal 75% Arial, Helvetica, sans-serif;
		}

		canvas {
			display: block;
			vertical-align: bottom;
		}


		/* ---- particles.js container ---- */

		#particles-js {
			position: absolute;
			width: 100%;
			height: 100%;
			background-color: #1f5e34;
			background-repeat: no-repeat;
			background-size: cover;
			background-position: 50% 50%;
		}


		/* ---- stats.js ---- */

		.count-particles {
			background: #000022;
			position: absolute;
			top: 48px;
			left: 0;
			width: 80px;
			color: #13E8E9;
			font-size: .8em;
			text-align: left;
			text-indent: 4px;
			line-height: 14px;
			padding-bottom: 2px;
			font-family: Helvetica, Arial, sans-serif;
			font-weight: bold;
		}

		.js-count-particles {
			font-size: 1.1em;
		}

		#stats,
		.count-particles {
			-webkit-user-select: none;
			margin-top: 5px;
			margin-left: 5px;
		}

		#stats {
			border-radius: 3px 3px 0 0;
			overflow: hidden;
		}

		.count-particles {
			border-radius: 0 0 3px 3px;
		}

		#card {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			width: 40%;
			padding: 50px;
			border-radius: 4px;
		}

		input {
			display: block;
			width: 100%;
			outline: none;
			box-shadow: none;
			border: none;
			padding-left: 15px;
			padding-right: 15px;
			height: 60px;
			font-size: 20px;

		}

		input:focus,
		button:focus {
			outline: none;

		}

		button {
			background: #138D75;
			box-shadow: none;
			border: none;
			cursor: pointer;
			color: #fff;
			height: 60px;
			padding: 15px;
			font-size: 20px;

		}

		input,
		button {
			margin: 20px -15px 5px;
		}

		@media(max-width: 700px) {
			#card {
				width: 100% !important;

			}
		}

		@media(max-width: 530px) {

			input,
			button {
				height: 50px;
			}

			#card {
				width: 100%;
				padding: 35px;

			}

			input {
				width: 100%;
			}

			button {
				padding: 10px;
			}
		}
	</style>
		<?php require 'meta.php';?>
</head>

<body cz-shortcut-listen="true">
	<div id="particles-js">

		<div class="card card-body" id="card">
			<h3>Welcome to Tradin'Zone</h3>
			<?php
			if (isset($_GET['e'])) {
				echo RetrieveError($_GET['e']);
			}
			?>
			<p>Let's create an account for you:</p>
			<form id="submitForm" action="createacc.php" method="post">
				<div class="form-group required">
					<label for="username"> Enter your <i>fresh</i> Name </label>
					<input type="text" class="form-control" id="username" required="" name="name" value="" placeholder="John">
				</div>
				<div class="form-group required">
					<label for="username"> Enter your <i>fresh</i> Username </label>
					<input type="text" class="form-control text-lowercase" id="username" required="" name="username" value="" placeholder="johnSnow123">
				</div>
				<div class="form-group required">
					<label class="d-flex flex-row align-items-center" for="password"> Enter your&nbsp;<i>secure</i>&nbsp;Password
					</label>
					<input type="password" class="form-control" required="" id="password" name="password" value="" placeholder="**********">
				</div>
				<div class="form-group pt-1">
					<button class="btn btn-primary btn-block" type="submit"> Register </button>
				</div>
			</form>
			<p class="small-xl pt-3 text-center">
				<span class="text-muted"> Already a member? </span>
				<a href="login.php"> Log In </a>
			</p>
		</div>

		<canvas class="particles-js-canvas-el" style="width: 100%; height: 100%;" width="742" height="907"></canvas>
	</div>
	<script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js" type="text/javascript"></script>
	<script src="https://threejs.org/examples/js/libs/stats.min.js" type="text/javascript"></script>
	<script src="ui/particles.js" type="text/javascript"></script>

</body>

</html>