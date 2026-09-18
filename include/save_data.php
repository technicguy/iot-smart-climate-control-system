<?php 
include("../config.php");
require_once('class.php');
$iot = new Iot();


 if ($_SERVER["REQUEST_METHOD"] == "POST") {    


$temp_status = (isset($_POST['temp_status']) && $_POST['temp_status']=='on')? 'on':'off';
$temp_sw = (isset($_POST['temp_sw']) && $_POST['temp_sw']=='on')? 'on':'off';
$hum_status = (isset($_POST['hum_status']) && $_POST['hum_status']=='on')? 'on':'off';
$hum_sw = (isset($_POST['hum_sw']) && $_POST['hum_sw']=='on')? 'on':'off';
$ppm_status = (isset($_POST['ppm_status']) && $_POST['ppm_status']=='on')? 'on':'off';
$ppm_sw = (isset($_POST['ppm_sw']) && $_POST['ppm_sw']=='on')? 'on':'off';
$ppm_t1s = (isset($_POST['ppm_t1s']) && $_POST['ppm_t1s']=='on')? 'on':'off';
$ppm_t2s = (isset($_POST['ppm_t2s']) && $_POST['ppm_t2s']=='on')? 'on':'off';

$sw_status = (isset($_POST['sw_status']) && $_POST['sw_status']=='on')? 'on':'off';
$sw_sw = (isset($_POST['sw_sw']) && $_POST['sw_sw']=='on')? 'on':'off';
$sw_t1s = (isset($_POST['sw_t1s']) && $_POST['sw_t1s']=='on')? 'on':'off';
$sw_t2s = (isset($_POST['sw_t2s']) && $_POST['sw_t2s']=='on')? 'on':'off';

$reset = (isset($_POST['reset']) && $_POST['reset']=='on')? 'on':'off';
	
$postup=array('temp_status' => $temp_status,
				'temp_sw' => $temp_sw,
				'hum_status' => $hum_status,
				'hum_sw' => $hum_sw,
				'ppm_status' => $ppm_status,
				'ppm_sw' => $ppm_sw,
				'ppm_t1s' => $ppm_t1s,
				'ppm_t2s' => $ppm_t2s,
				'sw_t1s' => $sw_t1s,
				'sw_t2s' => $sw_t2s,
				'sw_status' => $sw_status,
				'sw_sw' => $sw_sw,
				'reset' => $reset,
			);	
$values= array_merge($_POST, $postup);	
	foreach ($values as $id=>$val) {
	$msg=$iot->saveInfo($val, $id);

	}	
 echo $msg;

}