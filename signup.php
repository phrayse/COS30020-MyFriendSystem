<?php require ("functions/settings.inc.php");
require ("functions/signup.class.php");
require ("functions/accountmanager.class.php");
require ("functions/db.class.php");
include ("functions/sanitise.inc.php");
?>
<!-- create database object, then pass it to signup and accountManager objects -->
<?php $db = new DB(); $signup = new Signup($db); $accountManager = new AccountManager($db); ?>
<?php include_once ("functions/header.inc.php"); ?>
<main>
<strong>Registration</strong>
    <form action="signup.php" method="post">
        <p><label>Email:<input type="email" name="email" <?php echo isset($_POST["email"]) ? "value=\"" . $_POST["email"] . "\" " : ""; ?>autofocus required /></label>
        <br><label>Profile Name:<input type="text" name="name" <?php echo isset($_POST["name"]) ? "value=\"" . $_POST["name"] . "\" " : ""; ?>required /></label>
        <br><label>Password:<input type="password" name="password" minlength="1" required /></label>
        <br><label>Confirm Password:<input type="password" name="confirmPassword" required /></label>
        <br><input type="submit" value="Register" />
        <input type="reset" value="Clear" />
        </p>
    </form>
   
    <?php // can probably put the inside of the isset block as a function
    if (isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["password"])) {
        // Form data exists, do actions.
        if ($_POST["password"] != $_POST["confirmPassword"]) {
            echo "<p>Passwords do not match</p>";
            return;
        }
        $email = sanitise($_POST["email"]);
        $name = sanitise($_POST["name"]);
        $password = sanitise($_POST["password"]);
        $date = date("Y-m-d");
        // Check name and password against regex
        if (!$signup->regex($name, $password)) {
            return;
        }
        // Check whether email exists in the database
        if (!$signup->uniqueEmail($email)) {
            return;
        }
        // Check whether profile name exists in the database
        if (!$signup->uniqueUsername($name)) {
            return;
        }
        // email is unique, so register
        if (!$signup->register($email, $name, $password)) {
            return;
        }
        // registration successful, login
        if (!$accountManager->login($email, $password)) {
            return;
        }
        header("location: friendadd.php");
    }
    ?>
</main>
<?php include_once ("functions/nav.inc.php"); ?>