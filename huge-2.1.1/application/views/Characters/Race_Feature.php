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
function Toggle_Equip(x)
{
if(document.getElementById(x).innerHTML=="Equiped"){
$url = "Unequip.php?ID=";
$tog = "Not Equiped";
}
else
{
$url = "Equip.php?ID=";
$tog = "Equiped";
}
<!--
$.ajax({
    url: $url+x,
    context: document.body
    }).done(function() {
    document.getElementById(x).innerHTML = $tog;
    });
    return false;
}
-->
</script>
<?php
	echo "<a href='/test.php'>Back to Group </a>";
        echo "<a href='/Character.php?ID=" .$_GET["PL_ID"] . "'>Back to Character Sheet</a>";

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

$sql = "SELECT *,t1.ID as ID FROM Race_Features as t1 
	where t1.ID = ". $_GET["ID"];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
	echo "<div class='TitleBar'>" . $row["Race_Feature_Name"] . "</div><div class='Skills'>" . $row["Description"] . "</div>";

    }
} else {
    echo "0 results";
}
$conn->close();
?>
