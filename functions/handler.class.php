<?php
include "db.class.php";
include "signup.class.php";
include "accountmanager.class.php";
function sanitise($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}