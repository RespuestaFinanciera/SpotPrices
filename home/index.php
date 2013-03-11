<?php
session_start();
include("../class/spotClass.php");
$login =new spotClass();
$logged = $login->inicia();
if(!$logged){
  header( "Location: ../index.php?code=1" );
  exit();
}else{
  $user = $_SESSION['iduser'];
  $class_url = $login->class_url;
  $url = $class_url."getMisDatos.php?username=".$user;
  $misDatos = file_get_contents($url);
  $misDatos = json_decode($misDatos);
  $username = $misDatos->nombre;
  $email = $misDatos->email;
  $msg = "<span style='font-size:1.8em;'>Welcome ".$username."</span>";
}
?>
<!doctype html>  
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<!--<meta name="viewport" content="width=device-width initial-scale=1" />-->
<title>Spot Prices</title>
<script src="../kendoui/js/jquery.min.js"></script>
<script src="../kendoui/js/kendo.web.min.js"></script>
<link href="../kendoui/styles/kendo.common.min.css" rel="stylesheet" />
<link href="../kendoui/styles/kendo.default.min.css" rel="stylesheet" />
<link rel="icon" href="../images/favicon.gif" type="image/x-icon"/>
 <!--[if lt IE 9]>
 <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

<link rel="shortcut icon" href="../images/favicon.gif" type="image/x-icon"/> 
<!-- estilos -->
<link rel="stylesheet" type="text/css" href="../css/styles.css"/>
<!-- cosas raras -->
<link type="text/css" href="../css/css3.css" rel="stylesheet" />

