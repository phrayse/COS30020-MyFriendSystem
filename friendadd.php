<?php include ("functions/db.class.php");
include ("functions/accountmanager.class.php");
include ("functions/sanitise.inc.php");
$db = new DB(); $accountManager = new AccountManager($db);
include_once ("functions/header.inc.php"); ?>
<main>
    <?php
    if (isset($_SESSION["name"])) {
        /*
        need a list of all registered users, barring current friends.
        need a button to add them to friend list, which will remove them from current list and update friend count
        pagination too
        */
    } else {
        // not logged in, show nothing.
    }
    ?>
</main>
<?php include ("functions/nav.inc.php"); ?>