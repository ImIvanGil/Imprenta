<?php include("../adminuser/adminpro_class.php");
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
<link rel="stylesheet" href="../styles/ui-lightness/jquery.ui.all.css">

<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>

<script src="../js/jquery-1.4.2.js"></script>
<script src="../js/jquery.ui.core.js"></script>
<script src="../js/jquery.ui.widget.js"></script>
<script src="../js/jquery.ui.datepicker.js"></script>
<script src="../js/valida/jquery.validate.js"></script>
<script src="../js/localization/messages_es.js"></script>

<script>
//muestra el calendario
$(function() {
	$( "#fecha" ).datepicker({
		changeMonth: true,
		changeYear: true,
		showOn: 'both',
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: false,
		selectOtherMonths: false

  
	});
	
});
</script>
<script type="text/javascript">
<!-- muestra y oculta campos en el formulario segun el select
function mostrar(){
//Si la opcion con id Conocido_1 (dentro del documento > formulario con name fcontacto >     y a la vez dentro del array de Conocido) esta activada
if (document.registro.tipo_pago.value == "6") {
	//muestra la lista del tipo de nota de credito
	document.getElementById('motivo').style.display='block';
	document.getElementById('motivo2').style.display='block';
	document.getElementById('motivo2').focus();
	document.getElementById('nota').style.display='block';
	document.getElementById('nota2').style.display='block';
} else {
		document.getElementById('motivo').style.display='none';
		document.getElementById('motivo2').style.display='none';
		document.registro.motivo_nota.value = "-1";
		document.getElementById('nota').style.display='none';
		document.getElementById('nota2').style.display='none';
		document.registro.id_nota.value = "-1";
	}
}
-->
</script>
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
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="cobros_clientes.php" class="current">Cobranza</a></li>
           	<li><a href="#" onclick="javascript:document.forms['salir'].submit();">Salir</a></li>
         <?php
			echo '<form action="logout.php" method="POST" name="salir" id="salir">
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
    
    <div id="templatemo_service_bar">
    
    	<div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_55.png" alt="image 3" />
            <a href="nuevo_pago_cliente.php">Registro de pago</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="buscar_pago_cliente.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Nuevo pago de cliente</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					
					if (document.registro.fecha.value == "") {
						alert ('Debe eleccionar una fecha para la factura');
						document.getElementById('fecha').focus();
						return false;
					}
					if (document.registro.moneda.value == "-1") {
						alert ('Debe seleccionar una moneda');
						document.getElementById('moneda').focus();
						return false;
					}
					if (document.registro.monto.value == "") {
						alert ('Debe escribir el monto de la operacion a registrar');
						document.getElementById('monto').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            <?php
				$tipo = $_GET['tipo'];
				$cliente = $_GET['cliente'];
			?>
            
            
            <form method="post" enctype="multipart/form-data" action="registro_pago_cliente.php" name="registro" onSubmit="return validar()">
							<input type="hidden" name="operacion" id="operacion" value="registrar">	
                            <input type="hidden" name="cliente" id="cliente" value="<?php echo $cliente;?>">
                            <input type="hidden" name="tipo_pago" id="tipo_pago" value="<?php echo $tipo;?>">
                            <input type="hidden" name="motivo_nota" id="motivo_nota" value="">
                            	
                            
							<table align="center" width="550px" border="0px">
								
								<tr align="center"><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Datos de Registro </B></legend>
								    <table border="0" width="500px">	
                                    
                                    <tr>
                                    <td align="right"><b>Cliente: </b></td>
                                    	<td align="left" valign="middle">
                                            <?php 
												$lista_tam = $db->consulta("SELECT * FROM `cliente` WHERE id_cliente=$cliente;");
													while ($row6 = $db->fetch_array($lista_tam))
													{
													  $nombre = $row6['nombre'];
													  echo $nombre;
													}
											?>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><b>Tipo de pago: </b></td>
                                    	<td align="left" valign="middle">
                                            <?php 
												$lista_forma = $db->consulta("SELECT * FROM `tipo_pago_cliente` where id_tipo_pago=$tipo;");
													while ($row6 = $db->fetch_array($lista_forma))
													{
													  $tipo = $row6['tipo_pago'];
													  echo $tipo;
													}
											?>
                                    </td>
                                    </tr>

								    <tr><td align="right"><span>*</span>Fecha:</td>
								    <td> <input class="texto" type="text" id="fecha" name="fecha" size="20" /><br /></td></tr>
                                    <tr>
                                    <td align="right">Referencia: </td>
                                    <td align="left" valign="middle"><input class="texto" type="text" id="ref" name="ref" size="20" /><br />
                                   </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Moneda: </td>
                                    	<td align="left" valign="middle">
                                        <select id="moneda" name="moneda">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_moneda = $db->consulta("SELECT * FROM `moneda`;");
													while ($row6 = $db->fetch_array($lista_moneda))
													{
													  $id_moneda = $row6['id_moneda'];
													  $moneda = $row6['moneda'];
													  $tipo_cambio = number_format($row6['precio_compra'],2);
													  echo "<option value=\"".$id_moneda."\">".$moneda." - $".$tipo_cambio."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span> Monto: $</td>
                                    <td align="left" valign="middle"><input class="texto" type="text" id="monto" name="monto" size="20" /><br />
                                   </td>
                                    </tr>
                                    
                                     <tr>
                                    <td align="right" valign="top">Observaciones: </td>
                                    <td align="left" valign="middle"> <textarea id="observaciones" name="observaciones" rows="0" cols="0"></textarea><br />
                                   </td>
                                    </tr>
                                   
                                    </table>
								  </fieldset>
								</td></tr>

								
								<tr><td colspan = "2"><small><span>*</span>Campos obligatorios</small></td></tr>

								<tr><td colspan="2"align="right"> 	   
								 <BR> <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 <input class="submit_btn reset" type="reset" value="Cancelar"/>
								</td></tr>
								
								</table>  
							</form>
        
        
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
?>