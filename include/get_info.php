<?php
include("../config.php");
require_once('class.php');

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
	header('Content-Type: application/json');
$iot = new Iot();

$data1 = json_decode($iot->getWeather());
$data2 = json_decode($iot->getMaxinfo("soil"));



$data = array('tem' => $data1->tem,
			  'hum' => $data1->hum,
			  'ppm' => $data1->ppm,
			  'wet' => $data2->wet,
			  'stem' => $data2->tem,
			  'sdate' => $data2->date
			);


echo json_encode($data);

