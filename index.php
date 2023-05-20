<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
    <meta name="author" content="NAME STUID" />
    <link href="style.css" rel="stylesheet" />
    <title>Assignment 3: Index</title>
</head>
<body>
    <header>
        <?php include ("functions/header.inc"); ?>
    </header>

    <main>
        <p>Name: ##########
        <br>Email: #########@###
        <br>Student ID: #########
        </p>
        <p>I declare that this assignment is my individual work.
            I have not worked collaboratively nor copied from any other student's work or from any other source.
        </p>

        <?php

        /* TODO:
            create settings.php for the database connection stuff.
            probably get all of this stuff off the index page and put into a separate function page.
            confirm the foreign keys and CHECK <> are all good in myfriends table
        */

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
            friend_email varchar(50) NOT NULL,
            password varchar(20) NOT NULL,
            profile_name varchar(30) NOT NULL,
            date_started date NOT NULL,
            num_of_friends int UNSIGNED,
            PRIMARY KEY(friend_id)
        )";

        // Execute table creation query.
        $resultFriends = @mysqli_query($connection, $queryFriends);
        if ($resultFriends) {
            echo "Friends table operational.";
        } else {
            echo "Error establishing Friends table - " . mysqli_error($connection);
        }

        // If table is empty, populate.
        if (mysqli_num_rows($resultFriends) < 1) {
            echo "Adding default Friends table data";
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
                echo "Friends table populated.";
            } else {
                echo "Error populating Friends table - " . mysqli_error($connection);
            }
        }


        // MY FRIENDS TABLE:
        // SQL query for the MyFriends table.
        $queryMyFriends = "CREATE TABLE IF NOT EXISTS $tableMyFriends (
            friend_id1 int NOT NULL,
            friend_id2 int NOT NULL,
            FOREIGN KEY (friend_id1) REFERENCES $tableFriends(friend_id),
            FOREIGN KEY (friend_id2) REFERENCES $tableFriends(friend_id),
            CHECK (friend_id1 <> friend_id2)
        )";

        // Execute the MyFriends table query.
        $resultMyFriends = @mysqli_query($connection, $queryMyFriends);
        if ($resultMyFriends) {
            echo "MyFriends table operational.";
        } else {
            echo "Error establishing MyFriends table - " . mysqli_error($connection);
        }

        // If table is empty, populate.
        if (mysqli_num_rows($resultMyFriends) < 1) {
            $insertMyFriends = "INSERT INTO myfriends (friend_id1, friend_id2)
                VALUES (0,1),
                (0,2),
                (0,3),
                (0,4),
                (0,5),
                (1,2),
                (1,3),
                (1,4),
                (1,5),
                (1,6),
                (2,3),
                (2,4),
                (2,5),
                (2,6),
                (2,7),
                (3,4),
                (3,5),
                (3,6),
                (3,7),
                (3,8)";

            // Execute $insertMyFriends
            $resultInsertMyFriends = @mysqli_query($connection, $insertMyFriends);
            if ($resultInsertMyFriends) {
                echo "MyFriends table populated.";
            } else {
                echo "Error populating MyFriends table - " . mysqli_error($connection);
            }
        }

        // close the database connection.
        mysqli_close($connection);
        ?>
    </main>

   <footer>
        <?php include ("functions/nav.inc"); ?>
   </footer>
</body>
</html>