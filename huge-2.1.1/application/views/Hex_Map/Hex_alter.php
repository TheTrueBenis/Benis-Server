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
$sql = "SELECT * FROM DungeonsAndDragons.Hex_Map WHERE  `Hex_Map`.`X` =".$_GET["X"]." and `Hex_Map`.`Y` =".$_GET["Y"].";";
echo $sql;

$result = $conn->query($sql);
if ($result->num_rows > 0) {
$sql = "UPDATE  `DungeonsAndDragons`.`Hex_Map` SET  `Terrain` =  '".$_GET["terr"]."' WHERE  `Hex_Map`.`X` =".$_GET["X"]." and `Hex_Map`.`Y` =".$_GET["Y"].";";
echo $sql;
$result = $conn->query($sql);
}
else{
	$sql = "insert into `DungeonsAndDragons`.`Hex_Map` (Terrain,X,Y) Values  ('".$_GET["terr"]."',".$_GET["X"].",".$_GET["Y"].");";
	echo $sql;
	$result = $conn->query($sql);
}
$conn->close();

?>