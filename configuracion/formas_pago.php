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

<script language="javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="js/docs.js"></script>

<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script src="js/jquery.ui.draggable2.js"></script>
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
if (isset($_GET['forma'])) {
	$forma = $_GET['forma'];
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into forma_pago(forma_pago) values('".$forma."');");  
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
    
            <h2>Formas de Pago</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.forma.value == "") {
						alert ('Debe escribir la forma de pago');
						document.getElementById('forma').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            
            
            <form method="get" enctype="multipart/form-data" action="formas_pago.php" name="registro" onSubmit="return validar()">	
							<table align="center" width="550px" border="0px">
								
								<tr align="center"><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Nueva forma de pago</B></legend>
								    <table border="0" align="center">	

								    <tr><td align="right"><span>*</span>Nombre:</td>
								    <td> <input class="texto" type="text" id="forma" name="forma" size="40" /><br /></td>
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
									
                                    $detalle = $db->consulta("SELECT * FROM `forma_pago`");
                                    $existe = $db->num_rows($detalle);
									
                                    if($existe<=0){
                                        echo "<p align=\"center\">No se han agregado formas de pago al sistema</p>";
                                    
                                    }else{
										$i = 1;
									?>
										<table id="tablesorter-ins" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Forma</th>
                                                <th class="header">Eliminar</th>                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
										while ($row = $db->fetch_array($detalle))
										{
											$id = $row['id_forma_pago'];
											$forma = $row['forma_pago'];
											$variable =1;
											//ahora imprimire la tabla
                                        
										   echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
												 <td align=\"center\">".$forma."</td>
												 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/configuracion/borrar_variable.php?variable=$variable&numero=$id\",\"$id\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
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