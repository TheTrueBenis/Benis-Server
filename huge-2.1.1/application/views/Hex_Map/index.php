<div class="content">
    <?php
	//setcookie("Char_id", "123456", NULL, "/","", 0);
	//$_SESSION["favcolor"] = "yellow";
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// --- Define some constants
$MAP_WIDTH = 25;
$MAP_HEIGHT = 25;
$HEX_HEIGHT = 72;
$HEX_SCALED_HEIGHT = $HEX_HEIGHT * 1.0;
$HEX_SIDE = $HEX_SCALED_HEIGHT / 2;

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

$sql = "SELECT * FROM Hex_Map";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) {
		$map2[$row["X"]][$row["Y"]][1] = $row["Description"];
	}
} else {
	echo "0 results";
}
$conn->close();

?>

<html>
    <head>
        <!-- Stylesheet to define map boundary area and hex style -->
        <style type="text/css">
        body {
            /* 
            margin: 0;
            padding: 0;
            */
        }

        .hexmap {
            width: <?php echo $MAP_WIDTH * $HEX_SIDE * 1.5 + $HEX_SIDE/2; ?>px;
            height: <?php echo $MAP_HEIGHT * $HEX_SCALED_HEIGHT + $HEX_SIDE; ?>px;
            position: relative;
            background: #000;
        }

        .hex-key-element {
            width: <?php echo $HEX_HEIGHT * .75; ?>px;
            height: <?php echo $HEX_HEIGHT * .75; ?>px;
            border: 1px solid #fff;
            float: left;
            text-align: center;
        }

        .hex {
            position: absolute;
            width: <?php echo $HEX_SCALED_HEIGHT ?>;
            height: <?php echo $HEX_SCALED_HEIGHT ?>;
        }
        </style>
    </head>
    <body>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
	
    <script>
	
function handle_map_click(event) {

	var posx = 0;
	var posy = 0;
	var mapa = <?php echo json_encode($map2)  ?>;
	if (event.pageX || event.pageY) {
		posx = event.pageX;
		posy = event.pageY;
	} else if (event.clientX || e.clientY) {
		posx = event.clientX + document.body.scrollLeft
			+ document.documentElement.scrollLeft;
		posy = event.clientY + document.body.scrollTop
			+ document.documentElement.scrollTop;
	}

    var map = document.getElementById('hexmap');
	posx = posx - map.offsetLeft;
	posy = posy - map.offsetTop;

    var hex_height = <?php echo $HEX_SCALED_HEIGHT; ?>;
    x = (posx - (hex_height/2)) / (hex_height * 0.75);
    y = (posy - (hex_height/2)) / hex_height;
    z = -0.5 * x - y;
    y = -0.5 * x + y;

    ix = Math.floor(x+0.5);
    iy = Math.floor(y+0.5);
    iz = Math.floor(z+0.5);
    s = ix + iy + iz;
    if (s) {
        abs_dx = Math.abs(ix-x);
        abs_dy = Math.abs(iy-y);
        abs_dz = Math.abs(iz-z);
        if (abs_dx >= abs_dy && abs_dx >= abs_dz) {
            ix -= s;
        } else if (abs_dy >= abs_dx && abs_dy >= abs_dz) {
            iy -= s;
        } else {
            iz -= s;
        }
    }

    map_x = ix;
    map_y = (iy - iz + (1 - ix %2 ) ) / 2 - 0.5;

    tx = map_x * <?php echo $HEX_SIDE ?> * 1.5;
    ty = map_y * <?php echo $HEX_SCALED_HEIGHT ?> + (map_x % 2) * (<?php echo $HEX_SCALED_HEIGHT ?> / 2);

    var highlight = document.getElementById('highlight');
	var desc = document.getElementById('desc')
	var terr = document.getElementById('terrain')

    highlight.style.left = tx + 'px';
    highlight.style.top = ty + 'px';
	
	terr.style.left = tx+80 + 'px';
    terr.style.top = ty+0 + 'px';
	terr.style.display = "block"
	
	desc.value = mapa[map_x][map_y][1] ;
}

function handle_terrain_click(img,terr) {
	var edit = document.getElementById(map_x+"-"+map_y)
	if(edit == null){
	myDiv = $('<img src="" alt="'+terr+'" id="'+map_x+'-'+map_y+'" class="hex" style="zindex:99;left:'+tx+'px;top:'+ty+'px">   ')
	        .appendTo('div.hexmap');
	var edit = document.getElementById(map_x+"-"+map_y)	
	edit.src = img;
	}
	else{
	edit.src = img;
	}
	$url = "./Hex_alter?X="+map_x+"&Y="+map_y;
	$.ajax({
    url: $url+'&terr='+terr,
    context: document.body
    }).done(function(data) {
		
    });
    return false;
}
function handle_close_click(img,terr) {
	var terr = document.getElementById('terrain')
	terr.style.display = "none"
}

