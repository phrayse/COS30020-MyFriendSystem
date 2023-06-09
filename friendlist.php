<?php
include ("functions/handler.class.php");
$db = new DB(); $accountManager = new AccountManager($db);
include ("functions/header.inc.php"); ?>
<main>
    <?php
    if (isset($_SESSION["name"])) {
        $accountManager->getFriendCount();
        $accountManager->displayFriendTable();
        if (isset($_POST["unfriend"])) {
            $friendID = $_POST["friendID"];
            $accountManager->removeFriend($friendID);
        }
    } else {
        echo "<p>You must be logged in to see your friends</p>";
    }
    ?>
</main>
<?php include ("functions/footer.inc.php"); ?>