<?php 
//start the session
if(session_status() == PHP_SESSION_NONE) {
    session_set_cookie_params(31536000);
    session_start(); //Start the session if it doesn't exist
}

if(!isset($_SESSION['userID'])) {
    echo "<script>window.location.href='$_SERVER{`SERVER_NAME`}/PHP/login/login.php'</script>";
    exit();
}

$userID = $_SESSION['userID'];