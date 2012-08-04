<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<link type="text/css" href="jquery-ui-bootstrap/css/custom-theme/jquery-ui-1.8.16.custom.css" rel="stylesheet"/>
	<link href="bootstrap.css" rel="stylesheet"></link>
	<style type="text/css">
		::selection{ background-color: #E13300; color: white; }
		::moz-selection{ background-color: #E13300; color: white; }
		::webkit-selection{ background-color: #E13300; color: white; }
	
        body {
          padding-top: 40px;          
        }
        fieldset{
            border: 1px solid #ccc;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;                 
            width: auto;                
            margin: 0px auto 20px auto; 
            padding: 12px;
            clear: both;    
        }
        .footer {
            margin-top: 10px;
            padding: 10px 0 36px;
            border-top: 1px solid #E5E5E5;
        }   
        .input-xlarge {
            width: 350px;
        }
        .span1 {
            width: 100px;
        }
    </style>

    <script type="text/javascript" src="js/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="jquery-ui-bootstrap/bootstrap/js/bootstrap-modal.js"></script>
    <script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
</head>
<body>
	<div class="container">      
        <div class="navbar navbar navbar-fixed-top">
	      <div class="navbar-inner">
	         <div class="container">
	          <a class="brand" href="#">Universidad Nacional del Altiplano</a>
	          </div>
	      </div>
	    </div>
	    <br>            
          <h3>Registrar</h3>  
          <fieldset>                
            <form class="form-horizontal">
                <div class="control-group">
                  <label class="control-label" for="select01">Tipo de Documento:</label>
                    <div class="controls">
                      <select class="span1" id="select01">
                        <option></option>
                        <option>Solicitud</option>
                        <option>Oficio</option>
                      </select>
                    </div>
                    <br>
                      <label class="control-label" for="input01">Descripcion:</label>
                      <div class="controls">
                        <input type="text" class="input-medium" id="input01">
                      </div>
                    <br>
                      <label class="control-label" for="input01">Observacion:</label>
                      <div class="controls">
                        <textarea class="input-xlarge" id="textarea" rows="3"></textarea>
                      </div>
                </div>
            </form>
          </fieldset>
        

        <fieldset>
            <form class="form-inline">
                <label>Buscar:&nbsp;</label><input class="text input-xlarge" type="text" name="dato" id="dato" size="60"/>
                <input type="button" id="button_add" class="btn btn-primary" value="adjuntar"/>
            </form>            
            <table class="table" id="table_data">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Apellido Paterno</th>
                        <th>Apellido Materno</th>
                        <th>Nombres</th>
                    </tr>
                </thead>
                <tbody>                
                </tbody>
            </table>
        </fieldset>
        <div class="form-actions">
            <button id="button_registrar" class="btn btn-primary" name="button_registrar">Registrar</button>
        </div>
    </div> <!-- /container -->         
    <div class="modal hide" id="myModal">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">×</button>
            <h3>Alerta</h3>
        </div>
        <div class="modal-body">
            <p>One fine body…</p>
        </div>
        <div class="modal-footer">                
            <a href="#" class="btn close">Ok</a>
        </div>
    </div>
    <script type="text/javascript">
        $('#myModal').modal();
        function log( message ) {
            $( "<div/>" ).text( message ).prependTo( "#log" );
            $( "#log" ).scrollTop( 0 );
        }
        var add_item_table = "";
        $("#dato").autocomplete({                                
                    source: function( request, response ) {
                        var filtro = $("#dato").val();                                    			        
                        $.ajax({
                            type: "POST",
                            url: "autocompletar2.php",
                            dataType: "json", // siempre es asi
                            data: "filtro="+filtro,	// mas es = a concatenacion		            
                            success: function(data) {
                                response($.map(data.response, function(item){
                                    item.label = item.nombres + " " + item.ape_paterno + " " + item.ape_materno + " - " + item.cod_estudiante;
                                    item.value = item.cod_estudiante + "-" + item.nombres;
                                    return item;                                                
                                }));			                
                            }
                        });
                    },
                    minLength: 2,
                    select: function( event, ui ) {        

                        add_item_table = ""
                        add_item_table += '<tr><td>'+ui.item.cod_estudiante+'</td>';
                        add_item_table += '<td>'+ui.item.ape_paterno+'</td>';
                        add_item_table += '<td>'+ui.item.ape_materno+'</td>';                            
                        add_item_table += '<td>'+ui.item.nombres+'</td></tr>';                                                        
                        
                        log( ui.item ? "Selected: " + ui.item.nombres : "Nothing selected, input was " + this.value);                                                                                                            
                    },                        
                    open: function() {
                        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
                    },
                    close: function() {
                        $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );                            
                    }
                });

        $("#button_add").click(function(){
            $("#table_data tbody").append(add_item_table);
            $("#dato").val("");        
        });        
        $("#button_registrar").click(function(){
            var array_documento = new Array();
            array_documento[0] = $("#select01").val();
            array_documento[1] = $("#input01").val();
            array_documento[2] = $("#textarea").val();                
            array_documento = JSON.stringify(array_documento);
            
            
            var array_personas = new Array();
            $("#table_data tbody tr").each(function(index){                    
                array_personas[index] = $(this).find("td:eq(0)").html();                                        
            });                                
            array_personas = JSON.stringify(array_personas);
            
            $.ajax({
                type: "POST",
                url: "control_registrardocumento.php",
                dataType: "json", // siempre es asi
                data: "array_documento="+array_documento+"&array_personas="+array_personas, // mas es = a concatenacion                 
                success: function(data) {
                    if(data["success"] == true){
                        $('#myModal').modal('show');                            
                        $('#myModal .modal-body').html(data["response"]);
                        $("#select01").val("");
                        $("#input01").val("");
                        $("#textarea").val("");
                        $("#table_data tbody").empty();
                    }else{
                        $('#myModal').modal('show');                            
                        $('#myModal .modal-body').html(data["response"]);
                    }                         
                }
            });                
        });         
    </script>
    <footer class="footer container">            
        <p class="pull-right">&copy; 2012</p>
        <p>DERECHOS RESERVADOS<br />
        Universidad Nacional del Altiplano - Oficina de Tecnología Informática</p>
    </footer>
</body>
</html>