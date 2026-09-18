<?php  session_start();
include("config.php");
require_once(CLS);
$iot = new Iot();
$set = $iot->getSetting();
require_once(HEADER);
?>
<div id="result"></div>   

<?php require_once(FOOTER);?>