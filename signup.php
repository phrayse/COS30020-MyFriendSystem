<?php require ("functions/settings.inc.php"); include ("functions/sanitise.inc.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="author" content="STUID" />
    <link href="style.css" rel="stylesheet" />
    <title>Assignment 3: Signup</title>
</head>
<body>
<header>
    <?php include ("functions/header.inc.php"); ?>
</header>

<strong>Registration</strong>
<form action="signup.php" method="post">
    <p><label>Email:<input type="email" name="email" autofocus required /></label>
    <br><label>Profile Name:<input type="text" name="name" required /></label>
    <br><label>Password:<input type="password" name="password" minlength="1" required /></label>
    <br><label>Confirm Password:<input type="password" name="confirmPassword" required /></label>
    <br><input type="submit" value="Register" />
    <input type="reset" value="Clear" />
    </p>
</form>

<!-- This whole block oughta be a separate function.-->
<?php
if (isset($_POST["email"]) && isset($_POST["name"])) {
    // Form data exists, do actions.
    if ($_POST["password"] != $_POST["confirmPassword"]) {
        echo "<p>Passwords do not match</p>";
        return;
    }

    $email = sanitise($_POST["email"]);
    $name = sanitise($_POST["name"]);
    $password = sanitise($_POST["password"]);
    $date = date("Y-m-d");
    $SQLstring = "INSERT INTO friends (friend_email, password, profile_name, date_started)
        VALUES ('$email', '$password', '$name', '$date')";

    // Create mysqli connection object
    $connection = new mysqli($host, $user, $pswd, $dbnm);
    // check connection
    if($connection -> connect_errno) {
        die("<p>Database server unavailable: " . $connection->connect_error . "</p>");
    }
    // set database
    $connection -> select_db($dbnm)
        or die("<p>Database is cooked</p>");
    echo "test";
    // perform query
    if ($connection -> query($SQLstring) === TRUE) {
        echo "<p>Record added successfully</p>";
    } else {
        echo "<p>Failed to add record</p>";
    }
    
    $connection -> close();
}
?>

<footer>
    <?php include ("functions/nav.inc.php"); ?>
</footer>
</body>
</html>