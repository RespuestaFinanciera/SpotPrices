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
    var user = '<?php echo $user ?>';
    var name = '<?php echo $username ?>';

                        dataSource = new kendo.data.DataSource({
                            transport: {
                                read:  {
                                    url: ClassUrl + "getApps.php?username="+user,
                                    dataType: "json"
                                },
                                update: {
                                    url: ClassUrl + "editApps.php",
                                    dataType: "jsonp"
                                },
                                /*destroy: {
                                    url: ClassUrl + "destroyuser.php",
                                    dataType: "jsonp"
                                },
                                create: {
                                    url: ClassUrl + "createuser.php",
                                    dataType: "jsonp"
                                },*/
                                parameterMap: function(options, operation) {
                                    if (operation !== "read" && options.models) {
                                        return {models: kendo.stringify(options.models)};
                                    }
                                }
                            },
                            batch: true,
                            pageSize: 30,
                            schema: {
                                model: {
                                    id: "id",
                                    fields: {
                                        usuario: { editable: false},
                                        nombre: { editable: true, validation: { required: true, min: 6, max:30} },
                                        descripcion: { validation: { required: true, min: 6, max:80} },
                                        clave: { editable: false},
                                        status: { editable: false},
                                        client_name: { editable: false},
                                        client_email: { editable: false},
                                        client_rfc: { editable: false}
                                    }
                                }
                            }
                        });

                    $("#grid").kendoGrid({
                        dataSource: dataSource,
                        resizable:true,
                        selectable:true,
                        pageable: true,
                        sortable:true,
                        height: 450,
                        columns: [
                            { field: "nombre", title:"Service name"},
                            { field: "descripcion", title:"Description" },
                            { field: "clave", title:"Unique ID" },
                            { field: "client_name", title:"Client" },
                            { field: "client_email", title:"Client email" },
                            { field: "client_rfc", title:"Rfc or Tax ID" },
                            { field: "status", title:"Status" },
                            {template: kendo.template($("#command-template").html())},
                            { command: { text: "Config", click: config }, title: " "},
                            { command: { text: "Delete", click: drop }, title: " "}
                        ]

                    });
    $("#grid").on("click", ".k-grid-off", function() {
       $.getJSON(ClassUrl+"switch.php?clave=" + escape(this.id) + "&status=ON", function(data){
        }).done(function(data){
            reloadGrid();
            $("#msg").html(data.response.data.msg);
        });
    });
        
    $("#grid").on("click", ".k-grid-on", function() {
        $.getJSON(ClassUrl+"switch.php?clave=" + this.id + "&status=OFF", function(data){
        }).done(function(data){
            reloadGrid();
            $("#msg").html(data.response.data.msg);
        });
    });
    function drop(e){
        e.preventDefault();
    var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
    var clave = dataItem.clave;
        $.getJSON(ClassUrl+"drop.php?clave=" + escape(clave), function(data){
        }).done(function(data){
            reloadGrid();
            $("#msg").html(data.response.data.msg);
        });
    }
    function config(e){
        e.preventDefault();
        var dataItem = this.dataItem($(e.currentTarget).closest("tr"));
        var clave = dataItem.clave;
        var url = ClassUrl+"configApp.php?name="+clave;
        createWindowModal(url);


    }
    function isEmpty(val,lenght){
        return (val === undefined || val === null || val.length <= lenght) ? true : false;
    }
    function reloadGrid(){
        myGrid = $("#grid").data("kendoGrid"); 
        myGrid.dataSource.transport.options.read.url = ClassUrl + "getApps.php?username="+escape(user);
        myGrid.dataSource.read();
        myGrid.refresh();
    }
    function createWindowModal(content) {


              if (!$('#modalbox').data("kendoWindow")) {
                      // window not yet initialized
                      $('#modalbox').kendoWindow({
                          title: " ",
                             modal: true,
                             resizable: false,
                             width: 500,
                             draggable:false,
                             height:500,
                             content: content
                      }).data("kendoWindow").center().open(); // open the window;
                  } else {
                      // reopening window
                      $('#modalbox').data("kendoWindow")
                          .refresh(content)
                          .center()
                          .open(); // open the window
                  }
                  $("#modalbox").parent().css("position", "fixed");
                  $("#modalbox").parent().css("top", "0");
          }

});
</script>
<script id="command-template" type="text/x-kendo-template">
    # if(status == 'ON') { #
        <a id="#=clave#" class="k-button k-grid-off">Turn off</a>
    # } else { #
        <a id="#=clave#" class="k-button k-grid-on">Turn on</a>
    # } #
</script>
<style type="text/css">
.k-grid{ 
    font-size: 12px;
}
</style>
</head>
<body  class="k-content">
            <div id="modalbox" ></div>
            <img style="float:left;" src="../images/list.png" width="40"/><h2 style="margin-left:50px;">Current Services</h2>
            <div id="msg" class="k-block k-info-colored" style="width:70%;margin:0 auto;">Information message.</div>
            <div id="grid"></div>
</body>
</html>
