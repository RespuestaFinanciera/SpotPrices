<?php
header("Content-type: application/json");
require_once("pg_connect.include.php");
$user = "nouser";
$db = new dbOptions;
print_r($db->getPacksList());

?>