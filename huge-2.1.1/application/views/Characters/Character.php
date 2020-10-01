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
		document.getElementById("response").innerHTML = "";
    }
    else{
    document.getElementById("response").innerHTML = String(data);
    }
    });
    return false;
}

</script>

<a href='./Character_Select'>Back to Group </a>
<a href='./Inventory'>Inventory </a>

<?php

if(isset($_POST["ID"]))
{
	$ID=$_POST["ID"];
}
else
{
	$ID=$_COOKIE["Char_id"];
}

function DBConnection(){
	$servername = "localhost";
	$username = "DungeonsAndDrago";
	$password = "9616347";
	$dbname = "DungeonsAndDragons";
	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	else{
		return $conn;
	}
}

function CalcLevel($ID) {
	$conn = DBConnection();

	$Level = 0;
	$sql = "SELECT sum(Level) as Level
				FROM Player_Class as t1 
				Left join Class as t3
				ON t1.Class_ID = t3.ID
				where t1.Player_ID = ".$ID;
	$result2 = $conn->query($sql);
	$row2 = $result2->fetch_assoc();
	$Level = $row2["Level"];
		
	
	return $Level;
}
	
function CalcProf($lvl) {
	$conn = DBConnection();
	$sql = "SELECT *
			FROM Levels as t1
			where t1.Level = ".$lvl;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	return $row["Proficiency"];
}

function CalcAC($ID) {
	$conn = DBConnection();
	$sql = "SELECT sum(Item_AC) as AC
			FROM Inventory as t1
			left join Items as t2
				on t1.Item_ID = t2.ID
			where t1.Player_ID = ". $ID ." and t1.Equiped = 1";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$AC = $row["AC"];
	
	$sql = "SELECT sum(AC) as AC
			FROM Player_Class as t1 
			left join Class_Features as t2
			ON t1.Class_ID = t2.Class_ID and (t2.Group_ID = 0 or t2.Group_ID = t1.Group_1)and t1.Level >= t2.Level
			where t1.Player_ID =$ID";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$AC = $AC +$row["AC"];
		}
	}
	Return $AC;
}

function CalcHP($ID) {
	$conn = DBConnection();
	$sql = "SELECT sum(t3.Starting_HP*!t1.Multiclass)+ Sum(t3.HP_Per_Level*t1.Level) as HP
				FROM Player_Class as t1 
				Left join Class as t3
				ON t1.Class_ID = t3.ID
				where t1.Player_ID = ".$ID;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();	
	$HP = $row["HP"];
		
	
	Return $HP;
}

function GetItems($ID) {
	$conn = DBConnection();
	$return = "";
	
	
	$sql = "SELECT *,t1.ID as ID FROM Inventory as t1 
				left join Items as t2
				on t1.Item_ID = t2.ID
				where t1.Player_ID = ". $ID;
	$result2 = $conn->query($sql);
	$return =$return."<table class='Inventory'>";
	if ($result2->num_rows > 0) {
		while($row2 = $result2->fetch_assoc()) {
			$return =$return."<tr><td>";

			$return =$return."<div id=".$row2["ID"]." style='display:inline;'>";
			if($row2["Equiped"]&&$row2["Slot"]<>0){
				$return =$return."(E)</div>";
			}
			else if($row2["Slot"]<>0){
				$return =$return."</div>";
			}

			$return =$return."<div style='display:inline;'><a href=Item?ID=".$row2["ID"]."&PL_ID=".$ID.">" . $row2["Name"] . "<div></td><td>";

			$dis1 = "display:none";
			$dis2 = "display:none";

			if($row2["Equiped"]&&$row2["Slot"]<>0){
				$dis1 = "display:none";
				$dis2 = "display:table";
			}
			else if($row2["Slot"]<>0){
				$dis1 = "display:table";
				$dis2 = "display:none";
			}
			$return =$return."<div class='mobile_hidden' id='".$row2["ID"]."_unequip' style='" . $dis2 . "'><a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",1)\">Un-Equip</a></div><div class='mobile_hidden' id='".$row2["ID"]."_equip' style='". $dis1 ."'>";
			switch ($row2["Slot"]) {
				case 0:
					$return =$return."</div>";
				break;
				case 1:
					$return =$return."<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",1)\">Equip Main Hand</a></div>";
				break;
				case 2:
					$return =$return."<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",1)\">Equip Main Hand</a></br><a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",2)\">Equip Off Hand</a></div>";
				break;
				case 3:
					$return =$return."<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",1)\">Equip Main Hand</a></br><a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",4)\">Equip Two Hand </a></div>";
				break;
				case 4:
					$return =$return."<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",4)\">Equip Two Hand</a></div>";
				break;
				case 6:
					$return =$return."<a href=\"#\" onClick=\"javascript:return Toggle_Equip(".$row2["ID"].",6)\">Equip Armor</a></div>";
				break;
				}
			$return =$return."</td></tr>";
		}
	} else {
		echo "0 results";
	}
	$return =$return."</table>";
	Return $return;
}

