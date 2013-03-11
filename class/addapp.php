<?php
session_start();
include("../class/spotClass.php");
$login =new spotClass();
$logged = $login->inicia();
if(!$logged){
  echo"Your session has expired, please reload the page and login again.";
  exit();
}else{
  $user = $_SESSION['iduser'];
  $class_url = $login->class_url;
  $url = $class_url."getMisDatos.php?username=".$user;
  $misDatos = file_get_contents($url);
  $misDatos = json_decode($misDatos);
  $username = $misDatos->nombre;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Documento sin título</title>
<script src="../kendoui/js/jquery.min.js"></script>
<script src="../kendoui/js/kendo.web.min.js"></script>
<link href="../kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="../kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<script type="text/javascript">
$(document).ready(function() {
    var ClassUrl = "<?php echo $class_url ?>";
    var keylist="abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    var temp='';
    var user = '<?php echo $user ?>';
    var name = '<?php echo $username ?>';
    $("#packs").kendoDropDownList();
    $("#clave").val(generatepass());
    $("#reload").click(function() {
        $("#clave").val(generatepass());
    });
    $("#cancel").click(function() {
        window.parent.$("#modalbox").data("kendoWindow").close();
    });
    $("#add").click(function(){
        var nombre = $("#nombre").val();
        var descripcion = $("#descripcion").val();
        var cnombre = $("#clientn").val();
        var cmail = $("#clientm").val();
        var crfc = $("#clientrfc").val();
        var membership = $("#packs").val();
        var isEmptyNom = isEmpty(nombre,5);
        var isEmptyDes = isEmpty(descripcion,5);
        var isEmptyCN = isEmpty(cnombre,0);
        var isEmptyCM = isEmpty(cmail,0);
        var isEmptyCRFC = isEmpty(crfc,0);
        var clave = $("#clave").val();
        if(!isEmptyNom && !isEmptyDes && !isEmptyCN && !isEmptyCM && !isEmptyCRFC){
            var url = ClassUrl+"apps.php?user="+user+"&action=add&name="+escape(nombre)+"&descripcion="+escape(descripcion)+"&clave="+escape(clave)+"&cnombre="+escape(cnombre)+"&cmail="+escape(cmail)+"&crfc="+escape(crfc)+"&membership="+escape(membership);
            //alert(url);
            $.getJSON(url,function(data){
                }).error(function() { 
                        $("#msg").html("Error creating app"); 
                }).done(function(data){
                        if (data === undefined) {
                            $("#msg").html("Error creating app");
                        }
                        if(data.response.data.action){
                            $("#msg").html(data.response.data.msg);
                        }else{
                            $("#msg").html(data.response.data.msg);
                        }
                });
        }else{
            $("#msg").html("Name and Description must be filled and be at least 6 chars long.");
        }
    });

    function generatepass(){
    temp='';
    for (i=0;i<12;i++)
    temp+=keylist.charAt(Math.floor(Math.random()*keylist.length))
    return temp;
    }
    function isEmpty(val,lenght){
        return (val === undefined || val === null || val.length <= lenght) ? true : false;
    }
});
</script>
<style type="text/css">
.k-button{
    height:30px;
}
h3{
    padding:0 50px 0 0;
}
#packs{
    width:140px;
}
</style>
</head>
<body  class="k-content">
            <img style="float:left;" src="../images/app.png" width="80" height="80"/><h2 style="margin-left:100px;">Service creation</h2>
            <div id="msg" class="k-block k-info-colored" style="width:70%;margin:0 auto;">All the text fields are required for activation.</div>
            <br/>
            <div id="options" style="text-align:right; width:400px; margin:0 auto;">
                Service name:&nbsp;
                    <input id="nombre" required="required" type="text" class="k-textbox"/><br/>
                Description:&nbsp;
                    <input id="descripcion" required="required" type="text" class="k-textbox"/><br/>
                    <h3>Client details</h3>
                Client name:&nbsp;
                    <input id="clientn" required="required" type="text" class="k-textbox"/><br/>
                Client email:&nbsp;
                    <input id="clientm" required="required" type="text" class="k-textbox"/><br/>
                RFC or Tax ID:&nbsp;
                    <input id="clientrfc" required="required" type="text" class="k-textbox"/><br/>
                Service unique identifier:&nbsp;
                    <input id="clave" required="required" style="width:160px" type="text" disabled class="k-textbox"/>&nbsp;<button class="k-button" id="reload"><span class="k-icon k-i-refresh">New</span></button><br/>
                <h3>Choose a membership</h3>
                    <?php
                        echo $login->dropdownPacks();
            
                    ?><br/><br/>
                <button class="k-button" id="add">Create</button><button class="k-button" id="cancel">Done</button>
            </div><br/>
</body>
</html>
