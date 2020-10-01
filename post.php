<?php
//$arr =  ;
if(isset($_POST['postText'])) {
$phpArray = json_decode($_POST['postText'], true);
foreach ($phpArray as $key => $value) { 
    echo "$key </br>";
    foreach ($value as $k => $v) { 
        echo "$k | $v <br />"; 
    }
}
}
else{
	echo "No post data in postText </br>";
	echo "enter data at <a href='test.php'>test.php</a>";
	
}
?>