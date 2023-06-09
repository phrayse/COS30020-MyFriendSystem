<?php
class Signup
{
    private $db;

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
        $connection = $this->db->setConnection();
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

    public function uniqueUsername($name)
    {
        $connection = $this->db->setConnection();
        $SQLstring = "SELECT * FROM friends WHERE profile_name = '$name'";
        $result = $connection->query($SQLstring);
        if ($result->num_rows > 0) {
            $this->db->closeConnection();
            echo "<p>Profile name already in use</p>";
            return false;
        }
        $this->db->closeConnection();
        return true;
    }

    public function register($email, $name, $password)
    {
        $connection = $this->db->setConnection();
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
}