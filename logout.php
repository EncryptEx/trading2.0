<?php
session_start();
setcookie("userid", "", time() - 60480099, "/");
session_destroy();
header("location:login.php");
