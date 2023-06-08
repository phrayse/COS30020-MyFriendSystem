<?php include ("functions/db.class.php");
include ("functions/accountmanager.class.php");
include ("functions/sanitise.inc.php");
$db = new DB(); $accountManager = new AccountManager($db);
include_once ("functions/header.inc.php"); ?>
<main>
    <?php
    if (isset($_SESSION["name"])) {
        /*
        need a list of current friends, arranged by profile_name, with a button to remove friend.
        */
        $accountManager->getFriendCount();
        // this is dumb rn need to display everything properly
        $accountManager->displayFriendTable();

        if (isset($_POST["unfriend"])) {
            $friendID1 = $_POST["friendID1"];
            $friendID2 = $_POST["friendID2"];
            $result = $accountManager->removeFriend($friendID1, $friendID2);
            if ($result) {
                echo "<p>Friendship removed</p>";
                // think this doesn't proper refresh the page, so the removed friend is still showing for one more reload
                header("location: .");
            } else {
                echo "<p>Failed to remove friendship</p>";
            }
        }
    } else {
        // not logged in, show nothing.
    }
    ?>
</main>
<?php include ("functions/nav.inc.php"); ?>