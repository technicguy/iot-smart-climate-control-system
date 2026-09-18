<?php
define ('DB_USER', 'root');
define ('DB_PASS', '');
define ('DB_HOST', 'localhost');
define ('DB_NAME', 'dev_iot');
define ("DB_PORT", "");
//define ("URL", "http://homestaypokhara.com/iot/");
define ("URL", "http://localhost/iot/");


$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = array(
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
);

try {
    $db = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    $error = $e->getMessage();
    // Handle the error here - log it, display it, etc.
}
		

date_default_timezone_set("Asia/Kathmandu");

define('DATE', date("Y-m-d"));
define('TIME', date("h:i:s A"));
define("DATE_TIME", date("Y-m-d H:i:s A"));
define("HEADER", "include/header.php");
define("FOOTER", "include/footer.php");
define("CLS", "include/class.php");		
		