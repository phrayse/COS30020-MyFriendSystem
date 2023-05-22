<?php

// Procedural syntax:
// login or show error messages.
$user = $_GET["username"];
$password = $_GET["password"];
$dbConnect = @mysqli_connect("localhost", $user, $password)
  or die("<p>Unable to connect to the database server.</p>"
    . "<p>Error code " . mysqli_connect_errno()
    . ": " . mysqli_connect_error() . "</p>");
echo "<p>Successfully connected to the database server.</p>";
@mysqli_select_db($dbConnect, "amolnar_d")
  or die("<p>Unable to select the database.</p>"
    . "<p>Error code " . mysqli_errno($dbConnect)
    . ": " . mysqli_error($dbConnect) . "</p>");
echo "<p>Successfully opened the database.</p>";
// additional statements that access the database
mysqli_close($dbConnect);


// 10.3 using classes in PHP scripts
// instantiate an object from a class.
$objectName = new ClassName(arg1, arg2);

// Member Selection Notation (->)
// used to access methods and properties contained within an object.
$checking->getBalance();
$checkNumber = 1022;
$checking->getCheckAmount($checkNumber);


// Working with database connections as objects
// Access MySQL db connections as objects by instantiating an object from the mysqli class.
// Previous style, procedural syntax:
$dbConnect = mysqli_connect("localhost", "dongosselin", "rosebud", "real_estate");
// Object Oriented:
$dbConnect = new mysqli("localhost", "dongosselin", "rosebud", "real_estate");
$dbConnect->close();

// Procedural syntax:

// Object Oriented:
$dbConnect = @new mysqli("localhost", "dongosselin", "rosebud");
if ($dbConnect->connect_error )
  die("<p>Unable to connect to the database server.</p>"
    . "<p>Error code " . $dbConnect->connect_errno
    . ": " . $dbConnect->connect_error . "</p>";
$dbName = "real_estate";
@$dbConnect->select_db($dbName)
  or die("<p>Unable to select the database.</p>"
    . "<p>Error code " . $dbConnect->errno . ": "
    . $dbConnect->error . "</p>");
// additional statements that access or manipulate the database
$dbConnect->close();


// Execute SQL statements with objects/classes:
$tableName = "inventory";
$sqlString = "SELECT * FROM inventory";
$queryResult = $dbConnect->($sqlString)
  or die("<p>Unable to execute the query.</p>"
    . "<p>Error code " . $dbConnect->errno
    . ": " . $dbConnect->error . "</p>");
echo "<table width='100%' border='1'>";
echo "<tr><th>Make</th><th>Model</th><th>Price</th><th>Inventory</th></tr>";
$row = $queryResult->fetch_row();
while ($row) {
  echo "<tr><td>{$row[0]}</td>";
  echo "<td>{$row[1]}</td>";
  echo "<td class='right'>{$row[2]}</td>";
  echo "<td class='right'>{$row[3]}</td></tr>";
  $row = $queryResult->fetch_row();
}

?>
