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

<script language="javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="js/docs.js"></script>

<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script src="js/jquery.ui.draggable2.js"></script>

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

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-ins").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra']});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el elemento n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
</script>

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['cantidad'])) {
	$cant = $_GET['cantidad'];
	$fecha = $_GET['fecha'];
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into timbres(cantidad, fecha) values('".$cant."','".$fecha."');");  
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
    
            <h2>Consumo de timbres</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.fecha.value == "") {
						alert ('Debe escribir la fecha de la compra');
						document.getElementById('fecha').focus();
						return false;
					}
					if (document.registro.cantidad.value == "") {
						alert ('Debe escribir la cantidad de timbres');
						document.getElementById('cantidad').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            
            
            <form method="get" enctype="multipart/form-data" action="timbres.php" name="registro" onSubmit="return validar()">	
							<table align="center" width="550px" border="0px">
								
								<tr align="center"><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Agregar timbres</B></legend>
								    <table border="0s" align="center">	

								    <tr><td align="right"><span>*</span>Fecha:</td>
								    <td><input class="texto" type="text" id="fecha" name="fecha" size="20" /><br /></td>
                                    <td colspan="4">&nbsp;</td>
                                   </tr>
                                   <tr><td align="right"><span>*</span>Cantidad:</td>
								    <td> <input class="texto" type="text" id="cantidad" name="cantidad" size="20" /><br /></td>
                                    <td>&nbsp;</td>
                                    <td align="right"><input class="submit_btn reset" type="submit" value="Registrar"/></td>
                                    <td>&nbsp;</td>
                                    <td align="right"><input class="submit_btn reset" type="reset" value="Cancelar"/></td>
                                   </tr>
                                   <tr><td colspan = "6"><small><span>*</span>Campos obligatorios</small></td></tr>
                                  
                                    </table>
								  </fieldset>
								</td></tr>

							</form>
                            <tr>
                            <td align="left">
                            <div id="data_form">
                            <fieldset>								  
                              <legend> <B>Detalle</B></legend>
								<?php 
									
									$cons = $db->consulta("SELECT sum(cantidad) AS comprados FROM `timbres`");
									$row = $db->fetch_array($cons);
									$comprados = $row['comprados'];
									
									$cons = $db->consulta("SELECT certificadas,canceladas FROM `empresa`");
									$row = $db->fetch_array($cons);
									$certificadas = $row['certificadas'];
									$canceladas = $row['canceladas'];
									
									$consumos =$certificadas + $canceladas;
									$disponibles = $comprados-$consumos;
									
                                    $detalle = $db->consulta("SELECT * FROM `timbres`");
                                    $existe = $db->num_rows($detalle);
                                    if($existe<=0){
                                        echo "<p align=\"center\">No se han agregado compras de timbres al sistema</p>";
                                    }else{
										$i = 1;
									?>
                                    <h5>Resumen</h5>
                                    <table border="0" cellpadding="10px">
                                    <tr><td align="right"><b><i>Folios comprados:</i></b></td><td><?php echo $comprados;?></td></tr>
                                    <tr><td align="right"><b><i>Folios usados para certificar:</i></b></td><td><?php echo $certificadas;?></td></tr>
                                    <tr><td align="right"><b><i>Folios usados para cancelar:</i></b></td><td><?php echo $canceladas	;?></td></tr>
                                    <tr><td align="right"><b><i>Total folios consumidos:</i></b></td><td><?php echo $consumos	;?></td></tr>
                                    <tr><td align="right"><b><i>Folios disponibles:</i></b></td><td><?php echo $disponibles;?></td></tr>
                                    </table>
                                    <h5>Compras</h5>
										<table id="tablesorter-ins" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Fecha</th>
                                                <th class="header">Cantidad</th>                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
										while ($row = $db->fetch_array($detalle))
										{
											$id = $row['id'];
											$cantidad = $row['cantidad'];
											$fecha = $row['fecha'];
											//ahora imprimire la tabla
                                        
										   echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
												 <td align=\"center\">".$fecha."</td>
												 <td align=\"center\">".$cantidad."</td>
												 ";
											$i++;		 
													 
											}
											echo "</tr>";
											
									}
                                ?>
                                </fieldset>
                                </div>
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
?>