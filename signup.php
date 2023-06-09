<?php
require ("functions/handler.class.php");
?>
<!-- create database object, then pass it to signup and accountManager objects -->
<?php $db = new DB(); $signup = new Signup($db); $accountManager = new AccountManager($db); ?>
<?php include ("functions/header.inc.php"); ?>
<main>
    <strong>Registration</strong>
    <form action="signup.php" method="post">
        <p><label for="email">Email: <input type="email" id="email" name="email" <?php echo isset($_POST["email"]) ? "value=\"" . $_POST["email"] . "\" " : ""; ?>autofocus required /></label>
        <br><label for="name">Profile Name: <input type="text" id="name" name="name" <?php echo isset($_POST["name"]) ? "value=\"" . $_POST["name"] . "\" " : ""; ?>required pattern="^[a-zA-Z ]+$" /></label>
        <br><label for="password">Password: <input type="password" id="password" name="password" minlength="1" required pattern="^[a-zA-Z0-9]+$" /></label>
        <br><label for="confirmPassword">Confirm Password: <input type="password" id="confirmPassword" name="confirmPassword" required pattern="^[a-zA-Z0-9]+$" /></label>
        <br><input type="submit" value="Register" />
        <input type="reset" value="Clear" />
        </p>
    </form>
   
    <?php
    if (isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["password"])) {
        // Form data exists, do actions.
        $flag = true;
        if ($_POST["password"] != $_POST["confirmPassword"]) {
            echo "<p>Passwords do not match</p>";
            $flag = false;
        }
        // Check email's of valid formatting
        if ($flag) {
            $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                echo "<p>Invalid email address</p>";
                $flag = false;
            } else {
                $email = sanitise($email);
                $name = sanitise($_POST["name"]);
                $password = sanitise($_POST["password"]);
                $date = date("Y-m-d");
            }
        }
        // Check name and password against regex
        if ($flag && !$signup->regex($name, $password)) {
            $flag = false;
        }
        // Check whether email exists in the database
        if ($flag && !$signup->uniqueEmail($email)) {
            $flag = false;
        }
        // Check whether profile name exists in the database
        if ($flag && !$signup->uniqueUsername($name)) {
            $flag = false;
        }
        // email is unique, so register
        if ($flag && !$signup->register($email, $name, $password)) {
            $flag = false;
        }
        // registration successful, login
        if ($flag && !$accountManager->login($email, $password)) {
            $flag = false;
        }
        if ($flag) {
            header("location: friendadd.php");
            exit();
        }
    }
    ?>
</main>
<?php include ("functions/footer.inc.php"); ?>