function GetRaceFeatures($RaceID,$ID) {
	$conn = DBConnection();
	$return = "";
	
	$sql = "SELECT * FROM Race_Features where Race_ID =".$RaceID;
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$return =$return."<div style='vertical-align:top;padding-bottom:2.5%;'><a href='Race_Feature?ID=" .$row["ID"]. "&PL_ID=".$ID."'>" . $row["Race_Feature_Name"] ."</a></div>";
	}

		
	
	Return $return;
}

function GetClassFeatures($ID) {
	$conn = DBConnection();
	$return = "";
	
	$sql = "SELECT *, t2.ID as CFID
				FROM Player_Class as t1 
				left join Class_Features as t2
				ON t1.Class_ID = t2.Class_ID and (t2.Group_ID = 0 or t2.Group_ID = t1.Group_1)and t1.Level >= t2.Level
				Left join Class as t3
				ON t1.Class_ID = t3.ID
				where t1.Player_ID =".$ID;
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$return =$return."<div style='vertical-align:top;padding-bottom:2.5%;'><a href='Class_Feature?ID=" .$row["CFID"]. "&PL_ID=".$ID."'>" . $row["Class_Feature_Name"] ."</a></div>";
	}

		
	
	Return $return;
}

function GetPathFeatures($ID) {
	$conn = DBConnection();
	$return = "";
	
	$sql = "SELECT *, t2.ID as PID
				FROM Player_Class as t1 
				inner join Class_Path_Features as t2
				ON t1.Path_ID = t2.Path_ID and (t2.Group_ID = 0 or t2.Group_ID = t1.Group_1) and t1.Level >= t2.Level
				Left join Class as t3
				ON t1.Class_ID = t3.ID
				where t1.Player_ID = ".$ID;
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$return =$return."<div style='vertical-align:top;padding-bottom:2.5%'><a href='Path_Feature?ID=" .$row["PID"]. "&PL_ID=".$ID."'>" . $row["Path_Feature_Name"] ."</a></div>";
	}

		
	
	Return $return;
}

function GetFeats($ID) {
	$conn = DBConnection();
	$return = "";
	
	$sql = "SELECT *
				FROM Player_Class as t1 
				left join Class_Features as t2
				ON t1.Class_ID = t2.Class_ID
				Left join Class as t3
				ON t1.Class_ID = t3.ID
				where t1.Player_ID = ".$ID;
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$return =$return."<div style='vertical-align:top;font-size:25px;padding-bottom:15px;'><a href='Feature?ID=" .$row["ID"]. "&PL_ID=".$ID."'>" . $row["Class_Feature_Name"] ."</a></div>";
	}

		
	
	Return $return;
}

function GetSkills($ID,$row) {
	$conn = DBConnection();
	$return = "";
	$proficency = CalcProf(CalcLevel($ID));
	
	$sql = "SELECT * FROM Skills as t1
			left join Player_Skills as t2
			on t1.ID = t2.Skill_ID and t2.Player_ID = ".$ID;
		$result2 = $conn->query($sql);
		if ($result2->num_rows > 0) {
			while($row2 = $result2->fetch_assoc()) {
				$return =$return."<div";

				if(!is_null($row2["Player_ID"]))
				{
					$Trained = "X";
					$addit =$proficency;	
					$return =$return." class=\"green\"";
				}
				else
				{
					$Trained = "";
					$addit =0;
				}
				$return =$return.">";
				if($row2["Skill"]=="Insight"){$Insight =((floor(($row[$row2["Ability"]]-10)/2))+$addit);}
				if($row2["Skill"]=="Perception"){$Percept =((floor(($row[$row2["Ability"]]-10)/2))+$addit);}
				//$return =$return."<div class='tick-box'>";
				//if (!is_null($row2["Player_ID"])){$return =$return."X";}else{$return =$return."&nbsp;";}
				//$return =$return."</div>";
				$return =$return."<div style='display: inline-block; width:15%;vertical-align:top'>";
				if($row[$row2["Ability"]]+$addit>10)
				{
					$return =$return."+";
				}
				$return =$return.((floor(($row[$row2["Ability"]]-10)/2))+$addit) . 
				"</div><div style='display: inline-block; width:75%;vertical-align:top'> " . $row2["Skill"] . "</div></div>";
			}
		}

		
	
	Return $return;
}

