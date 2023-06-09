<?php
include ("functions/handler.class.php");
$db = new DB(); $accountManager = new AccountManager($db);
include ("functions/header.inc.php"); ?>
<main>
    <?php
    // if session is set, we're already logged in.
    if(!isset($_SESSION["name"])) {
        // if both fields are filled, do
        if(isset($_POST["email"]) && isset($_POST["password"])) {
            $email = sanitise($_POST["email"]);
            $password = sanitise($_POST["password"]);
            if ($accountManager->login($email, $password)) {
                header("location: friendlist.php");
            }
        }
    ?>
        <strong>Log in</strong>
        <form action="login.php" method="post">
            <p><label for="email">Email:<input type="email" id="email" name="email" <?php echo isset($_POST["email"]) ? "value=\"" . $_POST["email"] . "\" " : ""; ?>autofocus required /></label>
            <br><label for="password">Password:<input type="password" id="password" name="password" required /></label>
            <br><input type="submit" value="Log in" />
            <input type="reset" value="Clear" />
            </p>
        </form>
    <?php
    } else {
        echo "<p>You are already logged in!</p>";
    }
    ?>
</main>
<?php include ("functions/footer.inc.php"); ?>