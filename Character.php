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

$sql = "SELECT *, t1.race as race_ID FROM Players as t1 
	left join Class as t2
	ON t1.Class = t2.ID
	left join Race as t3
	ON t1.race = t3.ID
	where t1.ID = ". $_GET["ID"];
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {

	echo "<a href='/test.php'>Back to Group </a>";
	echo "<a href='/Inventory.php?ID=" .$_GET["ID"] . "'>Inventory </a>";

	$Check = 0;
        $Speed = 0;
        $AC = 0;
        $Fort = 0;
        $Ref = 0;
        $Will= 0;

        $conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT sum(Item_AC) as AC ,sum(`Item_Fort`) as Fort,sum(`Item_Ref`) as Ref,sum(`Item_Will`) as Will, sum(`Movement`) as speed, sum(`Check`) as Abl_Chk
                 FROM Inventory as t1
                 left join Items as t2
                        on t1.Item_ID = t2.ID
                 where t1.Player_ID = ". $_GET["ID"] ." and t1.Equiped = 1";
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
        $Check = $row2["Abl_Chk"];
        $Speed = $row2["speed"];
        $AC = $row2["AC"];
        $Fort = $row2["Fort"];
        $Ref = $row2["Ref"];
        $Will= $row2["Will"];
		}
	}


	$xp = $row["XP"];
	$lvl = $row["Level"];
	echo "<table style='width:100%'>";
	echo "<tr>";
	echo "<td>" . $row["Name"] . "</td>";
	echo "<td>" . $lvl . "</td>";
	echo "<td>" . $row["Class_Name"] . "</td>";
	echo "<td>" . $row["XP"] . "</td>";
	echo "</tr>";
	echo "<tr>";
        echo "<td> Char name </td>";
	echo "<td> level </td>";
	echo "<td> Class </td>";
	echo "<td> XP </td>";
        echo "</tr>";
	echo "</table>";
	echo "<table style='width:100%'>";
        echo "<tr>";
        echo "<td>" . $row["Race"] . "</td>";
        echo "<td>" . $row["Size"] . "</td>";
        echo "<td>" . $row["Age"] . "</td>";
        echo "<td>" . $row["Gender"] . "</td>";
	echo "<td>" . $row["Height"] . "</td>";
	echo "<td>" . $row["Weight"] . "</td>";
	echo "<td>" . $row["Alignment"] . "</td>";
	echo "<td>" . $row["Deity"] . "</td>";
	echo "<td>" . $row["Adventuring Company"] . "</td>";
        echo "</tr>";
        echo "<tr>";
        echo "<td> Race </td>";
        echo "<td> Size </td>";
        echo "<td> Age </td>";
	echo "<td> Gender </td>";
        echo "<td> Height </td>";
	echo "<td> Weight </td>";
	echo "<td> Alignment </td>";
	echo "<td> Deity </td>";
	echo "<td> Adventuring Company </td>";
        echo "</tr>";
        echo "</table>";
	echo "<div style='width:100%'> <div style='display: inline-block; width:33%'>";
	echo "<div class='TitleBar'>INITIATIVE </div>";
	echo "<div class='Ability'>+" . (floor(($row["DEX"]-10)/2)+($lvl/2)) . "</div>";
	echo "<div class='TitleBar'>Ability Scores</div>";
	echo "<div class='Ability'>";
	echo "<div>" . $row["STR"]. " STR\t +" . (floor(($row["STR"]-10)/2)+($lvl/2)) . "</div>";
	echo "<div>" . $row["CON"]. " CON\t +" . (floor(($row["CON"]-10)/2)+($lvl/2)) . "</div>";
	echo "<div>" . $row["DEX"]. " DEX\t +" . (floor(($row["DEX"]-10)/2)+($lvl/2)) . "</div>";
	echo "<div>" . $row["INT"]. " INT\t +" . (floor(($row["INT"]-10)/2)+($lvl/2)) . "</div>";
	echo "<div>" . $row["WIS"]. " WIS\t +" . (floor(($row["WIS"]-10)/2)+($lvl/2)) . "</div>";
	echo "<div>" . $row["CHA"]. " CHA\t +" . (floor(($row["CHA"]-10)/2)+($lvl/2)) . "</div>";
	echo "</div>";
	echo "<div class='TitleBar'>HIT POINTS</div>";
	echo "<div class='Ability'>";
	echo "<div style='display: inline-block; width:35%'><div>HP</div><div>" 
	. ($row["Starting_HP"]+$row["HP_Per_Level"]*($lvl-1)+$row["CON"]) 
	. "</div></div>";
	echo "<div style='display: inline-block; width:45%; border-left:1px;border-color: black;border-left-style: solid;'>Bloodied<br>" . floor(($row["Starting_HP"]+$row["HP_Per_Level"]*($lvl-1)+$row["CON"])/2) . "</div>";
	echo "</div>";
	echo "<div class='TitleBar'>Healing Surges</div>";
	echo "<div class='Ability'>";
	echo "<div style='display: inline-block; width:35%;'>Value<br>" . floor(($row["Starting_HP"]+$row["HP_Per_Level"]*($lvl-1)+$row["CON"])/4) . "</div>";
	echo "<div style='display: inline-block; width:45%; border-left:1px;border-color: black;border-left-style: solid;'>Surges<br>" . ($row["Surges"]+floor(($row["CON"]-10)/2)). "</div>";
	echo "</div>";
	echo "<div class='TitleBar'>Skills</div>";
        echo "<div class='Skills'>";
	$conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT * FROM Skills";
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
		if($row[$row2["Skill"]])
		{
		$trained = 5;
		echo "<div style='background-color:green'>";
		}
		else
		{
		$trained = 0;
		echo "<div>";
		}
		if($row2["Armor_Penalty"])
		{
		$Armor_Pen = $Check; 
		}
		else
                {
                $Armor_Pen = 0;
                }
		if($row2["ID"]==10){$Insight =((floor(($row[$row2["Ability"]]-10)/2)+($lvl/2))+$trained+$Armor_Pen);}
		if($row2["ID"]==13){$Percept =((floor(($row[$row2["Ability"]]-10)/2)+($lvl/2))+$trained+$Armor_Pen);}
		echo "<div style='display: inline-block; width:20%;vertical-align:top'>" . ((floor(($row[$row2["Ability"]]-10)/2)+($lvl/2))+$trained+$Armor_Pen) . 
		     "</div><div style='display: inline-block; width:70%;vertical-align:top'> " . $row2["Skill"] . "</div></div>";
            }
        }
        echo "</div>";
	echo "</div>";
	echo "<div style='display: inline-block; width:33%;height:100%;vertical-align:top'>";
	echo "<div>";
	echo "<div class='TitleBar'>DEFENSES</div>";
	echo "<div class='Ability'>";
	echo "<div>\tAC \t" . (10+($lvl/2)+$AC) . "</div>";
        echo "<div>\tFort \t" . (10+$row["Race_Fort"]+$row["Class_Fort"]+max((floor(($row["STR"]-10)/2)+($lvl/2)),(floor(($row["CON"]-10)/2)+($lvl/2)))+$Fort) . "</div>";
        echo "<div>\tREF \t"  . (10+$row["Race_Ref"]+$row["Class_Ref"]+max((floor(($row["DEX"]-10)/2)+($lvl/2)),(floor(($row["INT"]-10)/2)+($lvl/2)))+$Ref) . "</div>";
        echo "<div>\tWILL \t" . (10+$row["Race_Will"]+$row["Class_Will"]+max((floor(($row["WIS"]-10)/2)+($lvl/2)),(floor(($row["CHA"]-10)/2)+($lvl/2)))+$Will) . "</div>";
	echo "</div>";

	echo "<div class='TitleBar'> Race Features</div>";

        $conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT * FROM Race_Features where Race_ID =".$row["race_ID"];
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                echo "<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'><a href='Race_Feature.php?ID=" .$row2["ID"]. "&PL_ID=".$_GET["ID"]."'>" . $row2["Race_Feature_Name"] ."</a></div>";
            }
        }


	echo "<div class='TitleBar'>Class Features</div>";

	$conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT * FROM Class_Features where Class_ID =".$row["Class"];
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                echo "<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'><a href='Class_Feature.php?ID=" .$row2["ID"]. "&PL_ID=".$_GET["ID"]."'>" . $row2["Class_Feature_Name"] ."</a></div>";
            }
        }

	echo "<div class='TitleBar'>Path</div>";

        $conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT * FROM Class_Features where Class_ID =".$row["Class"];
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                echo "<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'>&nbsp;</div>";
            }
        }

	echo "<div class='TitleBar'>Paragon</div>";

        $conn2 = new mysqli($servername, $username, $password, $dbname);
        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT * FROM Class_Features where Class_ID =".$row["Class"];
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                echo "<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'>&nbsp;</div>";
            }
        }

	echo "<div class='TitleBar'>Languages</div>";

        if ($conn2->connect_error) {
                die("Connection failed: " . $conn2->connect_error);
        }
        $sql2 = "SELECT * FROM Player_Language t1
		left join Languages t2
		on t1.Language_ID = t2.ID
	where Player_ID =".$_GET["ID"];
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                echo "<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'>".$row2["Name"] ."</div>";
            }
        }

        echo "</div>";
	echo "</div>";
        echo "<div style='display: inline-block; width:33%;vertical-align:top'>";
	echo "<div class='TitleBar'>Movement</div>";
	echo "<div class='Ability'>";
	echo "Speed " . ($row["Speed"]+$Speed)  ;
	echo "</div>";
        echo "<div class='TitleBar'>Senses</div>";
        echo "<div class='Ability'>";
        echo "<div>Insight " . ($Insight+10) . "</div>";
	echo "<div>Perception " . ($Percept+10) . "</div>";
	echo "<div>" . $row["Special_Senses"] . "</div>";
	echo "</div>";
	echo "<div class='TitleBar'>Feats</div>";

        $sql2 = "SELECT * FROM Class_Features where Class_ID =".$row["Class"];
        $result2 = $conn2->query($sql2);
        if ($result2->num_rows > 0) {
            // output data of each row
            while($row2 = $result2->fetch_assoc()) {
                echo "<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'><a href='Class_Feature.php?ID=" .$row2["ID"]. "&PL_ID=".$_GET["ID"]."'>" . $row2["Class_Feature_Name"] ."</a></div>";
            }
        }
	
        echo "</div>";
	echo "</div>";
	echo "</div>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>
