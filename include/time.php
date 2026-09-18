<?php 
include("../config.php");
echo date("l, jS F Y", strtotime(DATE));
echo date(" g:i:s a", strtotime(TIME));?>