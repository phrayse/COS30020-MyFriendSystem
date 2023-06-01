<?php require ("settings.inc.php");
// Connect to database.
$connection = @mysqli_connect($host, $user, $pswd, $dbnm)
    or die("<p>The database server is not available.</p>");
@mysqli_select_db($connection, $dbnm)
    or die("<p>The database is not available.</p>");

$tableFriends = "friends";
$tableMyFriends = "myfriends";

// FRIENDS TABLE:
// SQL query to create Friends table if it doesn't yet exist.
$queryFriends = "CREATE TABLE IF NOT EXISTS $tableFriends (
    friend_id int NOT NULL AUTO_INCREMENT,
    friend_email varchar(50) NOT NULL UNIQUE,
    password varchar(20) NOT NULL,
    profile_name varchar(30) NOT NULL,
    date_started date NOT NULL,
    num_of_friends int UNSIGNED,
    PRIMARY KEY(friend_id)
)";

// Execute table creation query.
$resultFriends = @mysqli_query($connection, $queryFriends);
if ($resultFriends) {
    echo "<p>Friends table operational.</p>";
} else {
    echo "<p>Error establishing Friends table - " . mysqli_error($connection) . "</p>";
}

// If table is empty, populate.
$SQLstring = "SELECT * FROM $tableFriends";
$queryResult = @mysqli_query($connection, $SQLstring);
if (mysqli_num_rows($queryResult) == 0) {
    echo "<p>Adding default Friends table data</p>";
    $insertFriends = "INSERT INTO $tableFriends (friend_email, password, profile_name, date_started)
        VALUES ('sleve@email.com', 'pwsleve', 'Sleve McDichael', '2023-01-05'),
        ('onson@email.com', 'pwonson', 'Onson Sweemey', '2023-01-05'),
        ('darryl@email.com', 'pwdarryl', 'Darryl Archideld', '2023-01-05'),
        ('anatoli@email.com', 'pwanatoli', 'Anatoli Smorin', '2023-01-05'),
        ('rey@email.com', 'pwrey', 'Rey McSriff', '2023-01-05'),
        ('glenallen@email.com', 'pwglenallen', 'Glenallen Mixon', '2023-01-05'),
        ('mario@email.com', 'pwmario', 'Mario McRlwain', '2023-01-05'),
        ('raul@email.com', 'pwraul', 'Raul Chamgerlain', '2023-01-05'),
        ('bobson@email.com', 'pwbobson', 'Bobson Dugnutt', '2023-01-05'),
        ('willie@email.com', 'pwwillie', 'Willie Dustice', '2023-01-05')";

    $resultInsertFriends = @mysqli_query($connection, $insertFriends);
    if ($resultInsertFriends) {
        echo "<p>Friends table populated.</p>";
    } else {
        echo "<p>Error populating Friends table - " . mysqli_error($connection) . "</p>";
    }
} else {
    echo "<p>Friends table has existing data.</p>";
}

// MY FRIENDS TABLE:
// SQL query for the MyFriends table.
$queryMyFriends = "CREATE TABLE IF NOT EXISTS $tableMyFriends (
    friend_id1 int NOT NULL,
    friend_id2 int NOT NULL,
    FOREIGN KEY (friend_id1) REFERENCES $tableFriends(friend_id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (friend_id2) REFERENCES $tableFriends(friend_id) ON UPDATE CASCADE ON DELETE CASCADE,
    CHECK (friend_id1 != friend_id2)
)";

// Execute the MyFriends table query.
$resultMyFriends = @mysqli_query($connection, $queryMyFriends);
if ($resultMyFriends) {
    echo "<p>MyFriends table operational.</p>";
} else {
    echo "<p>Error establishing MyFriends table - " . mysqli_error($connection) . "</p>";
}

// If table is empty, populate.
$SQLstring = "SELECT * FROM $tableMyFriends";
$queryResult = @mysqli_query($connection, $SQLstring);
if (mysqli_num_rows($queryResult) == 0) {
    $insertMyFriends = "INSERT INTO $tableMyFriends (friend_id1, friend_id2)
        VALUES (1,2), (1,3), (2,3), (2,4), (3,4),
        (3,5), (4,5), (4,6), (5,6), (5,7),
        (6,7), (6,8), (7,8), (7,9), (8,9),
        (8,10), (9,10), (9,1), (10,1), (10,2)";
    // Execute $insertMyFriends
    $resultInsertMyFriends = @mysqli_query($connection, $insertMyFriends);
    if ($resultInsertMyFriends) {
        echo "<p>MyFriends table populated.</p>";
    } else {
        echo "<p>Error populating MyFriends table - " . mysqli_error($connection) . "</p>";
    }
} else {
    echo "<p>MyFriends table has existing data.</p>";
}

// close the database connection.
mysqli_close($connection);
?>