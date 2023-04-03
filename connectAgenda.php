<?php
//define the data that will be used to connect to the database
$servername = "81.206.73.35";
$username = "root";
$password = "";

//define the database name ( the name of the database you created, not the table )
$dbname = "agenda";

// Create connection to the database
$connA = mysqli_connect($servername, $username, $password, $dbname);

// if the connection fails, display an error message
if (!$connA) {
    // die() is a function that stops the execution of the script and displays the error message
    die("Connection failed: " . mysqli_connect_error());
}

echo "Connected successfully";
?>