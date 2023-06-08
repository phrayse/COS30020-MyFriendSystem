<?php
class AccountManager
{
    private $db;

    // Create Signup object with passed in database object.
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login($email, $password)
    {
        $connection = $this->db->getNewConnection();
        $SQLstring = "SELECT friend_id, profile_name FROM friends WHERE friend_email = '$email' AND password = '$password'";
        $result = $connection->query($SQLstring);
        if (!$result || $result->num_rows == 0) {
            $this->db->closeConnection();
            echo "<p>Login failed</p>";
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            // Login successful, set session variables.
            $_SESSION["email"] = $email;
            $_SESSION["name"] = $row["profile_name"];
            $_SESSION["id"] = $row["friend_id"];
            $name = $_SESSION["name"];
            echo "<p>Login successful. Welcome, $name.</p>";
        }
        $this->db->closeConnection();
        return true;
    }

    public function getFriendCount()
    {
        $connection = $this->db->getNewConnection();
        $id = $_SESSION["id"];
        $SQLstring = "SELECT * FROM myfriends WHERE friend_id1 = '$id' OR friend_id2 = '$id'";
        $result = $connection->query($SQLstring);
        echo $_SESSION["name"] . ", you have " . $result->num_rows . " friends";
        $this->db->closeConnection();
    }

    public function displayFriendTable()
    {
        $connection = $this->db->getNewConnection();
        $id = $_SESSION["id"];

        // Find all records on myfriends table that include user.
        // Print name of each friend with an unfriend button.
        $SQLstring =  "SELECT friends.friend_id, friends.profile_name
                        FROM myfriends
                        INNER JOIN friends ON (myfriends.friend_id1 = friends.friend_id OR myfriends.friend_id2 = friends.friend_id)
                        WHERE (myfriends.friend_id1 = '$id' OR myfriends.friend_id2 = '$id') AND friends.friend_id != '$id'
                        ORDER BY friends.profile_name ASC";
        $result = $connection->query($SQLstring);
        echo "<table border=\"2px\">";
        while ($row = $result->fetch_assoc()) {
            $friendName = $row["profile_name"];
            $friendID = $row["friend_id"];
            echo "<tr><td>$friendName</td><td>
                    <form method=\"POST\"><input type=\"hidden\" name=\"friendID\" value=\"{$friendID}\">
                        <input type=\"hidden\" name=\"user\" value=\"{$id}\">
                        <button type=\"submit\" name=\"unfriend\" onclick=\"return confirm('Are you sure?')\">Unfriend</button>
                    </form>
                </td></tr>";
        }
        echo "</table>";
        $this->db->closeConnection();
    }
    
    public function removeFriend($friendID)
    {
        $connection = $this->db->getNewConnection();
        $id = $_SESSION["id"];
        $SQLstring = "DELETE FROM myfriends where (friend_id1 = '$friendID' AND friend_id2 = '$id') OR (friend_id1 = '$id' AND friend_id2 = '$friendID')";
        $result = $connection->query($SQLstring);
        if (!$result) {
            // failed to remove
            echo "<p>Failed to remove connection.</p>";
            exit();
        } else {
            // friendship removed
            header("refresh:0");
            exit();
        }
    }

    public function displayEnemyTable() {
        $connection = $this->db->getNewConnection();
        $id = $_SESSION["id"];
        // TODO:
            // Pagination - the LIMIT 0,5 is the right start, but I think the 0 should be a variable that can increment by 5 each page
            // Mutual friend count. sql hard.
        $SQLstring =  "SELECT friends.friend_id, friends.profile_name
                        FROM friends
                        LEFT JOIN myfriends ON (myfriends.friend_id1 = friends.friend_id OR myfriends.friend_id2 = friends.friend_id)
                            AND (myfriends.friend_id1 = '$id' OR myfriends.friend_id2 = '$id')
                        WHERE myfriends.friend_id1 IS NULL AND myfriends.friend_id2 IS NULL AND friends.friend_id != '$id'
                        ORDER BY friends.profile_name ASC
                        LIMIT 0, 5";
        $result = $connection->query($SQLstring);
        echo "<table border=\"2px\">";
        while ($row = $result->fetch_assoc()) {
            $enemyName = $row["profile_name"];
            $enemyID = $row["friend_id"];
            echo "<tr><td>$enemyName</td><td>
                    <form method=\"POST\"><input type=\"hidden\" name=\"enemyID\" value=\"{$enemyID}\">
                        <input type=\"hidden\" name=\"user\" value=\"{$id}\">
                        <button type=\"submit\" name=\"addFriend\" onclick=\"return confirm('Are you sure?')\">Add friend</button>
                    </form>
                </td></tr>";
        }
        echo "</table>";
        $this->db->closeConnection();
    }

    public function addFriend($enemyID)
    {
        $connection = $this->db->getNewConnection();
        $id = $_SESSION["id"];
        $SQLstring = "INSERT INTO myfriends VALUES ($id, $enemyID)";
        $result = $connection->query($SQLstring);
        if (!$result) {
            // failed to add friend
            echo "<p>Failed to create connection.</p>";
            exit();
        } else {
            // friendship created
            header("refresh:0");
            exit();
        }
    }
}