<?php
require_once("sendMail.php");

class dbOptions{

	private $dbConnection = null;
	private $_SITE_ROOT;
	function __construct(){
		$this->_SITE_ROOT = $_SERVER['DOCUMENT_ROOT']."/SpotPrices/";
		include($this->_SITE_ROOT."config.php");
		$this->dbConnection = pg_connect("dbname=$_DB_NAME user=$_DB_USER password=$_DB_PSWD host=$_DB_HOST");
	}
	function result_construct($action,$data){
		$result = array("action"=>$action,"response"=>array("data"=>$data));
		$result = json_encode($result);
		return $result;
	}
	function login($user,$passwd){
		pg_prepare($this->dbConnection,"login","select exists(select username from usuarios where username = $1 and passwd =$2 and status='ON' limit 1)::integer as data");
		$result = pg_execute($this->dbConnection,"login",array($user,$passwd));
		if(pg_last_error()){
			return $this->result_construct("error",pg_last_error());
		}else{
			return $this->result_construct("success",pg_fetch_assoc($result));
		}
	}
	function insertaUsuario($correo){
			$mail= new sendMail;
			pg_prepare($this->dbConnection,"insertar","insert into usuarios (email,status) select $1 as email, $2 as status where not exists(select email from usuarios	where email = $1) returning email,status");
	      		$retorno = pg_execute($this->dbConnection,"insertar",array($correo,"OFF"));
	      		if(pg_last_error()){
	   				return $this->result_construct("error",array("action"=>false));
				}else{
					$retorno = pg_fetch_assoc($retorno);
					if($retorno['email'] == $correo){
						$mailer=$mail->enviarCorreo($correo);
					}else{
						return $this->result_construct("success",array("action"=>false,"msg"=>"This email is in use by another person, please use a different account.<br/><small>(Email not received yet or code expired? Click resend button)</small> ","inuse"=>true));
					}
				}
			if($mailer){
				$msg = "We just have sent a message to the provided email:<br/><b>".$correo."</b><br/> 
		            Please check your inbox, if you can't see the message,<br/>
		            please check your spam or junk-mail folder and move it to your inbox folder.";
		            return $this->result_construct("success",array("action"=>true,"msg"=>$msg));
				
			}else{
	            $msg = "Sorry, we could'nt send your message in this moment, please try again or wait a few minutes.";
	        	return $this->result_construct("success",array("action"=>true,"msg"=>$msg));
			}
	}
	function resendMail($correo){
		$mail= new sendMail;
		$mailer=$mail->enviarCorreo($correo);
		if($mailer){
				$msg = "We just have resent a message to the provided email:<br/><b>".$correo."</b><br/> 
		            Please check your inbox, if you can't see the message,<br/>
		            please check your spam or junk-mail folder and move it to your inbox folder.";
		            return $this->result_construct("success",array("action"=>true,"msg"=>$msg));
				
			}else{
	            $msg = "Sorry, we could'nt send your message in this moment, please try again or wait a few minutes.";
	        	return $this->result_construct("success",array("action"=>true,"msg"=>$msg));
			}

	}
	function getCorreo($correo,$timestamp){

		if(!$correo || !$timestamp){
			$msg = "no data";
	        	return $this->result_construct("success",array("action"=>false,"msg"=>$msg));
		
		}
		$correo=base64_decode(base64_decode($correo));
		$timestamp=base64_decode(base64_decode($timestamp));
		$timestamp=strtotime($timestamp)+ 86400;
		$limite=strtotime(date("Y-m-d H:i:s"));
		if($limite>$timestamp){
			$msg = "Sorry, your activation period has expired. Please visit the next link to request a new code.<br/><a class='adark' href='index.php'>New request</a>";
	        	return $this->result_construct("success",array("action"=>false,"msg"=>$msg));
		} else {
			$msg = "In order to activate your account, provide the following information for your profile (all information you provide will be treated confidentially)";
	        	return $this->result_construct("success",array("action"=>true,"msg"=>$msg));

		}

	}
	function updateUserData($nombre,$usuario,$passwd,$correo){
			$correo=base64_decode(base64_decode($correo));
			pg_prepare($this->dbConnection,"modifica", "update usuarios set  nombre=$1, username= $2, passwd= $3, status='ON' where email = $4 and status ='OFF' returning status");
			$modifica = pg_execute($this ->dbConnection , "modifica", array ($nombre,$usuario,$passwd,$correo));
			if(pg_last_error()){
				return $this -> result_construct ("error", pg_last_error(),array("action" =>false ,"msg"=>"Activation failed, please retry in a few moments."));
			}
			$modifica=pg_fetch_assoc($modifica);
			if(!$modifica){
					return $this -> result_construct ("error",array("action" =>false ,"msg"=>"This account is already activated or your link invalid."));
			}else{
				return $this->result_construct("success",array("action" =>true ,"msg"=>"Congratulations, your account has been created!<br/>(".$correo.").<br/>To monetize monthly your app YOU SAY THE PRICE and get 50% or 30%, to obtain 50% of the monthly income check this requirements<br/><a class='adark' href='requirements.php' target='_blank'>Read me</a><br/> or login now <br/><a class='adark' href='index.php' target='_self'>LOG IN</a>"));
			}
	}
	function addApp($user,$name,$descripcion,$clave,$cnombre,$cmail,$crfc,$membership){
		pg_prepare($this->dbConnection,"addApp","insert into apps (usuario,nombre,descripcion,clave,client_name,client_email,client_rfc,membership) select $1 as usuario, $2 as nombre, $3 as descripcion, $4 as clave, $5 as client_name, $6 as client_email, $7 as client_rfc, $8 as membership where not exists(select nombre, clave from apps	where nombre =$2 or clave =$4) returning nombre,clave");
	      		$retorno = pg_execute($this->dbConnection,"addApp",array($user,$name,$descripcion,$clave,$cnombre,$cmail,$crfc,$membership));
	      		if(pg_last_error()){
	   				return $this->result_construct("error",array("action"=>false,"msg"=>pg_last_error()));
				}else{
					$retorno = pg_fetch_assoc($retorno);
					if($retorno['nombre'] == $name && $retorno['clave'] == $clave){
						$set_request= $this->setAppREquest($clave);
						return $this->result_construct("success",array("action"=>true,"msg"=>"Your app has been added. req: ".$set_request));
					}else{
						return $this->result_construct("success",array("action"=>false,"msg"=>"This app name or identifier are already in use, try another one please."));
					}
				}
	}
	function editApp($clave,$requests,$membership){
		pg_prepare($this->dbConnection,"editApp","update apps set  membership = $1 where clave = $2 and status ='OFF' returning clave");
	      		$retorno = pg_execute($this->dbConnection,"editApp",array($membership,$clave));
	      		if(pg_last_error()){
	   				return $this->result_construct("error",array("action"=>false,"msg"=>pg_last_error()));
				}else{
					$retorno = pg_fetch_assoc($retorno);
					if($retorno['clave'] == $clave){
						$edit_request= $this->editAppREquest($clave,$requests);
						return $this->result_construct("success",array("action"=>true,"msg"=>"Your app has been edited. req: ".$edit_request));
					}else{
						return $this->result_construct("success",array("action"=>false,"msg"=>"You must turn off this service first."));
					}
				}
	}
	function setAppREquest($clave){
		pg_prepare($this->dbConnection,"setReq","insert into sv_request (clave) select $1 as clave where not exists(select clave from sv_request where clave =$1) returning clave");
  		$retorno = pg_execute($this->dbConnection,"setReq",array($clave));
  		$retorno = pg_fetch_assoc($retorno);
  		if(pg_last_error()){
				return pg_last_error();
		}else{
			if($retorno['clave'] == $clave){
				return true;
			}else{
				return false;
			}

		}

	}
	function editAppREquest($clave,$requests){
		$arr = array("usd"=>0,"eur"=>0,"mxn"=>0,"brl"=>0,"au"=>0,"ag"=>0,"cu"=>0,"pt"=>0);
		foreach ($arr as $key => $value) {
			
		}
		pg_prepare($this->dbConnection,"editReq","update sv_request set usd=$1, eur=$2, mxn=$3, brl=$4, au=$5, ag=$6, cu=$7, pt=$8 where clave = $9 returning clave ");
  		$retorno = pg_execute($this->dbConnection,"editReq",array($clave));
  		$retorno = pg_fetch_assoc($retorno);
  		if(pg_last_error()){
				return pg_last_error();
		}else{
			if($retorno['clave'] == $clave){
				return true;
			}else{
				return false;
			}

		}

	}
	function readApps($username){
		pg_prepare($this->dbConnection,"appList","select * from apps where usuario =$1 order by nombre");
		$appList=pg_execute($this->dbConnection,"appList",array($username));
		$respuesta = array();
			while($r=pg_fetch_assoc($appList)){
				$respuesta[] = $r;
			}
		if(pg_last_error()){
				return $this -> result_construct ("error", pg_last_error());
			}else{
				return json_encode($respuesta);
			}

	}
	function switchApp($status,$clave){
		if ($status == 'ON') {
			$status = 'OFF';
		}else{
			$status = 'ON';
		}
		pg_prepare($this->dbConnection,"switchApp","update apps set status = $1 where clave =$2");
		$switch=pg_execute($this->dbConnection,"switchApp",array($status,$clave));
		$switch=pg_fetch_assoc($switch);
		if(pg_last_error()){
				return $this -> result_construct ("error",array("action"=>false,"msg"=>pg_last_error()));
			}else{
				return $this -> result_construct ("success",array("action"=>true,"msg"=>"Service switched ".$status."."));
			}
	}
	function dropApp($clave){
		pg_prepare($this->dbConnection,"dropApp","delete from apps where status = 'OFF' AND clave =$1 returning clave");
		$drop=pg_execute($this->dbConnection,"dropApp",array($clave));
		$drop=pg_fetch_assoc($drop);
		if(pg_last_error()){
				return $this -> result_construct ("error",array("action"=>false,"msg"=>pg_last_error()));
			}else{
				if(!$drop){
					return $this -> result_construct ("success",array("action"=>false,"msg"=>"Cannot delete a running service."));
				}else{
					$request_drop = $this->dropRequest($clave);
					return $this -> result_construct ("success",array("action"=>true,"msg"=>"Service deleted. (req: ".$request_drop.")"));	
				}
				
			}

	}
	function dropRequest($clave){
		pg_prepare($this->dbConnection,"dropReq","delete from sv_request where clave =$1 returning clave");
		$drop=pg_execute($this->dbConnection,"dropReq",array($clave));
		$drop=pg_fetch_assoc($drop);
		if(pg_last_error()){
				return pg_last_error();
			}else{
				if(!$drop){
					return false;
				}else{
					return true;
				}
				
			}

	}
	function getRequests($clave){
		pg_prepare($this->dbConnection,"getReq","select * from sv_request where clave =$1 limit 1");
		$result=pg_execute($this->dbConnection,"getReq",array($clave));
		if(pg_last_error()){
			return $this->result_construct("error",pg_last_error());
		}else{
			$respuesta = pg_fetch_assoc($result);
			return $this->result_construct("success",$respuesta);
		}
	}
	function getPacksList(){
		$result = pg_query($this->dbConnection,"select * from packs order by id");
		if(pg_last_error()){
			return $this->result_construct("error",pg_last_error());
		}else{
			$respuesta = array();
			while($r = pg_fetch_assoc($result)){
				$respuesta[] = $r;
			}
			return $this->result_construct("success",$respuesta);
		}
	}
		

	function getDatosPanel($username){

		pg_prepare($this->dbConnection,"datosPanel","select nombre, username, email from usuarios where username =$1 limit 1");
		$usrData=pg_execute($this->dbConnection,"datosPanel",array($username));
		$usrData=pg_fetch_assoc($usrData);
		if(pg_last_error()){
				return $this -> result_construct ("error", pg_last_error());
			}else{
				return json_encode($usrData);
			}
	}

	function getAppData($username,$clave){
		pg_prepare($this->dbConnection,"datosApp","select * from vw_refresh where usuario =$1 and unique_id=$2 limit 1");
		$appData=pg_execute($this->dbConnection,"datosApp",array($username,$clave));
		$appData=pg_fetch_assoc($appData);
		if(pg_last_error()){
				return $this -> result_construct ("error", pg_last_error());
			}else{
				return json_encode($appData);
			}
            
    }
	

}

?>