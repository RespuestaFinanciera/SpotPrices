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
  if (isset($_GET['name'])) {
    $app = htmlspecialchars($_GET['name']);
    $url = $class_url."getAppData.php?username=".$user."&clave=".$app;
    $miApp = file_get_contents($url);
    $miApp = json_decode($miApp);

  }else{
    echo "No data";
    exit();
  }
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
  $("#packs").kendoDropDownList();
  $("#add").click(function(){
      var arrayUrl = "";
      $('.ck').each(function(){
         var checkbox = $(this);
         if(checkbox.is(':checked')){
              arrayUrl += "arr[]="+checkbox.attr('value')+"&";
         }
      });
      var membership = $("#packs").val();
      var clave = $("#clave").val();
          var url = ClassUrl+"apps.php?action=edit&clave="+escape(clave)+"&"+arrayUrl+"membership="+membership;
          $.getJSON(url,function(data){
              }).error(function() { 
                      $("#msg").html("Error editing app"); 
              }).done(function(data){
                      if (data === undefined) {
                          $("#msg").html("Error editing app");
                      }
                      if(data.response.data.action){
                          $("#msg").html(data.response.data.msg);
                      }else{
                          $("#msg").html(data.response.data.msg);
                      }
              });
  });
	});
</script>
</head>
<body  class="k-content">
            <img style="float:left;" src="../images/config.png" width="64"/><h2 style="margin:0 0 40px 80px;">Configuration</h2>
            <div id="msg" class="k-block k-info-colored" style="width:70%;margin:0 auto;">Information message.</div>
            <div id="options" style="text-align:center; width:400px; margin:0 auto;">
                Service name:&nbsp;
                    <input id="nombre" value="<?php echo $miApp->nombre; ?>" disabled type="text" class="k-textbox"/><br/>
                Monthly price:&nbsp;
                    <input id="clientm" value="<?php echo $miApp->costo; ?>" disabled type="text" class="k-textbox"/><br/>
                Service unique identifier:&nbsp;
                    <input id="clave" value="<?php echo $miApp->unique_id ?>" style="width:160px" type="text" disabled class="k-textbox"/><br/>
                <h3>Change membership</h3>
                    <?php
                        echo $login->dropdownPacks2($miApp->membership);
            
                    ?><br/>
                <h3>Service requests</h3>
                    <?php
                        echo $login->checkRequests($miApp->unique_id);
            
                    ?><br/>
                <button class="k-button" id="add">Save</button>
            </div>
</body>
</html>