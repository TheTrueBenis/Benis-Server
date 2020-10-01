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
function Get_Children($id,$padding,$passed,$distance) {
	$servername = "localhost";
	$username = "DungeonsAndDrago";
	$password = "9616347";
	$dbname = "DungeonsAndDragons";
	array_push($passed, $id);
	//print_r($passed);
	echo "</br>";
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	$paddingValue = "padding-left:".$padding."px;";
	$padding = $padding + 1;
	if($padding > 10)
		return "Overload";
	if($distance > 10000)
		return "Over Distance";
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT * FROM Dmn_City as t1 where t1.Dmn_Cityid = ".$id;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			//echo "<div id=".$row["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>".$padding. " " .$row["CityName"]."</div>";
			//echo "</br>";
			$cityName = $row["CityName"];
		}
	}
	$sql = "SELECT * FROM Dmn_Resources as t1 where t1.Dmn_City = ".$id;
	$result2 = $conn->query($sql);
	if ($result2->num_rows > 0) {
		$resources[0] = $distance;
		while($row2 = $result2->fetch_assoc()) {
			if(!in_array($row2["CityB"],$passed)){
				//$distance = $distance + $row2["Distance"]*$row2["Difficulty"];
				$resources[$row2["Dmn_Referance_Type"]] = ($row2["Referance_Amt"]/(1+($distance/35)));
				//$status = $status . Get_Children($row2["CityB"],$padding,$passed,$distance);
				echo "<div id=".$row2["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>--" .$cityName."--".$row2["Dmn_Referance_Type"]." ".($row2["Referance_Amt"]/(1+($distance/35)))." ".$row2["Distance"]." ".($distance/35)." </div>";
			}
			$refrences[$id] = $resources;
		}
		//print_r($refrences);
		//echo "</br>";
	}
	$sql = "SELECT * FROM Dmn_TradeRoute as t1 where t1.CityA = ".$id;
	$result2 = $conn->query($sql);
	if ($result2->num_rows > 0) {
		while($row2 = $result2->fetch_assoc()) {
			if(!in_array($row2["CityB"],$passed)){
				$distance = $distance + $row2["Distance"]*$row2["Difficulty"];
				
				$childReferences = Get_Children($row2["CityB"],$padding,$passed,$distance);
				
				//echo "<div id=".$row2["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>----".$row2["CityA"]." ".$row2["CityB"]." ".$row2["Distance"]." ".$distance." </div>";
				if(is_array($childReferences))
				{
					print_r("Parent");
					print_r($refrences);
					echo "</br>";
					print_r("Child");
					print_r($childReferences);
					echo "</br>";
					
					if(is_array($refrences)){
						foreach ($refrences as $key => $value) {
							echo $key."</br>";
							print_r($value);
							echo "</br>";
							if(array_key_exists($key,$childReferences)){
								print_r($childReferences[$key]);
								if($childReferences[$key][0]<$value[0]){
									$refrences[$key]=$childReferences[$key];
								}
								else
									$refrences[$key] = $refrences[$key];	
							}
						}
						$refrences = $refrences + $childReferences;
					}
					else
						$refrences = $childReferences;	
					print_r("Combined");
					print_r($refrences);
				}
			}
		}
		//echo "</br>";
	}
	
	echo "</br>";
	print_r($refrences);
	echo "</br>";
	return $refrences;
	$conn->close();
}

if(isset($_POST["Distance"]) &&  isset($_POST["Difficulty"])){
	//echo "Distance and Difficulty set";
	$sql = "UPDATE  `DungeonsAndDragons`.`Dmn_TradeRoute` SET  `Distance` =  '".$_POST["Distance"]."',`Difficulty` =  '".$_POST["Difficulty"]."' where CityA = ".$_GET["CityA"]." and CityB =  ".$_GET["CityB"]."";
	$sql2 = "UPDATE  `DungeonsAndDragons`.`Dmn_TradeRoute` SET  `Distance` =  '".$_POST["Distance"]."',`Difficulty` =  '".$_POST["Difficulty"]."' where CityA = ".$_GET["CityB"]." and CityB =  ".$_GET["CityA"]."";
	//echo $sql;
	//echo $sql2;
	$result = $conn->query($sql);
	if ($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			echo "here";
		}
	}
	$result = $conn->query($sql2);
	if ($result->num_rows > 0){
		while($row = $result->fetch_assoc()){
			echo "here";
		}
	}
}


$sql = "SELECT *,t2.CityName as CityAName,t3.CityName as CityBName,t2.Dmn_Cityid as CityAid,t3.Dmn_Cityid as CityBid
FROM  `Dmn_TradeRoute` AS t1
LEFT JOIN  `Dmn_City` AS t2 ON t1.CityA = t2.Dmn_Cityid
LEFT JOIN  `Dmn_City` AS t3 ON t1.CityB = t3.Dmn_Cityid where t1.CityA = ".$_GET["CityA"]." and t1.CityB =  ".$_GET["CityB"]."";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {

		//echo "<div id=".$row["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>".$row["CityAName"]. " to " .$row["CityBName"]." " .$row["Distance"]." " .$row["Difficulty"]."</div>";
		$sql = "SELECT *
			FROM  `Dmn_TradeRoute` AS t1
			where t1.CityA = ".$row["CityB"]." and t1.CityB =  ".$row["CityA"]."";
		$result2 = $conn->query($sql);
		if($result2->num_rows > 0){
			while($row2 = $result2->fetch_assoc()) {
				if($row["Distance"] <> $row2["Distance"] || $row["Difficulty"]  <> $row2["Difficulty"])
					echo '<form action="Trade_Routes_Edit.php?CityA='.$_GET["CityA"].'&CityB='.$_GET["CityB"].'" method="post">
						Distance: <input type="text" name="Distance"> '.$row["Distance"].' '.$row2["Distance"].'<br>
						Difficulty: <input type="text" name="Difficulty"> '.$row["Difficulty"].' '.$row2["Difficulty"].'<br>
						<input type="submit">
						</form>';
				else
					echo '<form action="Trade_Routes_Edit.php?CityA='.$_GET["CityA"].'&CityB='.$_GET["CityB"].'" method="post">
						Distance: <input type="text" name="Distance" value="'.$row["Distance"].'"> '.$row["Distance"].'<br>
						Difficulty: <input type="text" name="Difficulty" value="'.$row["Difficulty"].'"> '.$row["Difficulty"].'<br>
						<input type="submit">
						</form>';
			}
		}
		else{
			echo "False ".$sql;
			$sql = "INSERT INTO `DungeonsAndDragons`.`Dmn_TradeRoute` (`Dmn_TradeRouteid`, `CityA`, `CityB`, `Distance`, `Difficulty`) VALUES (NULL, '".$row["CityB"]."', '".$row["CityA"]."', '".$row["Distance"]."', '".$row["Difficulty"]."');";
			$result3 = $conn->query($sql);
		}

		echo "</br>";
	}
}
$avalable[] = array();
foreach ($resources as $key => $value) {
	$avalable = $avalable + $value;
}
//print_r($avalable);
foreach ($avalable as $key => $value) {
	$sql = "SELECT * FROM Dmn_Referance_Type as t1 where t1.idType = ".$key;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo "<div id=".$row["idType"]." style='display:inline;'>".$row["Referance_Type_Name"]." " .$value*$row["Create_Reference"]."</div>";
			echo "</br>";
		}
	}
}

$conn->close();
?>
<div id="response"></div>
