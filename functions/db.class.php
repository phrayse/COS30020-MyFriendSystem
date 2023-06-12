<?php
class DB
{
    private $host;
    private $user;
    private $pswd;
    private $dbnm;
    private $connection;

    // Constructor - grab details from settings.inc.php
    public function __construct()
    {
        include ("functions/settings.inc.php");
        $this->host = $host;
        $this->user = $user;
        $this->pswd = $pswd;
        $this->dbnm = $dbnm;
    }

    // Set database connection
    public function setConnection()
    {
        $this->connection = new mysqli($this->host, $this->user, $this->pswd, $this->dbnm);
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
        return $this->connection;
    }
    // Close database connection
    public function closeConnection()
    {
        if ($this->connection) {
            $this->connection->close();
        }
    }

    // Index page function. Returns bool
    public function createDatabase()
    {
        $this->setConnection();
        // throws an error with a description if any step of the creation or population fails.
        // it's gonna be the CREATE TRIGGERs.
        try {
            // create 'friends' and 'myfriends' tables if not exists
            $SQLstring = "CREATE TABLE IF NOT EXISTS friends (
                friend_id int NOT NULL AUTO_INCREMENT,
                friend_email varchar(50) NOT NULL UNIQUE,
                password varchar(20) NOT NULL,
                profile_name varchar(30) NOT NULL,
                date_started date NOT NULL,
                num_of_friends int UNSIGNED DEFAULT '0',
                PRIMARY KEY(friend_id)
                )";
            if (!$this->connection->query($SQLstring)) {
                throw new Exception("Failed to create 'friends' table: " . $this->connection->error);
            }

            $SQLstring = "CREATE TABLE IF NOT EXISTS myfriends (
                friend_id1 int NOT NULL,
                friend_id2 int NOT NULL,
                FOREIGN KEY (friend_id1) REFERENCES friends(friend_id) ON UPDATE CASCADE ON DELETE CASCADE,
                FOREIGN KEY (friend_id2) REFERENCES friends(friend_id) ON UPDATE CASCADE ON DELETE CASCADE,
                CHECK (friend_id1 != friend_id2)
                )";
            if (!$this->connection->query($SQLstring)) {
                throw new Exception("Failed to create 'myfriends' table: " . $this->connection->error);
            }

            // need permissions to use TRIGGERs
            // increase/decrease num_of_friends when a connection is made/removed.
            $SQLstring = "CREATE TRIGGER increment_friends AFTER INSERT ON myfriends FOR EACH ROW
                BEGIN
                    UPDATE friends
                    SET num_of_friends = num_of_friends + 1
                    WHERE friend_id = NEW.friend_id1;

                    UPDATE friends
                    SET num_of_friends = num_of_friends + 1
                    WHERE friend_id = NEW.friend_id2;
                END";
            if (!$this->connection->query($SQLstring)) {
               throw new Exception("Failed to create 'increment_friends' trigger: " . $this->connection->error);
            }

            $SQLstring = "CREATE TRIGGER decrement_friends AFTER DELETE ON myfriends FOR EACH ROW
                BEGIN
                    UPDATE friends
                    SET num_of_friends = num_of_friends - 1
                    WHERE friend_id = OLD.friend_id1;

                    UPDATE friends
                    SET num_of_friends = num_of_friends - 1
                    WHERE friend_id = OLD.friend_id2;
                END";
            if (!$this->connection->query($SQLstring)) {
                throw new Exception("Failed to create 'decrement_friends' trigger: " . $this->connection->error);
            }

            // populate tables if empty
            $SQLstring = "SELECT * FROM friends";
            if ($this->connection->query($SQLstring)->num_rows == 0) {
                $SQLstring = "INSERT INTO friends (friend_email, password, profile_name, date_started)
                VALUES ('sleve@email.com', 'pwsleve', 'Sleve McDichael', '2023-04-20'),
                ('onson@email.com', 'pwonson', 'Onson Sweemey', '2023-04-20'),
                ('darryl@email.com', 'pwdarryl', 'Darryl Archideld', '2023-04-20'),
                ('anatoli@email.com', 'pwanatoli', 'Anatoli Smorin', '2023-04-20'),
                ('rey@email.com', 'pwrey', 'Rey McSriff', '2023-04-20'),
                ('glenallen@email.com', 'pwglenallen', 'Glenallen Mixon', '2023-04-20'),
                ('mario@email.com', 'pwmario', 'Mario McRlwain', '2023-04-20'),
                ('raul@email.com', 'pwraul', 'Raul Chamgerlain', '2023-04-20'),
                ('bobson@email.com', 'pwbobson', 'Bobson Dugnutt', '2023-04-20'),
                ('willie@email.com', 'pwwillie', 'Willie Dustice', '2023-04-20')";
                if (!$this->connection->query($SQLstring)) {
                    throw new Exception("Failed to populate 'friends' table: " . $this->connection->error);
                }
            }

            $SQLstring = "SELECT * FROM myfriends";
            if ($this->connection->query($SQLstring)->num_rows == 0) {
                $SQLstring = "INSERT INTO myfriends (friend_id1, friend_id2)
                    VALUES (1,2), (1,3), (2,3), (2,4), (3,4),
                    (3,5), (4,5), (4,6), (5,6), (5,7),
                    (6,7), (6,8), (7,8), (7,9), (8,9),
                    (8,10), (9,10), (9,1), (10,1), (10,2)
                ";
                if (!$this->connection->query($SQLstring)) {
                    throw new Exception("Failed to populate 'myfriends' table: " . $this->connection->error);
                }
            }
            $this->closeConnection();
            return true;
        } catch (Exception $e) {
            echo "<p>Database construction failed: " . $e->getMessage();
            $this->closeConnection();
            return false;
        }
    }
}