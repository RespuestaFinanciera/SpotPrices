<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");

$username	=	"user";
$passwd		=	"passwd";

if((isset($_GET['username'])) && (isset($_GET['passwd']))){
	$username	=	strtoupper($_GET['username']);
	$passwd		=	$_GET['passwd'];
}
$db	=	new	dbOptions;
print_r($db->login($username,$passwd));
?>