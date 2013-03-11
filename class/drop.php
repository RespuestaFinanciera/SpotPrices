<?php
require_once("pg_connect.include.php");
$clave =null;


if(isset($_GET['clave'])){
	$clave= $_GET['clave'];
}
$db = new dbOptions;

        print_r($db->dropApp($clave));

?>