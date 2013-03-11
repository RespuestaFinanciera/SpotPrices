<?php


class spotClass {
      public $class_url = "http://ns1.maximas.com.mx/sp/class/";
        // Inicia sesion

        public function inicia($usuario=NULL, $clave=NULL) {
            if ($usuario==NULL && $clave==NULL) {
                // Verifica sesion
                if (isset($_SESSION['iduser'])) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $logged = $this->verifica_usuario($usuario, $clave);
                //echo "verificando";
                return $logged;
            }
        }
        //  Verifica login 
        function verifica_usuario($usuario, $clave) {
            $parametros = $this->crearurl(array("username"=>$usuario,"passwd"=>$clave));
            $request = $this->class_url."getLogin.php".$parametros;
            $response = file_get_contents($request);
            $response = json_decode($response);

            if($response->action == "success"){
                if($response->response->data->data == 1){
                    //se logueo
                    @session_start();
                    $_SESSION['iduser'] = $usuario;
                    return true;
                }else{
                    //datos malos
                    return false;
                }
            }elseif ($response->action =="error") {
                //error de conexion o no se que
                return false;
            }
        }
        function errorCodes($code){
            switch ($code) {
                case 1:
                    $msg = "<span style='font-size:1.8em;'>Restricted Access</span>";
                    return $msg;
                    break;
                case 2:
                    $msg = "<span style='font-size:1.8em;'>Incorrect login or password</span>";
                    return $msg;
                    break;
                case 3:
                    $msg = "Error Code 3";
                    return $msg;
                    break;
            }

        }
        private function crearurl($val1){
            $url = "?";
            foreach ($val1 as $key => $value) {
                $url .= $key."=".$value."&"; 
            }
            $url .= "*";
            $url = str_replace("&*", "", $url);
            return $url;
        }
        public function getPacks(){
            $req = $this->class_url."getPacks.php";
            $resp = file_get_contents($req);
            $resp = json_decode($resp);

            if ($resp->action == "success") {
                //se conecto a database
                if (count($resp->response->data)>0) {
                    //hay datos
                    $pr = $resp->response->data;
                    return json_encode($pr);
                }else{
                    //no hay datos
                    return false;
                }

            }elseif($resp->action == "error") {
               //no se conecto o esto exploto
                return false;
            }

        }
        public function getRequests($clave){
            $req = $this->class_url."getRequests.php?clave=".$clave;
            $resp = file_get_contents($req);
            $resp = json_decode($resp);

            if ($resp->action == "success") {
                //se conecto a database
                if (count($resp->response->data)>0) {
                    //hay datos
                    $pr = $resp->response->data;
                    return json_encode($pr);
                }else{
                    //no hay datos
                    return false;
                }

            }elseif($resp->action == "error") {
               //no se conecto o esto exploto
                return false;
            }

        }
        public function dropdownPacks(){
            $Packs = $this->getPacks();
            $Packs = json_decode($Packs);
            $miDropdown = "<select id='packs'>";
            foreach ($Packs as $value) {
                $miDropdown .= "<option value=\"".$value->clave."\">".$value->nombre."</option>";
            }
            $miDropdown .= "</select>";
            return $miDropdown;

        }
        public function checkRequests($clave){
            $Requests = $this->getRequests($clave);
            $Requests = json_decode($Requests);
            $miChecks = "";
            $estado = array(" ", "checked");
                //<input type="checkbox" name="AU" value="AU">Gold
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->usd]." name='usd' value='usd'>USD";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->eur]." name='eur' value='eur'>EUR";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->mxn]." name='mxn' value='mxn'>MXN";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->brl]." name='brl' value='brl'>BRL<br/>";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->au]." name='au' value='au'>AU";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->ag]." name='ag' value='ag'>AG";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->cu]." name='cu' value='cu'>CU";
                $miChecks .= "<input class='ck' type='checkbox' ".$estado[$Requests->pt]." name='pt' value='pt'>PT";
            return $miChecks;

        }
        public function dropdownPacks2($membership){
            $Packs = $this->getPacks();
            $Packs = json_decode($Packs);
            $miDropdown = "<select id='packs'>";
            foreach ($Packs as $value) {
                if($membership == $value->clave){
                  $miDropdown .= "<option value=\"".$value->clave."\" selected>".$value->nombre."</option>";  
                }else{
                   $miDropdown .= "<option value=\"".$value->clave."\">".$value->nombre."</option>"; 
                }
                
            }
            $miDropdown .= "</select>";
            return $miDropdown;

        }
}

?>