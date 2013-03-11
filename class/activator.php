<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$correo =null;
$timestamp= null;


if(isset($_GET['correo']) && isset($_GET['timestamp'])){
	$correo= $_GET['correo'];
	$timestamp= $_GET['timestamp'];
}

	$db	=	new	dbOptions;
	print_r($db->getCorreo($correo,$timestamp));
	
?>