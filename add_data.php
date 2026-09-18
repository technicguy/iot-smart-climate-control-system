<?php
include("config.php");
require_once(CLS);
$iot = new Iot();

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//header('Content-Type: application/json');

if(isset($_GET['sensor']) && isset($_GET['data'])){
	
	//$tem = isset($_GET['indata'])? $_GET['indata']:'';
	
	$get = array("wet" => $_GET['data'], "tem" => $_GET['indata']);
	
	$data = $iot->addItem($_GET['sensor'],$get);	
	
	//$data = json_decode($data);
	echo $data;

	
}