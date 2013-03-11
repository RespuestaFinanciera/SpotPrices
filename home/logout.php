<?php

//inicio de sesion
session_start();
// ** Logout the current user. **
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  $_SESSION['iduser'] = NULL;
  unset($_SESSION['iduser']);
  //$_SESSION['nombre'] = NULL;
  //unset($_SESSION['nombre']);
	
  $logoutGoTo = "../index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}


?>