<?php

// login or show error messages.
$user = $_GET["username"];
$password = $_GET["password"];
$dbConnect = @mysqli_connect("localhost", $user, $password)
        or die("<p>Unable to connect to the database
    server.</p>"
        . "<p>Error code " . mysqli_connect_errno()
        . ": " . mysqli_connect_error() . "</p>");
echo "<p>Successfully connected to the database server.</p>";
@mysqli_select_db($dbConnect, "amolnar_d")
        or die("<p>Unable to select the database.</p>"
        . "<p>Error code " . mysqli_errno($dbConnect)
        . ": " . mysqli_error($dbConnect) . "</p>");
echo "<p>Successfully opened the database.</p>";
// additional statements that access the database
mysqli_close($dbConnect);




?>
