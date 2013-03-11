<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$user =null;
$action= null;
$name= null;
$descripcion=null;
$clave=null;
$cnombre=null;
$cmail=null;
$crfc=null;
$membership=null;
$arr=null;
if($_GET['action'] == "add" && isset($_GET['action'])){
	if($_GET['user'] && $_GET['action'] && $_GET['name'] && $_GET['descripcion'] && $_GET['clave'] && $_GET['cnombre'] && $_GET['cmail'] && $_GET['crfc'] && $_GET['membership']){
		$db	=	new	dbOptions;
		$user= strtoupper($_GET['user']);
		$name= strtoupper($_GET['name']);
		$name = str_replace(" ", "_", $name);
		$descripcion= $_GET['descripcion'];
		$clave= $_GET['clave'];
		$cnombre= $_GET['cnombre'];
		$cmail= $_GET['cmail'];
		$crfc= $_GET['crfc'];
		$membership= $_GET['membership'];
			print_r($db->addApp($user,$name,$descripcion,$clave,$cnombre,$cmail,$crfc,$membership));
			return;

	}
}

if($_GET['action'] == "edit" && isset($_GET['action'])){
	if($_GET['clave'] && $_GET['arr'] && $_GET['membership']){

		$clave= $_GET['clave'];
		$requests = $_GET['arr'];
		$membership= $_GET['membership'];
		print_r($requests);
		exit();
		print_r($db->editApp($clave,$requests,$membership));
		return;
	}

}
echo "nodata";
exit();	
?>