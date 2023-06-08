<?php include ("functions/db.class.php");
include ("functions/accountmanager.class.php");
include ("functions/sanitise.inc.php");
$db = new DB(); $accountManager = new AccountManager($db);
include_once ("functions/header.inc.php"); ?>
<main>
    <?php
    if (isset($_SESSION["name"])) {
        $accountManager->getFriendCount();
        $accountManager->displayFriendTable();

        if (isset($_POST["unfriend"])) {
            $friendID1 = $_POST["friendID1"];
            $friendID2 = $_POST["friendID2"];
            $accountManager->removeFriend($friendID1, $friendID2);
        }
    } else {
        // not logged in, show nothing.
    }
    ?>
</main>
<?php include ("functions/nav.inc.php"); ?>