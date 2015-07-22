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
<link rel="shortcut icon" href="images/favicon.ico">
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
            <li><a href="../produccion/produccion.php">Producci&oacute;n</a></li>
            <li><a href="../admin.php">Admnistraci&oacute;n</a></li>
            <li><a href="#">Compras</a></li>
            
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
    
            <h2>Borrado de Variables del Sistema</h2>

			<?php
				$db = new MySQL();
				$numero = $_GET['numero'];
				$variable = $_GET['variable'];
				
				//si la variable es forma pago
				if($variable=='1'){
				
					//consulta el nombre de la forma
					$sql_nombre = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$numero."'";
					$consulta_nombre = $db->consulta($sql_nombre);
					$row_nombre = mysql_fetch_array($consulta_nombre);
					$nombre=$row_nombre['forma_pago'];
				
				
					$sql_tipo = "SELECT * FROM factura WHERE id_forma_pago ='$numero'";			
					$consulta_tipo = $db->consulta($sql_tipo);			
					
					if($db->num_rows($consulta_tipo)>0){
						echo"No es posible eliminar el tipo de pago $nombre por que actualmente hay facturas con dicho tipo de pago favor de verificar sus datos<br><br>";
						echo"<p align=\"right\"><a href=\"formas_pago.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
					}
					else{		
					
						$db->consulta("DELETE FROM forma_pago WHERE id_forma_pago='".$numero."';");
						
						echo "La forma de pago, <b> ".$nombre."</b> ha sido eliminada<br>";
						echo "<p align=\"right\"><a href=\"formas_pago.php\">Regresar</a></p>";
					}
				
				}else{
						if($variable=='2'){
					
						//consulta el nombre del metodo
						$sql_nombre = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo_pago='".$numero."'";
						$consulta_nombre = $db->consulta($sql_nombre);
						$row_nombre = mysql_fetch_array($consulta_nombre);
						$nombre=$row_nombre['metodo_pago'];
					
					
						$sql_tipo = "SELECT * FROM factura WHERE id_metodo_pago ='$numero'";			
						$consulta_tipo = $db->consulta($sql_tipo);			
						
						if($db->num_rows($consulta_tipo)>0){
							echo"No es posible eliminar el metodo de pago $nombre por que actualmente hay facturas con dicho metodo de pago favor de verificar sus datos<br><br>";
							echo"<p align=\"right\"><a href=\"metodos_pago.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
						}
						else{		
						
							$db->consulta("DELETE FROM metodo_pago WHERE id_metodo_pago='".$numero."';");
							
							echo "El m&eacute;todo de pago, <b> ".$nombre."</b> ha sido eliminado<br>";
							echo "<p align=\"right\"><a href=\"metodos_pago.php\">Regresar</a></p>";
						}
					
					}else{
							if($variable=='3'){
					
							//consulta el nombre de la presentacion
							$sql_nombre = "SELECT nombre FROM presentacion WHERE id_presentacion='".$numero."'";
							$consulta_nombre = $db->consulta($sql_nombre);
							$row_nombre = mysql_fetch_array($consulta_nombre);
							$nombre=$row_nombre['nombre'];
						
						
							$sql_tipo = "SELECT * FROM insumo_presentacion WHERE id_presentacion ='$numero'";			
							$consulta_tipo = $db->consulta($sql_tipo);			
							
							if($db->num_rows($consulta_tipo)>0){
								echo"No es posible eliminar la presentaci&oacute;n $nombre por que actualmente hay insumos que se relacionan con ella, favor de verificar sus datos<br><br>";
								echo"<p align=\"right\"><a href=\"presentaciones.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
							}
							else{		
							
								$db->consulta("DELETE FROM presentacion WHERE id_presentacion='".$numero."';");
								
								echo "La presentaci&oacute;n de insumo, <b> ".$nombre."</b> ha sido eliminada<br>";
								echo "<p align=\"right\"><a href=\"presentaciones.php\">Regresar</a></p>";
							}
						
						}//fin de las presentaciones
						else{
							if($variable=='4'){
						
								//consulta el nombre de la tinta
								$sql_nombre = "SELECT tinta FROM tinta WHERE id_tinta='".$numero."'";
								$consulta_nombre = $db->consulta($sql_nombre);
								$row_nombre = mysql_fetch_array($consulta_nombre);
								$nombre=$row_nombre['tinta'];
							
							
								$sql_tinta = "SELECT * FROM producto WHERE id_tintas ='$numero'";			
								$consulta_tinta = $db->consulta($sql_tinta);			
								
								if($db->num_rows($consulta_tinta)>0){
									echo"No es posible eliminar la tinta $nombre por que actualmente hay productos que se relacionan con ella, favor de verificar sus datos<br><br>";
									echo"<p align=\"right\"><a href=\"colores.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
								}
								else{		
								
									$db->consulta("DELETE FROM tinta WHERE id_tinta='".$numero."';");
									
									echo "La tinta <b> ".$nombre."</b> ha sido eliminada<br>";
									echo "<p align=\"right\"><a href=\"colores.php\">Regresar</a></p>";
								}
							
							}else{
								if($variable=='5'){
						
								//consulta el nombre de la unidad
								$sql_nombre = "SELECT unidad FROM unidades WHERE id_unidad='".$numero."'";
								$consulta_nombre = $db->consulta($sql_nombre);
								$row_nombre = mysql_fetch_array($consulta_nombre);
								$nombre=$row_nombre['unidad'];
							
							
								$sql_unidad = "SELECT * FROM producto WHERE id_unidad ='$numero'";			
								$consulta_unidad = $db->consulta($sql_unidad);			
								
								if($db->num_rows($consulta_unidad)>0){
									echo"No es posible eliminar la unidad de medida $nombre por que actualmente hay productos que se relacionan con ella, favor de verificar sus datos<br><br>";
									echo"<p align=\"right\"><a href=\"unidades.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
								}
								else{		
								
									$db->consulta("DELETE FROM unidades WHERE id_unidad='".$numero."';");
									
									echo "La unidad de medida <b> ".$nombre."</b> ha sido eliminada<br>";
									echo "<p align=\"right\"><a href=\"unidades.php\">Regresar</a></p>";
								}
							
							}
								
							
							else{
							if($variable=='6'){
						
								//consulta el nombre del papel
								$sql_nombre = "SELECT tipo FROM tipo_papel WHERE id_tipo='".$numero."'";
								$consulta_nombre = $db->consulta($sql_nombre);
								$row_nombre = mysql_fetch_array($consulta_nombre);
								$nombre=$row_nombre['tipo'];
							
							
								$sql_papel = "SELECT * FROM producto WHERE id_tipo_papel ='$numero'";			
								$consulta_papel = $db->consulta($sql_papel);			
								
								if($db->num_rows($consulta_papel)>0){
									echo"No es posible eliminar el tipo de papel $nombre por que actualmente hay productos que se relacionan con el, favor de verificar sus datos<br><br>";
									echo"<p align=\"right\"><a href=\"papel.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
								}
								else{		
								
									$db->consulta("DELETE FROM tipo_papel WHERE id_tipo='".$numero."';");
									
									echo "el tipo de papel <b> ".$nombre."</b> ha sido eliminado<br>";
									echo "<p align=\"right\"><a href=\"papel.php\">Regresar</a></p>";
								}
							
							}
							}
						}//fin de las unidades de medida
						}//fin de los colores de tinta
					}//fin del metodo de pago
					
				}//fin de la forma de pago
	
			?>
            
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