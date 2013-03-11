<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$clave =null;


if(isset($_GET['clave'])){
	$clave= $_GET['clave'];
}else{
	echo "nodata";
	exit();
}

	$db	=	new	dbOptions;
	$res = $db->getRequests($clave);
	print_r($res);
	
?>