<style>
div.TitleBar {
    background-color:black;
    color:white;
    text-align:center;
    font-size:40px;
    margin:5px;
}
div.Ability {
    font-family:Courier;
    font-size:35px;
    text-align:center;
}
div.Skills {
    font-family:Courier;
    font-size:30px;
}
 
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script type="text/javascript">
function Toggle_Equip(x,y)
{
if(document.getElementById(x).innerHTML=="(E)"){
$url = "Unequip?ID=";
$tog = "";
$dis = "block";
$dis2 = "none";
}
else
{
$url = "Equip?ID=";
$tog = "(E)";
$dis = "none";
$dis2 = "block";
}

$.ajax({
    url: $url+x+'&Slot='+y,
    context: document.body
    }).done(function(data) {
    if(data=="Good")
    {
        document.getElementById(x).innerHTML = $tog;
        document.getElementById(x+"_equip").style.display = $dis;
        document.getElementById(x+"_unequip").style.display = $dis2;
    }
    else{
    document.getElementById("response").innerHTML = String(data);
    }
    });
    return false;
}

</script>
<?php
	echo "<a href='./index'>Back to Group </a>";
        echo "<a href='./Character?ID=" .$_COOKIE["Char_id"] . "'>Back to Character Sheet</a>";

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

$sql = "SELECT *,t1.ID as ID FROM Inventory as t1 
	left join Items as t2
	on t1.Item_ID = t2.ID
	where t1.Player_ID = ". $_COOKIE["Char_id"];
$result = $conn->query($sql);
echo "<table>";
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	echo "<tr>
	<td>";

	echo "<div id=".$row["ID"]." style='display:inline;'>";
	if($row["Equiped"]&&$row["Slot"]<>0){
        echo "(E)</div>";
        }
        else if($row["Slot"]<>0){
        echo "</div>";
        }

	echo "<div style='display:inline;'><a href=Item.php?ID=".$row["Item_ID"]."&PL_ID=".$_COOKIE["Char_id"].">" . $row["Name"] . "<div></td>
	<td>".($row["Weight"]/16)."</td>
	<td>";

	$dis1 = "display:none";
	$dis2 = "display:none";

	if($row["Equiped"]&&$row["Slot"]<>0){
	$dis1 = "display:none";
        $dis2 = "display:block";
        }
        else if($row["Slot"]<>0){
	$dis1 = "display:block";
        $dis2 = "display:none";
        }
	echo "<div id='".$row["ID"]."_unequip' style='" . $dis2 . "'><a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",1)\">Un-Equip</a></div><div id='".$row["ID"]."_equip' style='". $dis1 ."'>";
	switch ($row["Slot"]) {
        case 0:
	echo "</div>";
        break;
        case 1:
        echo "<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",1)\">Equip Main Hand</a></div>";
        break;
        case 2:
        echo "<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",1)\">Equip Main Hand</a> <a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",2)\">Equip Off Hand</a></div>";
        break;
        case 3:
        echo "<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",1)\">Equip Main Hand 1</a> <a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",4)\">Equip Two Hand 4</a></div>";
        break;
        case 4:
        echo "<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",4)\">Equip Two Hand 4</a></div>";
        break;
        case 6:
        echo "<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row["ID"].",6)\">Equip Armor</a></div>";
        break;
        }
	echo "<div>";
	switch ($row["Slot"]) {
        case 0:
                echo "Misc";
        break;
        case 1:
                echo "Main Hand";
        break;
        case 2:
                echo "Off Hand";
        break;
        case 3:
                echo "Versitile";
        break;
        case 4:
                echo "Two Handed";
        break;
        case 6:
                echo "Armor";
        break;
        }
	echo "</td></tr>";

    }
} else {
    echo "0 results";
}
echo "</table>";
$conn->close();
?>
<div id="response"></div>
