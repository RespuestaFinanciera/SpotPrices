<?php
session_start();
include("class/spotClass.php");
    $login =new spotClass();
    $classUrl = $login->class_url;
    if($_GET["mdc"] && $_GET["mdt"]){
      $mdc = $_GET['mdc'];
      $mdt = $_GET['mdt'];
    }else{
      $mdc = false;
      $mdt = false;
    }
?>
<!doctype html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--<meta name="viewport" content="width=device-width initial-scale=1" />-->
<title>Spot Prices</title>
<script src="kendoui/js/jquery.min.js"></script>
<script src="kendoui/js/kendo.web.min.js"></script>
<link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<link rel="icon" href="images/favicon.gif" type="image/x-icon"/>
<script language="JavaScript" src="libs/hash.js"></script>
 <!--[if lt IE 9]>
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

<link rel="shortcut icon" href="images/favicon.gif" type="image/x-icon"/> 
<!-- estilos -->
<link rel="stylesheet" type="text/css" href="css/styles.css"/>
<!-- cosas raras -->
<link type="text/css" href="css/css3.css" rel="stylesheet" />

<!--<script type="text/javascript" src=" https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.js"></script>-->
<script type="text/javascript" src="js/jquery.pikachoose.js"></script>
<script language="javascript">
function isEmpty(val){
    return (val === undefined || val == null || val.length <= 5) ? true : false;
}
function White(s) {
  return /\s/g.test(s);
}
function createWindow(msg,time,center) {
            $("#mymsgbox").html("");
            $("#mymsgbox").html(msg);
            if(center){
              $("#mymsgbox").data("kendoWindow").center().open();
              $("#mymsgbox").parent().css("position", "fixed");
            }else{
              $("#mymsgbox").data("kendoWindow").open();
              $("#mymsgbox").parent().css("position", "fixed");
              $("#mymsgbox").closest(".k-window").css({
                  top: 50,
                  left: 100
              });
            }
            
            if(time>0){
              setTimeout(function() {
                $("#mymsgbox").data("kendoWindow").close();
              }, time);
            }
                
            

          };
var msg = "";
var time=2300;
var classUrl = "<?php echo $classUrl; ?>";
var correo = "<?php echo $mdc; ?>";
var timestamp = "<?php echo $mdt; ?>";
			$(document).ready(function (){
        $("#mymsgbox").kendoWindow({
               modal: true,
               resizable: false,
               width: 600,
               visible: false,
               title: false,
               actions:{}
            });
            createWindow("PLEASE WAIT...",false,true);
            if (correo && timestamp) {
                  var url = classUrl+"activator.php?correo="+correo+"&timestamp="+timestamp;
                $.getJSON(url, function(data){

                }).done(function(data){
                  $("#mymsgbox").data("kendoWindow").close();
                    if(data.response.data.action){
                      var msg = data.response.data.msg;

                    }else{
                      var msg = "<span style='font-size:1.8em;'>"+data.response.data.msg+"</span>";
                      createWindow(msg,false,true);
                    }

                });
                $("#activate").click(function(){
                        var pwd1 = $("#pwd1").val();
                        var pwd2 = $("#pwd2").val();
                        var nombre = $("#nombre").val();
                        var usuario = $("#usuario").val();
                        var check1 = isEmpty(pwd1);
                        var check2 = isEmpty(pwd2);
                        var check3 = White(pwd1);
                        var check4 = White(pwd2);
                        var check5 = isEmpty(nombre);
                        var check6 = isEmpty(usuario);
                        if(check1 || check2 || check3 || check4){
                            createWindow("Password must be at least 6 chars long with no spaces.",4000,true);
                        }else{
                            if(pwd1 != pwd2){
                                createWindow("The passwords are different, check them please.",4000,true);

                            }else{
                                if(check5 || check6){
                                    createWindow("Your name and username must be at least 6 chars long.",4000,true);
                                }else{
                                  var pwd = calcMD5(pwd2);
                                    var url = classUrl+"updateUserData.php?nombre="+nombre+"&usuario="+usuario+"&passwd="+pwd+"&correo="+correo;
                                    $.getJSON(url, function(data){

                                    }).done(function(data){
                                        if(data.action == "success"){
                                          var msg = "<span style='font-size:1.8em;'>"+data.response.data.msg+"</span>";
                                          createWindow(msg,false,true);

                                        }else{
                                          var msg = "<span style='font-size:1.8em;'>"+data.response.data.msg+"</span>";
                                          createWindow(msg,3000,true);
                                        }

                                    });
                                }
                            }
                        }
                    });

            }else{
                $(".holder_content1").html("<section class='group4'><h2>Oops! Seems you are lost.</h2></section>");
            };
				});
		</script>
    <style type="text/css">
    .k-button{
      color: #F60;
    }
    .k-block.gp1{
      border: solid 1px #000;
      background:rgba(255,255,255,0.3);
    }
    .k-block.gp2{
      border: solid 1px #000;
      background:rgba(255,255,255,0.5);
    }
    .tablita {
    border: 1px solid #ccc;
    font-size: 13px;
    color: #000;
    margin:5px;
    }
    .tablita2 {
    font-size: 13px;
    color: #000;
    }
    .tablita th {
        border: 1px solid #ccc;
        width: 100px;
    }
    .tablita td {
        border: 1px solid #E3E3E3;
        font-size: 12px;
        text-align: center;
    }
    .adark{
      color:#212121;
      width:100px;
      margin: 0 8em;
    }
    .adark:hover{
      color:#999;
    }
    </style>
    </head>
    <body>

    <div style="background-image:url(images/borde.png);position:absolute; top:0px; left:0px; width:105%; height:120px;"></div>


    <!--start container-->
    <div id="container">
    <div style="background-image:url(images/flechita.png);position:absolute; top:0px; right:0px; width:180px; height:190px;"></div>
    <!--start header-->
    <div id="mymsgbox" class="k-block k-success-colored"></div>
    <header style="">
    <!--start logo-->
    <a href="#" id="logo"><img src="images/spotprices.png" width="500"  alt="logo"/></a>    

	<!--end logo-->
	
   <!--start menu-->
