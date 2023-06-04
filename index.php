<?php require ("functions/db.class.php"); ?>
<?php include_once ("functions/header.inc.php"); ?>
<main>
    <p>Name: ##########
    <br>Email: #########@###
    <br>Student ID: #########
    <br>I declare that this assignment is my individual work. I have not worked collaboratively nor copied from any other student's work or from any other source.
    </p>

    <?php
    // Create a new instance of database object, then call createDatabase method.
    $db = new DB();
    if ($db->createDatabase()) {
        echo "<p>Tables created and populated successfully</p>";
    } else {
        echo "<p>Error initialising tables</p>";
    }
    ?>
</main>
<?php include_once ("functions/nav.inc.php"); ?>