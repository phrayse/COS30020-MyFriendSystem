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
        if (!$result || mysqli_num_rows($result) == 0) {
            $this->db->closeConnection();
            echo "<p>Login failed</p>";
            return false;
        }
        while ($row = mysqli_fetch_assoc($result)) {
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
        echo $_SESSION["name"] . ", you have " . mysqli_num_rows($result) . " friends";
        $this->db->closeConnection();
    }

    public function getFriendName($friendID)
    {
        $connection = $this->db->getNewConnection();
        $SQLstring = "SELECT profile_name FROM friends WHERE friend_id = '$friendID'";
        $result = $connection->query($SQLstring);
        $row = mysqli_fetch_assoc($result);
        return $row['profile_name'];
    }

    public function displayFriendTable()
    {
        $connection = $this->db->getNewConnection();
        $id = $_SESSION["id"];

        // For each record in the myfriends table for the current user, get the associated profile name of the other user.
        $SQLstring =  "SELECT friends.friend_id, friends.profile_name
                        FROM myfriends
                        INNER JOIN friends ON (myfriends.friend_id1 = friends.friend_id OR myfriends.friend_id2 = friends.friend_id)
                        WHERE (myfriends.friend_id1 = '$id' OR myfriends.friend_id2 = '$id') AND friends.friend_id != '$id'
                        ORDER BY friends.profile_name ASC";
        $result = $connection->query($SQLstring);
        echo "<table border=\"2px\">";
        while ($row = mysqli_fetch_assoc($result)) {
            $friendID = $row["friend_id"];
            $friendName = $row["profile_name"];
            echo "<tr><td>$friendName</td><td>
                    <form method=\"POST\"><input type=\"hidden\" name=\"friendID1\" value=\"{$friendID}\">
                        <input type=\"hidden\" name=\"friendID2\" value=\"{$id}\">
                        <button type=\"submit\" name=\"unfriend\" onclick=\"return confirm('Are you sure?')\">Unfriend</button>
                    </form>
                </td></tr>";
        }
        echo "</table>";
        $this->db->closeConnection();
    }
    
    public function removeFriend($friendID1, $friendID2)
    {
        $connection = $this->db->getNewConnection();
        $SQLstring = "DELETE FROM myfriends where (friend_id1 = '$friendID1' AND friend_id2 = '$friendID2') OR (friend_id1 = '$friendID2' AND friend_id2 = '$friendID1')";
        $result = $connection->query($SQLstring);
        if (!$result) {
            // failed to remove
            echo "<p>Failed to remove connection.</p>";
            exit();
        } else {
            // friendship removed
            echo "<p>Friendship removed</p>";
            header("refresh:0");
            exit();
        }
    }
}