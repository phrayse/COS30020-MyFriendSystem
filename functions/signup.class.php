<?php
class Signup
{
    private $db;
    private $userID;

    // Create Signup object with passed in database object.
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function regex($name, $password)
    {
        if (!preg_match("/^[a-zA-Z ]+$/", $name)) {
            echo "<p>Profile name must contain only letters and spaces</p>";
            return false;
        }
        if (!preg_match("/^[a-zA-Z0-9]+$/", $password)) {
            echo "<p>Password must contain only letters and numbers<?p>";
            return false;
        }
        return true;
    }

    public function uniqueEmail($email)
    {
        $connection = $this->db->getNewConnection();
        $SQLstring = "SELECT * FROM friends WHERE friend_email = '$email'";
        $result = $connection->query($SQLstring);
        if ($result->num_rows > 0) {
            $this->db->closeConnection();
            echo "<p>Email already in use</p>";
            return false;
        }
        $this->db->closeConnection();
        return true;
    }

    public function register($email, $name, $password)
    {
        $connection = $this->db->getNewConnection();
        $date = date("Y-m-d");
        $SQLstring = "INSERT INTO friends (friend_email, password, profile_name, date_started)
            VALUES ('$email', '$password', '$name', '$date')";
        if (!$connection->query($SQLstring)) {
            $this->db->closeConnection();
            echo "<p>Registration failed</p>";
            return false;
        }
        $this->db->closeConnection();
        return true;
    }

    public function login($email, $password)
    {
        // Details have been added successfully, so now grab the new records and log in
        $connection = $this->db->getNewConnection();
        $SQLstring = "SELECT friend_id FROM friends WHERE friend_email = '$email' AND password = '$password'";
        $result = $connection->query($SQLstring);
        if (!$result) {
            $this->db->closeConnection();
            echo "<p>Login failed</p>";
            return false;
        }
        while ($row = mysqli_fetch_assoc($result)) {
            $this->userID = $row["friend_id"];
        }
        
        $this->db->closeConnection();
        echo "<p>Login successful. User ID is $this->userID</p>";
        return true;
    }
}