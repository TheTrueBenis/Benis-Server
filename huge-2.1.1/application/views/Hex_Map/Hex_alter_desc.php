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


$sql = "UPDATE  `DungeonsAndDragons`.`Hex_Map` SET  `Description` =  '".$_POST["desc"]."' WHERE  `Hex_Map`.`X` =".$_POST["X"]." and `Hex_Map`.`Y` =".$_POST["Y"].";";
echo $sql;
$result = $conn->query($sql);
$conn->close();

?>