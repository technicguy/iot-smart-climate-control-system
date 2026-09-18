<?php
include("config.php");
require_once(CLS);
$iot = new Iot();


$fdata = $iot->getSetting();

$now = date("H", strtotime(TIME));
if($now > 0 && $now == $fdata[70][1]){
	$iot->saveInfo($fdata[72][1],"ppm_sw");
}
if($now > 0 && $now == $fdata[71][1]){
	$iot->saveInfo($fdata[73][1],"ppm_sw");
}
if($now > 0 && $now == $fdata[74][1]){
	$iot->saveInfo($fdata[75][1],"sw_sw");
}if($now > 0 && $now == $fdata[76][1]){
	$iot->saveInfo($fdata[77][1],"sw_sw");
}

