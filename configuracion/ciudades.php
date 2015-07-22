<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>
<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-ins").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra']});
		} 
	);
</script>

<!-- Script que hace que aparezcan enlazados los combos de pais, estado y ciudad -->
<script type="text/javascript">
	$(document).ready(function(){
		cargar_paises();
		$("#pais").change(function(){dependencia_estado();});
		$("#estado").change(function(){dependencia_ciudad();});
		$("#estado").attr("disabled",true);
		$("#ciudad").attr("disabled",true);
	});
	
	function cargar_paises()
	{
		$.get("../scripts/cargar-paises.php", function(resultado){
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$('#pais').append(resultado);			
			}
		});	
	}
	function dependencia_estado()
	{
		var code = $("#pais").val();
		$.get("../scripts/dependencia-estado.php", { code: code },
			function(resultado)
			{
				if(resultado == false)
				{
					alert("Error");
				}
				else
				{
					$("#estado").attr("disabled",false);
					document.getElementById("estado").options.length=1;
					$('#estado').append(resultado);			
				}
			}
	
		);
	}
	
	function dependencia_ciudad()
	{
		var code = $("#estado").val();
		$.get("../scripts/dependencia-ciudades.php?", { code: code }, function(resultado){
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$("#ciudad").attr("disabled",false);
				document.getElementById("ciudad").options.length=1;
				$('#ciudad').append(resultado);			
			}
		});	
		
	}
</script>

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['ciud'])) {
	$c = $_GET['ciud'];
	$p = $_GET['pais'];
	$e = $_GET['estado'];
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into ciudad(Name, Country, Province) values('".$c."','".$p."','".$e."');"); 
	$mensaje =  "Se ha registrado la ciudad $c"; 
	$link = "Location: ciudades.php?mensaje=$mensaje";
	header($link);
}
?>


</head>
<body>

<div id="templatemo_wrapper">

	<div id="templatemo_header">

    	<div id="site_title">
            <h1><a href="#">Sistema de ERP<span><?php echo 'Bienvenido, <b> '.$curUser.' </b>'; ?></span></a></h1>
        </div> <!-- end of site_title -->
        
        <div class="cleaner"></div>
    </div> <!-- end of templatemo_header -->
    
    <div id="templatemo_menu">
      <ul>
            <li><a href="../inicio.php">Inicio</a></li>
            <li><a href="../ventas/ventas.php">Ventas</a></li>
            <li><a href="../compras/compras.php">Compras</a></li>
            <li><a href="../produccion/produccion.php">Producci&oacute;n</a></li>
            <li><a href="../administracion/admin.php">Administraci&oacute;n</a></li>
            
			<li><a href="administracion.php" class="current">Configuraci&oacute;n</a></li>
			<li><a href="#" onclick="javascript:document.forms['salir'].submit();">Salir</a></li>
         <?php
			echo '<form action="../adminuser/logout.php" method="POST" name="salir" id="salir">
			<input type="hidden" name="action" value="logout">';
			echo'</form>';
		?>
        </ul>    	  	
    </div> <!-- end of templatemo_menu -->

    <div id="templatemo_banner_wrapper">
    
    <div id="templatemo_banner_thin"> 
    
    	
    
    	<div class="cleaner"></div>
        
    </div> <!-- end of banner -->
    
    </div>	<!-- end of banner_wrapper -->
    
    <div id="templatemo_service_bar_wrapper">
    
    <div id="templatemo_service_line">

    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Ciudades</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.pais.value == "-1") {
						alert ('Debe seleccionar el pais');
						document.getElementById('pais').focus();
						return false;
					}
					if (document.registro.estado.value == "-1") {
						alert ('Debe seleccionar el estado');
						document.getElementById('estado').focus();
						return false;
					}
					if (document.registro.ciudad.value == "") {
						alert ('Debe escribir la ciudad');
						document.getElementById('ciudad').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            <?php 
			if (isset($_GET['mensaje'])) {
				$m = $_GET['mensaje'];
				echo "<b>$m</b><br>";
			}
			?>
            
            <form method="get" enctype="multipart/form-data" action="ciudades.php" name="registro" onSubmit="return validar()">	
							<table align="center" width="550px" border="0px">
								
								<tr align="center"><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Nueva ciudad</B></legend>
								    <table border="0" align="center">	
                                    
                                    <tr><td align="right"><span>*</span>Pa&iacute;s:</td>
								   <td> <select id="pais" name="pais">
                                            <option value="-1">Seleccione Uno...</option>
                                        </select><br />
                                   </td></tr>
                                        
                                   <tr><td align="right"><span>*</span>Estado:</td>
								   <td> <select id="estado" name="estado">
                                            <option value="-1">Selecciona Uno...</option>
                                        </select><br />
                                   </td></tr>
                                   
                                   <tr><td align="right"><span>*</span>Ciudad:</td>
								   <td> <input class="texto" type="text" size="35" name="ciud" id="ciud"/><br />
                                   </td></tr>
                                   
                                   

								    <tr>
								    
                                    <td align="right"><input class="submit_btn reset" type="reset" value="Cancelar"/></td>
                                    
                                    <td align="right"><input class="submit_btn reset" type="submit" value="Registrar"/></td>
                                   </tr>
                                   <tr><td colspan = "6"><small><span>*</span>Campos obligatorios</small></td></tr>
                                  
                                    </table>
								  </fieldset>
								</td></tr>

							</form>
                            <tr>
                            <td align="left">
                            
                            </tbody>
                            </table>
                                
                                
                            </td>
                        </tr>
                        
        				</table> 
        
            </div>
            
       	  <div class="cleaner_h20"></div>
            
            
		</div>
    	<div class="cleaner"></div>
    </div>
    </div>

</div> <!-- end of templatemo_wrapper -->

<div id="templatemo_footer_wrapper">
	<div id="templatemo_footer">

       	<a href="#">C&eacute;nit Consultores</a>
    
    </div> <!-- end of templatemo_footer -->
</div> <!-- end of templatemo_footer_wrapper -->

</body>
</html>
<?php
}
ob_end_flush();
?>