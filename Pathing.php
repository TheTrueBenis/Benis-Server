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
	//echo "</br>";
	$conn = new mysqli($servername, $username, $password, $dbname);
	
	$paddingValue = "padding-left:".$padding."px;";
	$padding = $padding + 1;
	if($padding > 10)
		return "Overload";
	if($distance > 100000)
		return "Over Distance";
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	$sql = "SELECT * FROM Dmn_City as t1 where t1.Dmn_Cityid = ".$id;
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$cityName = $row["CityName"];
		}
	}
	
	$sql = "SELECT * FROM Dmn_Resources as t1 where t1.Dmn_City = ".$id;
	$result2 = $conn->query($sql);
	if ($result2->num_rows > 0) {
		$Locations[$id][0] =  $distance;
		$resources[0] = array($distance,$id);
		while($row2 = $result2->fetch_assoc()) {
			if(!in_array($row2["CityB"],$passed)){
				$resources[$row2["Dmn_Referance_Type"]] = ($row2["Referance_Amt"]/(1+($distance/35)));
				$LocalResouces[$row2["Dmn_Referance_Type"]] = ($row2["Referance_Amt"]/(1+($distance/35)));
			}
			$Locations[$id][1] = $LocalResouces;
			$refrences[$id] = $resources;
		}
		//print_r($Locations);
		//echo "</br>";
	}
	
	$sql = "SELECT * FROM Dmn_TradeRoute as t1 where t1.CityA = ".$id;
	$result2 = $conn->query($sql);
	if ($result2->num_rows > 0) {
		while($row2 = $result2->fetch_assoc()) {
			if(!in_array($row2["CityB"],$passed)){
				$distance2 = $distance + $row2["Distance"]*$row2["Difficulty"];
				
				$childReferences = Get_Children($row2["CityB"],$padding,$passed,$distance2);
				//echo "<div id=".$row2["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>----".$row2["CityA"]." ".$row2["CityB"]." ".$row2["Distance"]." ".$distance." </div>";
				if(is_array($childReferences))
				{
					/*
					print_r("Parent");
					print_r($Locations);
					echo "</br>";
					print_r("Child");
					print_r($childReferences);
					echo "</br>";
					*/
					if(is_array($Locations)){
						foreach ($Locations as $key => $value) {
							//echo $key."</br>";
							//print_r($value);
							//echo "</br>";
							
							if(array_key_exists($key,$childReferences)){
								//print_r($childReferences[$key]);
								if($childReferences[$key][0]<$value[0]){
									$Locations[$key][0]=$childReferences[$key][0];
									$Locations[$key][1]=$childReferences[$key][1];
								}
								else
									$Locations[$key] = $Locations[$key];	
							}
						}
						$Locations = $Locations + $childReferences;
					}
					else
						$Locations = $childReferences;	
					//print_r("Combined");
					//print_r($refrences);
				}
			}
		}
		//echo "</br>";
	}
	/*
	echo "</br>";
	print_r($refrences);
	echo "</br>";
	*/
	return $Locations;
	
	$conn->close();
}
if ($_GET["ID"] !== null)
	$LocationsRefs = Get_Children($_GET["ID"],0,array(),0);
else
	print_r(Get_Children(0,0,0,0));

foreach ($LocationsRefs as $key => $value) {
	/*
	print_r($key);
	echo("</br>");
	print_r($value);
	echo("</br>");
	*/
	foreach ($value[1] as $key2 => $value2) {
		//print_r($value2);
		//echo("</br>");
		if(array_key_exists($key2,$resources)){
			//echo("here");
			$resources[$key2] =	$resources[$key2] + $value2;
		}
		else
			$resources[$key2] = $value2;
	}
}
print_r($resources);

$sql = "SELECT * FROM Dmn_City as t1 where t1.Dmn_Cityid = ".$_GET["ID"];
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		echo "<div id=".$row["Dmn_Cityid"]." style='display:inline;".$paddingValue."'>".$padding. " " .$row["CityName"]."</div>";
		echo "</br>";
	}
}

$sql = "SELECT `Dmn_Referance_Type`,t2.Create_Reference ,sum(`Referance_Amt`) as sumamt FROM `Dmn_Resources` as t1 left join Dmn_Referance_Type as t2 on t1.Dmn_Referance_Type = t2.idType GROUP BY `Dmn_Referance_Type`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		//echo "<div id=".$row["Dmn_Referance_Type"]." style='display:inline;".$paddingValue."'>".$row["Dmn_Referance_Type"]. " " .$row["sumamt"]." " .((($row["sumamt"]*1360)/$row["Create_Reference"])*1000)."</div>";
		//echo "</br>";
		$WorldValue[$row["Dmn_Referance_Type"]] = array($row["sumamt"],((($row["sumamt"]*1360)/$row["Create_Reference"])*1000));
		}
}
/*
$sql = "SELECT `Dmn_Referance_Type`,sum(`Referance_Amt`) as sumamt FROM `Dmn_Resources` GROUP BY `Dmn_Referance_Type`";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		//echo "<div id=".$row["Dmn_Referance_Type"]." style='display:inline;".$paddingValue."'>".$row["Dmn_Referance_Type"]. " " .$row["sumamt"]."</div>";
		//echo "</br>";
		$WorldValue[$row["Dmn_Referance_Type"]] = $row["sumamt"];
		}
}

//print_r($WorldValue);
$avalable[] = array();
foreach ($resources as $key => $value) {
	$avalable = $avalable + $value;
}
*/
$avalable = $resources;
//print_r($avalable);
echo "<table>";
foreach ($avalable as $key => $value) {
	$sql = "SELECT * FROM Dmn_Referance_Type as t1 where t1.idType = ".$key;
	echo "<tr>";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			echo "<td id=".$row["idType"]." style='display:inline;'>".$row["Referance_Type_Name"]."</td><td>" .$value. "</td><td>" .$WorldValue[$key][0]."</td><td>" .$WorldValue[$key][1]/(($value/$WorldValue[$key][0])/.05)."</td>";
			$LocalValues[$key] = $WorldValue[$key][1]/(($value/$WorldValue[$key][0])/.05);
			//echo "</br>";
		}
	}
	echo "</tr>";
}
echo "</table>";
//print_r($LocalValues);
echo "</br>";
echo "Iron Puddled</br>";
echo $LocalValues[23]*91 ." ". $LocalValues[42] . " " . $LocalValues[8];
echo "</br>";
echo $LocalValues[23]*91+$LocalValues[42]+$LocalValues[8];

echo "</br>Split Time</br>";
$formula = "r23*91+r42*1+r8+1";
$re = "(r\d*)|([-+*\/])|(\d*)";
preg_match_all($re, $formula, $matches);
print_r($matches);

$re = '/([rm]*?\d.?)([+*-\/])([.]?\d*)(.)?/';
$str = 'r23*91+rm42*1+r8+1';

preg_match_all($re, $str, $matches, PREG_SET_ORDER, 0);

// Print the entire match result
var_dump($matches);
foreach ($matches as $key => $value) {
	foreach ($value as $key2 => $value2) {
		if($key2 <> 0){
			print_r($value2);
			echo "</br>";
		}
	}
}

$conn->close();
?>
<div id="response"></div>
