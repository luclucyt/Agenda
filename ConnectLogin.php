<?php
    //define the data that will be used to connect to the database
    $servername = "localhost";
    $username = "root";
    $password = "";

    //define the database name ( the name of the database you created, not the table )
    $dbname = "login";

    // Create connection to the database
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // if the connection fails, display an error message
    if (!$conn) {
        // die() is a function that stops the execution of the script and displays the error message
        die("Connection failed: " . mysqli_connect_error());
    }
?>