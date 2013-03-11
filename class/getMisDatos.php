<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$username =null;


if(isset($_GET['username'])){
	$username= strtoupper($_GET['username']);
}

	$db	=	new	dbOptions;
	$res = $db->getDatosPanel($username);
	print_r($res);
	
?>