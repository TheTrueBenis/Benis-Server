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
function Get_Children($id,$padding) {
	$servername = "localhost";
	$username = "DungeonsAndDrago";
	$password = "9616347";
	$dbname = "DungeonsAndDragons";

	$conn = new mysqli($servername, $username, $password, $dbname);

	$paddingValue = "padding-left:".$padding."px;";
	$padding = $padding + 10;
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT * FROM Dmn_Area as t1 where t1.ParentArea = ".$id;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo "<div id=".$row["idDomains"]." style='display:inline;".$paddingValue."'>".$row["Dmn_Name"]."</div>";
			echo "</br>";
			Get_Children($row["idDomains"],$padding);
		}
	}
	$sql = "SELECT * FROM Dmn_City as t1 where t1.ParentArea = ".$id;
	$result2 = $conn->query($sql);
	if ($result2->num_rows > 0) {
		while($row2 = $result2->fetch_assoc()) {
			echo "<div id=".$row2["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>".$row2["CityName"]."</div>";
			echo "</br>";
		}
	}
	$conn->close();
}
if ($_GET["ID"] !== null)
	Get_Children($_GET["ID"]);
else
	Get_Children(0);
	
$conn->close();

echo rand();
?>
<div id="response"></div>