<!--
	<nav>
    <ul>
    <li><a href="#" class="current">Home</a>

	</li>
    <li><a href="#">About us</a></li>
	<li><a href="#">Services</a></li>
	<li><a href="#">Portfolio</a></li>
    <li><a href="#">News</a></li>
    <li><a href="#">Contact</a></li>

    </ul>
    </nav>
  -->
	<!--end menu-->
	

    <!--end header-->
	</header>


   <!--start intro-->

   <div id="intro" style="height:70px;">
   <!--
   <div class="group_bannner_right">
   <img src="images/picture.png" width="550" height="316"  alt="baner">
   </div>
   -->
   <!--<header class="group_bannner_left">-->
   <header>
   <hgroup>
   <h1>Account Activation.</h1>
   </hgroup>
   </header>
   </div>
   
   <!--start holder-->

   <div class="holder_content1">
    <section class="group4" style="width:600px; margin: 0 8em;">
    <span id="msg">In order to activate your account, provide the following information for your profile
      (all data will be treated confidentially)</span>
    <div id="form" class="k-block" style="text-align:right; padding:10px;">
    <label for="nombre"><span>Your full name (For your pofile information):</span></label>
          <input id="nombre" required="required" type="nombre" name="nombre" class="k-textbox"/><br/>
          <label for="usuario"><span>Your username (For logging in):</span></label>
          <input id="usuario" required="required" type="usuario" name="usuario" class="k-textbox"/><br/>
          <label for="passwd"><span>Your password (write it twice in order to confirm it):</span></label>
          <input id="pwd1" required="required" type="password" name="passwd" class="k-textbox"/>
          <input id="pwd2" required="required" type="password" class="k-textbox"/><br/>
          <button class="k-button" id="activate">Activate my account</button>
        </div>

      </section>
    </div>
    <!--end holder-->
   
   
    </div>
   <!--end container-->
   <!--start footer-->
   <footer>
   <div class="container">
   <div id="FooterTwo"><img src="images/RespuestaFinanciera_foot.png" width="310" height="50"/></div>
   <div id="FooterTree">Template by <a href="http://www.alejandroguiberra.com" target="blank">Alejandro Guiberra.com</a> ©</div> 
   </div>
   </footer>
   <!--end footer-->
<!-- Free template distributed by http://freehtml5templates.com -->   
   </body></html>
