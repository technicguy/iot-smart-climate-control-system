<?php
include("../config.php");

echo "Send from Server Message";

if(isset($_GET['humidity']) && isset($_GET['temperature'])){
        $query = "INSERT INTO dh11 SET temp = '".$_GET['temperature']."', hum = '".$_GET['humidity']."'";
        $insert = $db->query($query);	
	
}
