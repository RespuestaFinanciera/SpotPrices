<?php
require_once("pg_connect.include.php");
$status =null;
$clave =null;


if(isset($_GET['status']) && isset($_GET['clave'])){
	$status= strtoupper($_GET['status']);
	$clave= $_GET['clave'];
}
$db = new dbOptions;

        print_r($db->switchApp($status,$clave));

?>