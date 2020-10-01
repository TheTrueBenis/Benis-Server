<?php
$servername = "localhost";
$username = "DungeonsAndDrago";
$password = "9616347";
$dbname = "DungeonsAndDragons";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "UPDATE  `DungeonsAndDragons`.`Inventory` SET  `Equiped` =  '0' WHERE  `Inventory`.`ID` =".$_GET["ID"].";"; 
$result = $conn->query($sql);

echo "Good";

$conn->close();
?>
