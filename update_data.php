<?php
include("config.php");
require_once(CLS);
$iot = new Iot();


header("Pragma: no-cache");
header('Content-Type: application/json');

if(isset($_GET['hum']) && isset($_GET['tem'])){

	$data = $iot->update();
	$data = json_decode($data);
	echo $data->message;
	
}