function handle_save_click(){
	
	$url = "./Hex_alter_desc";
	
	$.ajax({
	type: "POST",
    url: $url,
	data: 	"X="+map_x+
			"&Y="+map_y+
			"&Type=Desc"+
			"&desc="+desc.value,
    context: document.body
    }).done(function(data) {
    });
    return false;
}
	

</script>
<?php

// ----------------------------------------------------------------------
// --- This is a list of possible terrain types and the
// --- image to use to render the hex.
// ----------------------------------------------------------------------
    $terrain_images = array("g"	=> "grass-r1.png",
                            "d"	=> "dirt.png",
                            "w"	=> "coast.png",
                            "p"	=> "stone-path.png",
                            "s"	=> "water-tile.png",
                            "de"=> "desert.png",
                            "o"	=> "desert-oasis-tile.png",
                            "f"	=> "forested-mixed-summer-hills-tile.png",
                            "h"	=> "hills-variation3.png",
                            "m"	=> "mountain-tile.png");


    // ==================================================================

    function render_map_to_html() {
		$MAP_WIDTH = 20;
		$MAP_HEIGHT = 20;
		$HEX_HEIGHT = 72;
		$HEX_SCALED_HEIGHT = $HEX_HEIGHT * 1.0;
		$HEX_SIDE = $HEX_SCALED_HEIGHT / 2;

        // -------------------------------------------------------------
        // --- Draw each hex in the map
        // -------------------------------------------------------------
        $terrain_images = array("g"    => "grass-r1.png",
                            "d"     => "dirt.png",
                            "w"    => "coast.png",
                            "p"     => "stone-path.png",
                            "s"    => "water-tile.png",
                            "de"   => "desert.png",
                            "o"    => "desert-oasis-tile.png",
                            "f"   => "forested-mixed-summer-hills-tile.png",
                            "h"    => "hills-variation3.png",
                            "m" => "mountain-tile.png");
		
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

		$sql = "SELECT * FROM Hex_Map";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
			// output data of each row
			while($row = $result->fetch_assoc()) {
				
				//echo "Name: <a href='/Character.php?ID=" . $row["ID"] ." '>" . $row["X"]. "</a>";
				
				$terrain = $row["Terrain"];
				$id = $row["X"]."-".$row["Y"];
				// --- Image to draw
				$img = $terrain_images[$terrain];
				echo $img;
				// --- Coordinates to place hex on the screen
				$tx = $row["X"] * $HEX_SIDE * 1.5;
				$ty = $row["Y"] * $HEX_SCALED_HEIGHT + ($row["X"] % 2) * $HEX_SCALED_HEIGHT / 2;

				// --- Style values to position hex image in the right location
				$style = sprintf("left:%dpx;top:%dpx", $tx, $ty);

				// --- Output the image tag for this hex
				print "<img src='/huge-2.1.1/public/img/Hex/$img' alt='$terrain' id='$id' class='hex' style='zindex:99;$style'>\n";
				//print "<img src='dungeon.png' alt='$terrain' class='hex' style='zindex:100;$style'>\n";
				
			}
		} else {
			echo "0 results";
		}
		$conn->close();
		
    }

    ?>
	

    <!-- Render the hex map inside of a div block -->
    <div id='hexmap' class='hexmap' ondblclick='handle_map_click(event);'>
        <?php render_map_to_html(); ?>
        <img id='highlight' class='hex' src='/huge-2.1.1/public/img/Hex/hex-highlight.png' style='zindex:100;'>
    </div>

    <!--- output a list of all terrain types -->
    <br/>
	<div id='terrain' style="position:absolute;background-color:white;width:280px;display:none">
	<div>
		<INPUT TYPE = "Text" id='desc' style = "display: inline-block; width:70%;margin-bottom:0px">
		<button onclick="handle_save_click()" style = "display: inline-block; width:15%;padding:0px;vertical-align:bottom">Save</button>
		<button onclick="handle_close_click()" style = "display: inline-block; width:10%;float:right">X</button></div>
    <?php 
        reset ($terrain_images);
        while (list($type, $img) = each($terrain_images)) {
            print "<div  class='hex-key-element 'onclick=\"handle_terrain_click('/huge-2.1.1/public/img/Hex/$img','$type')\"><img src='/huge-2.1.1/public/img/Hex/$img' alt='$type' style='width:35px'><br/>$type</div>";
        }
    ?>
	</div>
    </body>
</html>

</div>
