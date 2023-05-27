<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="author" content="STUID" />
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
    <!-- Arbitrarily assigning a minimum length of 3 to the password field so it can't be left blank somehow -->
    <br><label>Password:<input type="password" name="password" minlength="3" required /></label>
    <br><label>Confirm Password:<input type="password" name="confirmPassword" required /></label>
    <br><input type="submit" value="Register" />
    <input type="reset" value="Clear" />
    </p>
</form>

<?php
if (isset($_POST["email"]) && isset($_POST["name"])) {
    // Form data exists, do actions.
    if ($_POST["password"] != $_POST["confirmPassword"]) {
        echo "<p>Passwords do not match</p>";
        return;
    }

    // If we've reached this point, passwords match and fields are filled.
    // Do I bother checking for no duplicate emails or nah.
    $email = $_POST["email"];
    $name = $_POST["name"];
    $password = $_POST["password"];
    $date = date("Y-m-d");
    $SQLstring = "INSERT INTO friends (friend_email, password, profile_name, date_started)
        VALUES ($email, $password, $name, $date)";
}
?>

<footer>
    <?php include ("functions/nav.inc.php"); ?>
</footer>
</body>
</html>