<?php
require_once("PHPMailer_v5.1/class.phpmailer.php");

class sendMail{

	 	public $classUrl = "http://localhost/SpotPrices/class/";
 		public $Url = "http://localhost/SpotPrices/";
		public function enviarCorreo($correo){
		        $link = base64_encode(base64_encode($correo));
		        $time = date("d-m-Y H:i:s");
		        $timeh = base64_encode(base64_encode($time));
		        $link = "<a href='".$this->Url."activation.php?mdc=".$link."&mdt=".$timeh."'>Account activation.</a>";
		        $EmailDestino = $correo;
		        $Asunto = "Account activation";
		        $CuerpoHtml = "For activating your account please click the next link, if you don't required this activation, ignore this message.<br/>".$link."<br/>Att.:<b>SpotPrices.biz</b>";
		        $NombreDestino = "New user";
		        $mailer = $this->EnviarEmail($EmailDestino,$NombreDestino,$Asunto,$CuerpoHtml);
		        //$mailer = 1;
	        return $mailer;

	 	}
		public function EnviarEmail($EmailDestino,$NombreDestino,$Asunto,$CuerpoHTML,$RUTA = NULL){
			//return true;
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			try{
				$Params = $this->getParametros();
				
				$mail->Host = $Params['SMTP'];
				$mail->SMTPDebug  = 2;
				$mail->SMTPAuth   = true;
				$mail->Port       = $Params['PUERTO_SMTP'];
				$mail->Username   = $Params['USUARIO'];
				$mail->Password   = $Params['PASSWORD'];
				$mail->AddReplyTo($Params['USUARIO'], $Params['NOMBRE_MOSTRAR']);
				$mail->AddAddress($EmailDestino,$NombreDestino);
				$mail->SetFrom($Params['USUARIO'], $Params['NOMBRE_MOSTRAR']);
				$mail->AddReplyTo($Params['USUARIO'], $Params['NOMBRE_MOSTRAR']);
				$mail->Subject = $Asunto;
				$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
				$mail->MsgHTML($CuerpoHTML);
				//$mail->MsgHTML(file_get_contents($CuerpoHTML));
				if($RUTA!=NULL){
					foreach ($RUTA as $value) {
						$mail->AddAttachment($value);      // attachment
					}
				}
				//$mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
				return ($mail->Send()) ? true : false;
			} 
			catch (phpmailerException $e) {
			  echo $e->errorMessage();
			  return false;
			}
			catch (Exception $e) {
			  //echo $e->getMessage();
			  return false;
			}
		}

		public function getParametros(){
			$Params = null;
			$Params['SMTP'] = 'creditosprendarios.com';
			$Params['PUERTO_SMTP'] = '25';
			$Params['USUARIO'] = 'intranet@creditosprendarios.com';
			$Params['PASSWORD'] = 'Senet33$';//$this->DesencriptarEnAscii(base64_decode('Wmxma3I0MzM1'),3);
			$Params['NOMBRE_MOSTRAR'] = 'Spot Prices';
			return $Params;
		}

}

?>