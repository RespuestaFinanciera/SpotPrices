<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$correo=null;
if($_GET['email'] && $_GET['resend'] == "true"){
		$correo= $_GET['email'];
	    if (preg_match(
	    '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
	    $correo)) {
	    $db	=	new	dbOptions;
		print_r($db->resendMail($correo));
		return;
	    }

	}
if($_GET['email']){
	$correo= $_GET['email'];
    if (preg_match(
    '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
    $correo)) {
    $db	=	new	dbOptions;
	print_r($db->insertaUsuario($correo));
	return;
    }
}
return false;
	
	
?>