<!--<script type="text/javascript" src=" https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.js"></script>-->
<script type="text/javascript" src="../js/jquery.pikachoose.js"></script>
<script language="javascript">
var msg = "<?php echo $msg; ?>";
var user ="<?php echo $user; ?>";
var classUrl = "<?php echo $class_url; ?>";
$(document).ready(function (){
        function onSelect(e) {
                    var item = $(e.item),
                        menuElement = item.closest(".k-menu"),
                        dataItem = this.options.dataSource,
                        index = item.parentsUntil(menuElement, ".k-item").map(function () {
                            return $(this).index();
                        }).get().reverse();

                    index.push(item.index());

                    for (var i = -1, len = index.length; ++i < len;) {
                        dataItem = dataItem[index[i]];
                        dataItem = i < len-1 ? dataItem.items : dataItem;
                    }
                    if(dataItem.value !== undefined){
                          var url = classUrl+dataItem.value+"app.php";
                              createWindowModal(url);
                    }
                    
                }

					$("#pikame").PikaChoose();

          createWindow(msg, 3000);
          $("#menu-images").kendoMenu({
            select: onSelect,
                    dataSource: [
                        {
                            text: "Home", imageUrl: "../images/menu/home.png",
                            items: [
                                { text: "Top News", imageUrl: "../images/menu/news.png" },
                                { text: "Videos", imageUrl: "../images/menu/movie.png" }
                            ]
                        },
                        {
                            text: "My services", imageUrl: "../images/menu/software.png",
                            items: [
                                { text: "Add new service", imageUrl: "../images/menu/add.png", value:"add"},
                                { text: "Show my services", imageUrl: "../images/menu/show.png", value:"list"}
                                
                            ]
                        },
                        {
                            text: "Configuration", imageUrl: "../images/menu/settings.png",
                            items: [
                                { text: "My account", imageUrl: "../images/menu/my_account.png", url: 'panel.php' },
                                { text: "Log off", imageUrl: "../images/menu/exit.png", url: "logout.php?doLogout=true" }
                            ]
                        }
                    ]

                });

          function createWindow(msg,time) {
            $('#mymsgbox').html("");
            $('#mymsgbox').html(msg);
            $('#mymsgbox').kendoWindow({
               title: " ",
               modal: false,
               resizable: false,
               width: 600,
               draggable:false
            }).data('kendoWindow').open();
            $("#mymsgbox").parent().css("position", "fixed");
            $("#mymsgbox").closest(".k-window").css({
                top: 20,
                left: 100
            });
            setTimeout(function() {
              $('#mymsgbox').data("kendoWindow").close();
              $('#mymsgbox').html("");

            }, time);

          }
          function createWindowModal(content) {


              if (!$('#modalbox').data("kendoWindow")) {
                      // window not yet initialized
                      $('#modalbox').kendoWindow({
                          title: " ",
                             modal: true,
                             resizable: false,
                             width: 900,
                             draggable:false,
                             height:600,
                             content:content
                      }).data("kendoWindow").center().open(); // open the window;
                  } else {
                      // reopening window
                      $('#modalbox').data("kendoWindow")
                          .refresh(content) // request the URL via AJAX
                          .center()
                          .open(); // open the window
                  }

          }
});
		</script>
    <style type="text/css">
    .k-image{
      width:20px;
    }
    .k-window  div.k-window-content
      {
              overflow: hidden;
      }
      .group5 p{
        font-size: 16px;
      }

    </style>
    </head>
    <body>

    <div style="background-image:url(../images/borde.png);position:absolute; top:0px; left:0px; width:100%; height:120px;"></div>


    <!--start container-->
    <div id="container">
    <div style="background-image:url(../images/flechita.png);position:absolute; top:0px; right:0px; width:180px; height:190px;"></div>
    <!--start header-->
    <div id="mymsgbox" class="k-block k-info-colored"></div>
    <div id="modalbox"></div>
    <header style="">
    <!--start logo-->
    <a href="#" id="logo"><img src="../images/spotprices.png" width="500"  alt="logo"/></a>    

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

   <div id="intro" style="height:auto;">
   <!--
   <div class="group_bannner_right">
   <img src="images/picture.png" width="550" height="316"  alt="baner">
   </div>
   -->
   <!--<header class="group_bannner_left">-->

    <h4 class="k-block k-success-colored">Logged as: <?php echo $username; ?></h4>
      <div id="menu-images"></div>

   </div>
   <!--end intro-->

   
   
   <!--start holder-->

   <div class="holder_content">
   
   <section class="group1">
   <h3>How to...</h3>
   	<p>In "My services" menu you could see the service actions; add a new one or list your existing ones.</p>
    <img src="../images/step1.png" width="330"/>
    <!--<a href=""><span class="read_more">Read more...</span></a>-->

	</section>
	

     
   <section class="group2">
   <h3>Add a new service</h3>
   	<p>After clicking the "Add service" button, provide your new service data for identification in order to give your client access to the API</p>
	 <img src="../images/step2.png" width="330"/>
  <!--<a href=""><span class="read_more">Read more...</span></a>-->

	</section>


       
   <section class="group3">
   <h3>Show my apps</h3>
   	<p>You can see all your apps listed here for editing, activation, deactivation or deleting them, also there will be showed the actual configuratin and data received from the API</p>
	<a href=""><span class="read_more">Read more...</span></a>

	</section>

  
   </div>
   <!--end holder-->
   
   <!--start holder-->

   <div class="holder_content1">
    <section class="group4">
   <h3>Last spot prices</h3>
   	<p>Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Donec molestie. Sed aliquam sem ut arcu. Phasellus sollicitudin. 
	Vestibulum condimentum  facilisis nulla. In hac habitasse platea dictumst. Nulla nonummy. Cras quis libero.</p>
    <div class="pikachoose">
	<ul id="pikame" >
		<li><a href="#"><img src="../images/picture3.jpg" width="500" height="250"  alt="picture"/></a><span>“Simplicity is the nature of great souls.”</span></li>
		<li><a href="#"><img src="../images/picture2.jpg" width="500" height="250" alt="picture"/></a><span>“The little things are infinitely the most important. “ </span></li>
		<li><a href="#"><img src="../images/picture1.jpg" width="500" height="250" alt="picture"/></a><span>“Simplicity is the essence of happiness.”</span></li>
	   </ul>
       </div>

	</section>


       
   <section class="group5">
   <h3>My account</h3>
   	<div class="k-block">
          <p><?php echo $nombre."<br/>Your username:&nbsp;".$user."<br/>Your e-mail:&nbsp;".$email; ?></p><br/>
    </div>


	</section>

   
    </div>
    <!--end holder-->

   
   
    </div>
   <!--end container-->
   <!--start footer-->
   <footer>
   <div class="container">
   <div id="FooterTwo"><img src="../images/RespuestaFinanciera_foot.png" width="310" height="50"/></div>
   <div id="FooterTree">Template by <a href="http://www.alejandroguiberra.com" target="blank">Alejandro Guiberra.com</a> ©</div> 
   </div>
   </footer>
   <!--end footer-->
<!-- Free template distributed by http://freehtml5templates.com -->   
   </body></html>