// Create connection
$conn = DBConnection();


$sql = "SELECT *, t1.race as race_ID FROM Players as t1 
	left join Class as t2
	ON t1.Class = t2.ID
	left join Race as t3
	ON t1.race = t3.ID
	where t1.ID = ". $ID;
$result = $conn->query($sql);


$row = $result->fetch_assoc();


$Speed = 0;
$AC = 0;
$HP = 0;
$addit = 0;

$xp = $row["XP"];
$lvl = CalcLevel($ID);
$proficency = CalcProf($lvl);
$AC = CalcAC($ID);
$HP = CalcHP($ID);	


?>
<div style='width:100%'> 
	<div style='display: inline-block; width:33%'>
		<div class='mobile_hidden' style='display: inline-block; width:25%'>
			<div class='Ability'>
				<div>
					<div>
						STR
					</div>
					<div>
						<? echo (floor(($row["STR"]-10)/2)) ?>
					</div>
					<div>
						<? echo $row["STR"] ?>
					</div>
				</div>
				<div>
					<div>
						DEX
					</div>
					<div>
						<? echo (floor(($row["DEX"]-10)/2))?>
					</div>
					<div>
						<? echo $row["DEX"]?>
					</div>
				</div>
				<div>
					<div>
						CON
					</div>
					<div>
						<? echo (floor(($row["CON"]-10)/2))?>
					</div>
					<div>
						<? echo $row["CON"]?>
					</div>
				</div>
				<div>
					<div>
						INT
					</div>
					<div>
						<? echo (floor(($row["INT"]-10)/2))?>
					</div>
					<div>
						<? echo $row["INT"] ?>
					</div>
				</div>
				<div>
					<div>
						WIS
					</div>
					<div>
						<? echo (floor(($row["WIS"]-10)/2))?>
					</div>
					<div>
						<? echo $row["WIS"] ?>
					</div>
				</div>
				<div>
					<div>
						CHA</div>
					<div>
						<? echo (floor(($row["CHA"]-10)/2))?>
					</div>
					<div>
						<? echo $row["CHA"] ?>
					</div>
				</div>
			</div>
		</div>
		<div class='col2'>
			<div class='Skills' style='border: black;border: 1px;border-style: solid;'> 
				<? echo $proficency ?> Proficiency
			</div>
			<div class='Skills' style='border: black;border: 1px;border-style: solid;'>
				<div class='<? if ($row["Saving_Throw_1"]=="STR" || $row["Saving_Throw_2"]=="STR"){echo "green";$addit =$proficency;} else{echo "";$addit =0;} ?>'>
					<!--	
					<div class='tick-box '> 
						<? if ($row["Saving_Throw_1"]=="STR" || $row["Saving_Throw_2"]=="STR"){echo "X";$addit =$proficency;} else{echo "&nbsp;";$addit =0;} ?>
					</div>
					-->
					<div style='display: inline-block; width:15%'>
						<?
							if($row["STR"]+$addit>10){echo "+";}
							echo (floor(($row["STR"]-10)/2)+$addit);
						?>
					</div>
					<div style='display: inline-block; width:69%'>
						Strength
					</div>
				</div>
				<div class='<? if ($row["Saving_Throw_1"]=="DEX" || $row["Saving_Throw_2"]=="STR"){echo "green";$addit =$proficency;} else{echo "";$addit =0;} ?>'>
					<!--	
					<div class='tick-box '> 
						<? if ($row["Saving_Throw_1"]=="DEX" || $row["Saving_Throw_2"]=="DEX"){echo "X";$addit =$proficency;}else{echo "&nbsp;";$addit =0;} ?>
					</div>
					-->
					<div style='display: inline-block; width:15%'>
						<?
							if($row["DEX"]+$addit>10){echo "+";}
							echo (floor(($row["DEX"]-10)/2)+$addit)
						?>
					</div>
					<div style='display: inline-block; width:69%'>
						Dexterity
					</div>
				</div>
				<div class='<? if ($row["Saving_Throw_1"]=="CON" || $row["Saving_Throw_2"]=="CON"){echo "green";$addit =$proficency;} else{echo "";$addit =0;} ?>'>
					<!--	
					<div class='tick-box '> 
						<? if ($row["Saving_Throw_1"]=="CON" || $row["Saving_Throw_2"]=="CON"){echo "X";$addit =$proficency;}else{echo "&nbsp;";$addit =0;}?>
					</div>
					-->
					<div style='display: inline-block; width:15%'>
						<?
							if($row["CON"]+$addit>10){echo "+";}
							echo (floor(($row["CON"]-10)/2)+$addit)
						?>
					</div>
					<div style='display: inline-block; width:69%'>
						Constitution
					</div>
				</div class='<? if ($row["Saving_Throw_1"]=="INT" || $row["Saving_Throw_2"]=="INT"){echo "green";$addit =$proficency;} else{echo "";$addit =0;} ?>'>
				<div>
					<!--	
					<div class='tick-box '> 
						<? if ($row["Saving_Throw_1"]=="INT" || $row["Saving_Throw_2"]=="INT"){echo "X";$addit =$proficency;}else{echo "&nbsp;";$addit =0;}?>
					</div>
					-->
					<div style='display: inline-block; width:15%'>
						<?
							if($row["INT"]+$addit>10){echo "+";}
							echo (floor(($row["INT"]-10)/2)+$addit)
						?>
					</div>
					<div style='display: inline-block; width:69%'>
						Intelligence
					</div>
				</div>
				<div class='<? if ($row["Saving_Throw_1"]=="WIS" || $row["Saving_Throw_2"]=="WIS"){echo "green";$addit =$proficency;} else{echo "";$addit =0;} ?>'>
					<!--	
					<div class='tick-box '> 
						<? if ($row["Saving_Throw_1"]=="WIS" || $row["Saving_Throw_2"]=="WIS"){echo "X";$addit =$proficency;}else{echo "&nbsp;";$addit =0;}?>
					</div>
					-->
					<div style='display: inline-block; width:15%'>
						<?
							if($row["WIS"]+$addit>10){echo "+";}
							echo (floor(($row["WIS"]-10)/2)+$addit)
						?>
					</div>
					<div style='display: inline-block; width:69%'>
						Wisdom
					</div>
				</div>
				<div class='<? if ($row["Saving_Throw_1"]=="CHA" || $row["Saving_Throw_2"]=="CHA"){echo "green";$addit =$proficency;} else{echo "";$addit =0;} ?>'>
					<!--	
					<div class='tick-box '> 
						<? if ($row["Saving_Throw_1"]=="CHA" || $row["Saving_Throw_2"]=="CHA"){echo "X";$addit =$proficency;}else{echo "&nbsp;";$addit =0;}?>
					</div>
					-->
					<div style='display: inline-block; width:15%'>
						<?
							if($row["CHA"]+$addit>10){echo "+";}
							echo (floor(($row["CHA"]-10)/2)+$addit)
						?>
					</div>
					<div style='display: inline-block; width:69%'>
						Charisma
					</div>
				</div>
				<div style='text-align:center'>
					Saving Throws
				</div>
			</div>
			<div class='Skills' style='border: black;border: 1px;border-style: solid;'>
		<?
		echo GetSkills($ID,$row);
		$sql = "SELECT * FROM Skills as t1
			left join Player_Skills as t2
			on t1.ID = t2.Skill_ID and t2.Player_ID = ".$ID;
		$result2 = $conn->query($sql);
		if ($result2->num_rows > 0) {
			while($row2 = $result2->fetch_assoc()) {
				if(!is_null($row2["Player_ID"]))
				{
					$Trained = "X";
					$addit =$proficency;	
				}
				else
				{
					$Trained = "";
					$addit =0;
				}

				if($row2["Skill"]=="Insight"){$Insight =((floor(($row[$row2["Ability"]]-10)/2))+$addit);}
				if($row2["Skill"]=="Perception"){$Percept =((floor(($row[$row2["Ability"]]-10)/2))+$addit);}
				
			}
		}
		?>
				<div style='text-align:center'>
					Skills	
				</div>
			</div>
		</div>
		<div class='Skills' style='border: black;border: 1px;border-style: solid;'>
			<div style='display: inline-block;vertical-align:top;text-align:center' >
				<? echo 10+$Percept; ?>
			</div>
			<div class='mobile_small' style='display: inline-block; vertical-align:middle ;text-align:center' >
				Passive Perception
			</div>
		</div>
		<div class='Skills' style='border: black;border: 1px;border-style: solid;'>
	<?
		$sql2 = "SELECT * FROM Player_Language t1
			left join Languages t2
			on t1.Language_ID = t2.ID
			where Player_ID =".$ID;
			$result2 = $conn->query($sql2);
		if ($result2->num_rows > 0) {
			while($row2 = $result2->fetch_assoc()) {
				echo "<div style='vertical-align:top;padding-bottom:15px;display:inline-block;padding-right:5px;'>".$row2["Name"] ."</div>";
			}
		}
	?>
		</div>
	</div>
	<div style='display: inline-block; width:30%;height:100%;vertical-align:top'>
		<div>
			<div class='Ability'>
				<div style='display: inline-block; width:10%'>
					<div>
						<? echo $AC ?>
					</div>
					<div>
						AC
					</div>
				</div>
				<div style='display: inline-block; width:30%; border-left:1px;border-color: black;border-left-style: solid;'>
					<? echo floor(($row["DEX"]-10)/2) ?>
					<br>Init
				</div>
				<div style='display: inline-block; width:35%; border-left:1px;border-color: black;border-left-style: solid;'>
					<? echo ($row["Speed"]+$Speed) ?>
					<br>Speed
				</div>
			</div>
			<div class='Ability' style='border: black;border: 1px;border-style: solid;'>
				<div style='display: inline-block; width:30%'>
					<div>
						<? echo ($HP+floor(($row["CON"]-10)/2)*$lvl) ?>
					</div>
					<div>
						HP
					</div>
				</div>
				<div style='display: inline-block; width:60%; border-left:1px;border-color: black;border-left-style: solid;'>
					<?
					$sql = "SELECT t3.Starting_HP , t1.Level
								FROM Player_Class as t1 
								Left join Class as t3
								ON t1.Class_ID = t3.ID
								where t1.Player_ID = ".$ID;
					$result2 = $conn->query($sql);
					if ($result2->num_rows > 0) {
						while($row2 = $result2->fetch_assoc()) {
							echo "<div style='vertical-align:top'>".$row2["Level"]."d".$row2["Starting_HP"] ."</div>";
						}
					}
					?>
					HD
				</div>
			</div>
			<div class='Items Skills' style='border: black;border: 1px;border-style: solid;'>
			<?
				echo GetItems($ID);
			?>
			<div id="response">
			</div>
			</div>
		</div>
	</div>
	<div style='display: inline-block; width:33%;vertical-align:top'>
		<div class='TitleBar'>
			Race Features
		</div>
		<div class='Skills'>
			<?
				echo GetRaceFeatures($row["race_ID"],$ID);
			?>
		</div>
		<div class='TitleBar'>
			Class Features
		</div>
		<div class='Skills'>
			<?
				echo GetClassFeatures($ID);
			?>
		</div>
		<div class='TitleBar'>
			Path
		</div>
		<div class='Skills'>
		<?
			echo GetPathFeatures($ID);
		?>
        </div>
		<div class='TitleBar'>
			Senses
		</div>
        <div class='Ability'>
			<div>
				Insight
				<? 
					echo ($Insight+10) 
				?>
			</div>
			<div>
				Perception
				<? 
					echo ($Percept+10) 
				?>
			</div>
			<div>
				<?
					$row["Special_Senses"]
				?>
			</div>
		</div>
		<div class='TitleBar'>
			Feats
		</div>
		<?
			echo GetFeats($ID);
        ?>
	</div>
</div>

<?


$conn->close();
?>
