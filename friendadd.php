<?php
include ("functions/handler.class.php");
$db = new DB(); $accountManager = new AccountManager($db);
include ("functions/header.inc.php"); ?>
<main>
    <?php
    if (isset($_SESSION["name"])) {
        $accountManager->getFriendCount();
        $accountManager->displayEnemyTable();
        if (isset($_POST["addFriend"])) {
            $enemyID = $_POST["enemyID"];
            $accountManager->addFriend($enemyID);
        }
    } else {
        echo "<p>You must be logged in to add friends</p>";
    }
    ?>
</main>
<?php include ("functions/footer.inc.php"); ?>