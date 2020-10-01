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

$selection = "";

switch ($_GET["Slot"]) {
        case 0:
        //echo "Misc";
        break;
        case 1:
        //echo "Main Hand ";
	$selection = "(`Equiped_Slot` = 1 or `Equiped_Slot` = 4)";
        break;
        case 2:
        //echo "Off Hand";
	$selection = "(`Equiped_Slot` = 2 or `Equiped_Slot` = 4)";
        break;
        case 3:
        break;
        case 4:
        //echo "Two Hand";
	$selection = "(`Equiped_Slot` = 1 or `Equiped_Slot` = 2 or `Equiped_Slot` = 4)";
        break;
        case 6:
	$selection = "(`Equiped_Slot` = 6)";
        break;
}

$sql = "select * from `DungeonsAndDragons`.`Inventory` as t1
	left join `DungeonsAndDragons`.`Items` as t2
	on t1.Item_ID = t2.ID
	Where `Equiped` =  '1' and ".$selection;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
	echo "The following item(s) need to be unequiped before you can do that";
	$i = 0;
    while($row = $result->fetch_assoc()) {
	if($i){
	echo ",";
	}
	echo " ";
	echo $row["Name"];
	$i++;
    }
} else {
echo "Good";
$sql = "UPDATE  `DungeonsAndDragons`.`Inventory` SET  `Equiped` =  '1',`Equiped_Slot` = '".$_GET["Slot"]."' WHERE  `Inventory`.`ID` =".$_GET["ID"].";"; 
$result = $conn->query($sql);
$conn->close();
}
?>
