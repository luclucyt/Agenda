<?php
    global $connection;

$servername = "81.206.73.35";
$username = "LucasDatabase";
$password = "LucasDatabase";

$dbname = "agenda";

$connection = mysqli_connect($servername, $username, $password, $dbname);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}