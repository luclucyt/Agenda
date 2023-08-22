<?php

echo "<script>
if(window.history.replaceState) {
    window.history.replaceState( null, null, window.location.href );
}</script>";

$servername = "localhost";
$username = "LucasDatabase";
$password = "LucasDatabase";

$dbname = "agenda";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}