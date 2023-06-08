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

        // gotta grab from myfriends table where either field matches ID
        // from there, find each profile_id that matches and print the name.
        $SQLstring = "SELECT * FROM myfriends WHERE (friend_id1 = '$id' OR friend_id2 = '$id')";
        $result = $connection->query($SQLstring);
        echo "<table border=\"2px\">";
        while ($row = mysqli_fetch_assoc($result)) {
            $friendID1 = $row["friend_id1"];
            $friendID2 = $row["friend_id2"];
            $friendName1 = $this->getFriendName($friendID1);
            $friendName2 = $this->getFriendName($friendID2);

            if ($friendID1 == $_SESSION["id"]) {
                echo "<tr><td>{$friendName2}</td>
                <td>
                    <form method=\"POST\"><input type=\"hidden\" name=\"friendID1\" value=\"{$friendID1}\">
                        <input type=\"hidden\" name=\"friendID2\" value=\"{$friendID2}\">
                        <button type=\"submit\" name=\"unfriend\" onclick=\"return confirm('Are you sure?')\">Unfriend</button>
                    </form>
                </td></tr>";
            }
            if ($friendID2 == $_SESSION["id"]) {
                echo "<tr><td>{$friendName1}</td>
                <td>
                    <form method=\"POST\"><input type=\"hidden\" name=\"friendID1\" value=\"{$friendID1}\">
                        <input type=\"hidden\" name=\"friendID2\" value=\"{$friendID2}\">
                        <button type=\"submit\" name=\"unfriend\" onclick=\"return confirm('Are you sure?')\">Unfriend</button>
                    </form>
                </td></tr>";
            }

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
            return false;
        } else {
            // friendship removed
            // p sure this is where I need to add an sql query that subtracts one from each user's num_of_friends value
            // unless the problematic CREATE TRIGGER decrement_friends bit starts working
            return true;
        }
    }
}