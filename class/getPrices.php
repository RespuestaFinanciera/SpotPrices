<?php
header('Content-type: application/json;charset: utf-8;');
$username = null;
$password = null;
if(isset($_GET['username']) && isset($_GET['password'])){
	$username = $_GET['username'];
	$password = $_GET['password'];
}else{
	echo "nodata";
	exit();

}
$token = file_get_contents("http://apps.prendario.com/spotPrices/token.php?username=".$username."&password=".$password);
$token = json_decode($token);
if($token->status != 'sucess'){
	echo $token->status;
	exit();
}

$token = $token->response->access_token;
$request = array(
					"type"=>"AU",
					"currency"=>"MXN"
				);
$request = json_encode($request);

$url = "http://apps.prendario.com/spotPrices/api_get.php?access_token=".$token;
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
$returned = curl_exec($ch);


print_r($returned);
?>