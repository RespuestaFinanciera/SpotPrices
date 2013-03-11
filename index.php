<?php
session_start();
include($_SERVER["DOCUMENT_ROOT"]."/SpotPrices/class/spotClass.php");
//Sas
$login =new spotClass();
$logged = $login->inicia();
if(!$logged){
    if (isset($_GET['code'])) {
      $msg = $login->errorCodes($_GET['code']);
    }else{
     $msg = "<span style='font-size:1.8em;'>Your'e not logged in, please register now or login to your account.</span>"; 
    }
    $classUrl = $login->class_url;
}else{
    header( "Location: home/index.php" );
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
var msg = "<?php echo $msg; ?>";
var time=2300;
var classUrl = "<?php echo $classUrl; ?>";
var title = "Welcome";
			$(document).ready(function (){
          $("#pikame").PikaChoose();

          createWindow(msg, false,time);
          $("#register").click(function(){
                    correo = $("#email").val();
                                var url = classUrl+"insertUser.php?email="+correo;
                                $.getJSON(url, function(data){

                                }).done(function(data){
                                  if(data){
                                    if(data.response.data.inuse){
                                        $("#register").attr("disabled", "disabled");
                                          $("#resend").html("<button id='Resend' class='k-button width-150'>Resend</button>");
                                    }
                                    var msg = "<span style='font-size:1.8em;'>"+data.response.data.msg+"</span>";
                                    createWindow(msg,false,7000);

                                  }else{
                                    createWindow("<span style='font-size:1.8em;'>Invalid email format</span>",false,3000);
                                  }
                                  
                                  

                                });
            
         });
          $("#resend").click(function(){
                    correo = $("#email").val();
                                var url = classUrl+"insertUser.php?email="+correo+"&resend=true";
                                $.getJSON(url, function(data){

                                }).done(function(data){
                                  if(data){
                                    var msg = "<span style='font-size:1.8em;'>"+data.response.data.msg+"</span>";
                                    createWindow(msg,false,7000);

                                  }else{
                                    createWindow("<span style='font-size:1.8em;'>Invalid email format</span>",false,3000);
                                  }
                                  
                                  

                                });
          });


          function createWindow(msg, title,time) {
            $('#mymsgbox').html("");
            $('#mymsgbox').html(msg);
            $('#mymsgbox').kendoWindow({
               title: title,
               modal: false,
               resizable: false,
               width: 600,
               actions:{}
            }).data('kendoWindow').open();
            $("#mymsgbox").parent().css("position", "fixed");
            $("#mymsgbox").closest(".k-window").css({
                top: 50,
                left: 100
            });
            setTimeout(function() {
              $('#mymsgbox').data("kendoWindow").close();
            }, time);

          }
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
    .k-button:disabled, .k-button:disabled:hover, .k-button:disabled:active {
      background-color: #E3E3E3;
      border-color: #C5C5C5;
      color: #9F9E9E;
      opacity: 0.7;
      cursor: default;
      outline: 0 none;
      }
    </style>
    </head>
    <body>

    <div style="background-image:url(images/borde.png);position:absolute; top:0px; left:0px; width:100%; height:120px;"></div>


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

   <div id="intro">
   <!--
   <div class="group_bannner_right">
   <img src="images/picture.png" width="550" height="316"  alt="baner">
   </div>
   -->
   <!--<header class="group_bannner_left">-->
   <header>
   <hgroup>
   <h1>The data that You require for your App. </h1>
   <h2>“All developers can test Our service for FREE.“ </h2>
   <h2>“Easy to integrate in your app, easier to Monetize.“ </h2>
   </hgroup>
   </header>
   </div>
   <!--end intro-->

   
   
   <!--start holder-->

   <div class="holder_content">
   
   <section class="group1">
   <h3>What you can get.</h3>
   	<p><div class="k-block gp1">
      <table border="1" class="tablita">
        <thead>
          <tr>
            <th><b>SpotPrice / Gr</b></th>
            <th align="center"><b>High</b></th>

            <th align="center"><b>Low</b></th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th><b>Update on:</b></th>
            <td colspan="2">2012-12-11 18:19:40</td>

          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><b>GOLD</b></th>
            <td>$54.94</td>
            <td>$49.45</td>

          </tr>
          <tr>
            <th><b>SILVER</b></th>
            <td>$1.06</td>
            <td>$0.95</td>
          </tr>
          <tr>

              <th><b>EUR</b></th>
              <td>$1.30</td>
              <td>$1.17</td>
          </tr>
        </tbody>
      </table>
    </div></p>

	</section>
	

     
   <section class="group2">
   <h3>Metal Prices.</h3>
   	<p><div style="height:128px; padding: 10px 0 0 0;" class="k-block gp1">
    <table class="tablita2"  width="280">
        <tr>
        <tr>
            <th>
                <b>Gold</b>
            </th>
            <td>
                <span>$1.00</span>
            </td>

        </tr>
        <tr>
            <th>
                <span><b>Silver</b></span>
            </th>
            <td>
                <span>$1.00</span>
            </td>

        </tr>
        <tr>
            <th>
                <span><b>Copper</b></span>
            </th>
            <td>
                <span>$1.00</span>
            </td>

        </tr>
        <tr>
            <th>
                <span><b>Platinum</b></span>
            </th>
            <td>
                <span>$1.00</span>
            </td>

        </tr>
        <tr>
            <th>
                <span><b>Paladium</b></span>
            </th>
            <td>
                <span>$1.00</span>
            </td>

        </tr>
    </table>
    </div></p>

	</section>


       
   <section class="group3">
   <h3>Currency Prices.</h3>
   	<p><div style="height:128px;" class="k-block gp1">
    <table class="tablita2"  width="280">

        <tr>
            <th>
                <b>Description:</b>

            </th>
            <td>
                <span>Obtaining dollar, euro and more currencies.</span>
            </td>
        </tr>
        <tr>
            <th>
                <b>Category:</b>

            </th>
            <td>
                <span>Spot Prices</span>
            </td>
        </tr>
        <tr>
            <th>
                <b>Brand:</b>

            </th>
            <td>
                <span>RespuestaFinanciera.com</span>
            </td>
        </tr>
    </table>
    </div></p>

	</section>

  
   </div>
   <!--end holder-->
   
   <!--start holder-->

   <div class="holder_content1">
    <section class="group4">
   <h3>More services</h3>
   	<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec molestie. Sed aliquam sem ut arcu. Phasellus sollicitudin. 
	Vestibulum condimentum  facilisis nulla. In hac habitasse platea dictumst. Nulla nonummy. Cras quis libero.</p>
    <div class="pikachoose">
	<ul id="pikame" >
		<li><a href="#"><img src="images/picture3.jpg" width="500" height="250"  alt="picture"/></a><span>“Simplicity is the nature of great souls.”</span></li>
		<li><a href="#"><img src="images/picture2.jpg" width="500" height="250" alt="picture"/></a><span>“The little things are infinitely the most important. “ </span></li>
		<li><a href="#"><img src="images/picture1.jpg" width="500" height="250" alt="picture"/></a><span>“Simplicity is the essence of happiness.”</span></li>
	   </ul>
       </div>

	</section>


       
   <section class="group5">
   <h3>Join us now!</h3>
   	<div class="k-block gp2" style="height:160px; padding:8px;">
      <span class="purple" style="margin:20px 20px 60px 10px; padding:0;">1)</span>
          <h4 style="margin:0 30px 0 0 ;padding:0;">Register for free and get 30 days of unlimited services.</h4><br/>

          <label for="email"><span>Your E-mail:&nbsp;</span></label>
          <input id="email" class="k-textbox" type="text" name="email" required="required"  placeholder="e.g. your@mail.net"  required data-email-msg="Email format is not valid"  /><br/><br/>
          <input id="register" class="k-button width-150" type="submit" name="register" value="Register"/>&nbsp;<span id="resend"></span>

    </div><br/>
    <div class="k-block gp2" style="height:110px; padding:8px;">
          <span class="purple" style="margin:10px 30px 0 10px ;padding:0;">2)</span>
            <h4 style="margin:0 30px 0 0 ;padding:10px 0 0 0;">Activate via e-mail and<br/>configure your account</h4>
    </div><br/>
   	<div class="k-block gp2" style="height:170px; padding:8px;">
      <span class="purple" style="margin:40px 30px 40px 10px ;padding:0;">3)</span>
        <h4 style="margin:0 30px 0 0 ;padding:0;">Log in and start!</h4><br/>
        <form action="login.php" method="post">
          <label for="user"><span>Your Username:&nbsp;</span></label><br/>
          <input class="k-textbox" type="text" name="username" required="required" /><br/>
          <label for="passwd"><span>Your Password:&nbsp;</span></label><br/>
          <input class="k-textbox" type="password" name="passwd" required="required" />
          <input class="k-button width-150" type="submit" name="send" value="Log in"/>
        </form>
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
