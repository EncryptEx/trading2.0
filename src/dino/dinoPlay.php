<?php
session_start();
require './../utils.php';
Islogged($_SESSION['usr']);
$userid = $_SESSION['usr'];

if(!isset($_SESSION['dinoMaxMilis']) || !isset($_SESSION['dinoCanPlay']) || $_SESSION['dinoMaxMilis'] == "") {
    header("location:dino.php");
    die();   
}
$dinoMaxMilis = $_SESSION['dinoMaxMilis'];
unset($_SESSION['dinoCanPlay']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script src="./../ui/confetti/confetti.min.js"></script>
	<title>Tradin' Zone</title>
    <style>
        .maximize{
            font-size: 5rem;
        }
    </style>
	<?php require './../meta.php'; ?>
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

			
            <div class="jumbotron ">
                <div class="row">
                    <div class="col-12 col-sm-6 justify-content-end">
                        <h5>Increase your earnings by raising the multiplier!</h5>
                        <span class="small text-muted">But careful, don't abuse and wait for too long... Otherwise you'll lose everything.</span>
                        <h1 id="multiplier" class="maximize"></h1>
                    </div>
                    <div class="col-6 d-none d-sm-inline-block justify-content-center">
                        <img id="gif" src="./../ui/gif/dino.gif" alt="dinosaur running for his life" style="max-width:200px;" width="100%" height="auto">
                    </div>
                </div>
                
                <button class="btn btn-danger" onclick="stop()" id="stop">Stop & claim!</button>
                <form action="claimDino.php" method="POST" id="formSecret">
                    <input type="hidden" name="m" id="multiplierFI">
                </form>
            </div>
	</div>
    <script>
        // Main game controller
        var isGameRunning = true;
        var maxTime = <?php echo $dinoMaxMilis;?>;
        var milis = 0;
        var multiplier = 0;
        
        var timer = setInterval(() => {
            multiplier += 0.001;
            if(milis > maxTime){
                $('#stop').hide();
                $('#multiplier').addClass("animate__animated animate__backInDown")
                $('#multiplier').text("You lost everything");    
                $('#gif').attr("src", "https://media.giphy.com/media/pWdckHaBKYGZHKbxs6/giphy.gif");
                multiplier = 0;
                redirect();
                clearInterval(timer);
                return;
            }
            $('#multiplier').text("x"+multiplier.toLocaleString("en-US", {'minimumFractionDigits':3,'maximumFractionDigits':3}));
            milis++;
        },  1);

        function stop(){
            $('#stop').hide();
            clearInterval(timer);
            redirect();
            $('#gif').hide();
        }

        function redirect(){
            
            setTimeout(()=>{
                $('#multiplierFI').val(multiplier);
                $('#formSecret').submit();
            },2000);
        }

        let confetti = new Confetti('stop');

        // Edit given parameters
        confetti.setCount(100);
        confetti.setSize(2);
        confetti.setPower(40);
        confetti.setFade(false);

    </script>

</div>
</body>
<?php require './../ui/footer.php'; ?>

</html>