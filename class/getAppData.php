<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$username =null;
$clave=null;


if(isset($_GET['username']) && isset($_GET['clave'])){
	$username= strtoupper($_GET['username']);
	$clave = $_GET['clave'];
}else{
	echo "No Data";
	exit();
}

	$db	=	new	dbOptions;
	$res = $db->getAppData($username,$clave);
	print_r($res);
	
?>