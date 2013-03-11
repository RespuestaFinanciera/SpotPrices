<?php
require_once("pg_connect.include.php");
$username =null;


if(isset($_GET['username'])){
	$username= strtoupper($_GET['username']);
}
$db = new dbOptions;

        print_r($db->readApps($username));

?>