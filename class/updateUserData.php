<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$nombre=null;
$usuario=null;
$passwd=null;
$correo=null;


if($_GET['nombre'] && $_GET['usuario'] && $_GET['passwd'] && $_GET['correo']){
	$nombre= $_GET['nombre'];
	$usuario= strtoupper($_GET['usuario']);
	$passwd= $_GET['passwd'];
	$correo= $_GET['correo'];
}

	$db	=	new	dbOptions;
	print_r($db->updateUserData($nombre,$usuario,$passwd,$correo));
	
?>
