<?php
session_start();
include("class/spotClass.php");
$login=new spotClass();
$logged = $login->inicia($_POST['username'], md5($_POST['passwd']));
if($logged){
	header("Location: ./home/index.php");
}else{
	header("Location: ./index.php?code=2");
}

?>