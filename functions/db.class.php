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

    // Set database
    public function setConnection()
    {
        $this->connection = mysqli_connect($this->host, $this->user, $this->pswd, $this->dbnm);
    }
    // Get database
    public function getConnection()
    {
        return $this->connection;
    }
    // Set & get database
    public function getNewConnection()
    {
        $this->setConnection();
        return $this->connection;
    }
    // Close database
    public function closeConnection()
    {
        if ($this->connection) {
            mysqli_close($this->connection);
        }
    }

    // Index page function. Returns bool
    public function createDatabase()
    {
        $this->setConnection();

        // SQL to create both tables then populate
        $SQLstring = "CREATE TABLE IF NOT EXISTS friends (
            friend_id int NOT NULL AUTO_INCREMENT,
            friend_email varchar(50) NOT NULL UNIQUE,
            password varchar(20) NOT NULL,
            profile_name varchar(30) NOT NULL,
            date_started date NOT NULL,
            num_of_friends int UNSIGNED DEFAULT '0',
            PRIMARY KEY(friend_id)
            );

            CREATE TABLE IF NOT EXISTS myfriends (
            friend_id1 int NOT NULL,
            friend_id2 int NOT NULL,
            FOREIGN KEY (friend_id1) REFERENCES friends(friend_id) ON UPDATE CASCADE ON DELETE CASCADE,
            FOREIGN KEY (friend_id2) REFERENCES friends(friend_id) ON UPDATE CASCADE ON DELETE CASCADE,
            CHECK (friend_id1 != friend_id2)
            );

            CREATE TRIGGER increment_friends AFTER INSERT ON myfriends FOR EACH ROW
            BEGIN
                UPDATE friends
                SET num_of_friends = num_of_friends + 1
                WHERE friend_id = NEW.friend_id1;

                UPDATE friends
                SET num_of_friends = num_of_friends + 1
                WHERE friend_id = NEW.friend_id2;
            END;

            CREATE TRIGGER decrement_friends AFTER DELETE ON myfriends FOR EACH ROW
            BEGIN
                UPDATE friends
                SET num_of_friends = num_of_friends - 1
                WHERE friend_id = OLD.friend_id1;

                UPDATE friends
                SET num_of_friends = num_of_friends - 1
                WHERE friend_id = OLD.friend_id2;
            END;

            INSERT INTO friends (friend_email, password, profile_name, date_started)
            VALUES ('sleve@email.com', 'pwsleve', 'Sleve McDichael', '2023-04-20'),
            ('onson@email.com', 'pwonson', 'Onson Sweemey', '2023-04-20'),
            ('darryl@email.com', 'pwdarryl', 'Darryl Archideld', '2023-04-20'),
            ('anatoli@email.com', 'pwanatoli', 'Anatoli Smorin', '2023-04-20'),
            ('rey@email.com', 'pwrey', 'Rey McSriff', '2023-04-20'),
            ('glenallen@email.com', 'pwglenallen', 'Glenallen Mixon', '2023-04-20'),
            ('mario@email.com', 'pwmario', 'Mario McRlwain', '2023-04-20'),
            ('raul@email.com', 'pwraul', 'Raul Chamgerlain', '2023-04-20'),
            ('bobson@email.com', 'pwbobson', 'Bobson Dugnutt', '2023-04-20'),
            ('willie@email.com', 'pwwillie', 'Willie Dustice', '2023-04-20');

            INSERT INTO myfriends (friend_id1, friend_id2)
            VALUES (1,2), (1,3), (2,3), (2,4), (3,4),
            (3,5), (4,5), (4,6), (5,6), (5,7),
            (6,7), (6,8), (7,8), (7,9), (8,9),
            (8,10), (9,10), (9,1), (10,1), (10,2)
        ";

        if (mysqli_multi_query($this->connection, $SQLstring)) {
            $this->closeConnection();
            return true;
        }
        $this->closeConnection();
        return false;
    }
}