<?php session_start();
include("../config.php");
require_once('../'.CLS);
$iot = new Iot();

if(isset($_POST['user']) && isset($_POST['pwd'])){
	$data = $iot->login($_POST['user'],$_POST['pwd']);
	echo $data;
	
}



?>

