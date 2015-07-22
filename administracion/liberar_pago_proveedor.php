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
            <li><a href="admin.php">Administraci&oacute;n</a></li>
            <li><a href="pagos.php" class="current">Pagos</a></li>
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
            <a href="nuevo_pago.php">Registro de pago</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="pagos.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Liberar pago a proveedor</h2>
            
            
           	<?php
				$db = new MySQL();
				$numero = $_GET['numero'];
				
				
				//consulta el id_proveedor y el si esta libre o esta por asignar
				$sql_cte = "SELECT id_proveedor,aplicado FROM pago_proveedor WHERE id_pago='".$numero."'";
				$consulta_cte = $db->consulta($sql_cte);
				$row_cte = mysql_fetch_array($consulta_cte);
				$id_proveedor=$row_cte['id_proveedor'];
				$aplicado=$row_cte['aplicado'];
				
				//consulto el nombre del proveedor
				$sql_nombre = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_proveedor=$row_nombre['nombre'];
				$nombre_proveedor = utf8_decode($nombre_proveedor);
				
				
				//no lo podre liberar si no esta asignado a nada
				if($aplicado==1){
					echo"El pago a nombre del proveedor <b>".$nombre_proveedor."</b> no ha sido asignado aun a ningun cargo por lo que no es necesario liberarlo.<br><br>";
					echo"<p align=\"right\"><a href=\"pagos.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
				}
				else{
					//selecciono las cargos que se han asignado para cambiarles el status y que vuelvan a estar disponibles para pagarse
					
					$facts = "SELECT * FROM detalle_pago_proveedor WHERE id_pago_proveedor='".$numero."';";
					$asignaciones = $db->consulta($facts);
					
					while ($rowf = $db->fetch_array($asignaciones))
                        {
							$cargo = $rowf['id_cargo'];
							$monto = $rowf['monto'];
							$parcial = $rowf['parcial'];
							
							$id_detalle = $rowf['id_detalle'];
							//si parcial es 1 quiere decir que se saldo por completo y al liberarla vamos a ponerla por cobrar
							if($parcial==1){
								$f1 = "UPDATE cargo SET id_status_cobranza='2' WHERE id_cargo='".$cargo."';";
								$db->consulta($f1);
								$del2 = "DELETE FROM detalle_pago_proveedor WHERE id_detalle='".$id_detalle."';";
								$db->consulta($del2);
								
							}else{
									//si parcial es cero vamos a ver cuanto se adeuda y ver si es lo mismo que el monto porlo que seria por completo por cobrar
									//consultare los abonos que se han hecho a la cargo aparte de este
									$cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_proveedor` WHERE `id_cargo` = '".$cargo."' and `id_detalle`!=".$id_detalle.";");
								   	while ($row2 = $db->fetch_array($cons_abonos)){
									   $abonos = $row2['suma'];
									   //si hay otros abonos entonces la dejo en pago parcial, si este es el unico entonces la dejo en por pagar
										if($abonos!=0){
											$f1 = "UPDATE cargo SET id_status_cobranza='3' WHERE id_cargo='".$cargo."';";
											$db->consulta($f1);
											$del2 = "DELETE FROM detalle_pago_proveedor WHERE id_detalle='".$id_detalle."';";
											$db->consulta($del2);
										}else{
											$f1 = "UPDATE cargo SET id_status_cobranza='2' WHERE id_cargo='".$cargo."';";
											$db->consulta($f1);
											$del2 = "DELETE FROM detalle_pago_proveedor WHERE id_detalle='".$id_detalle."';";
											$db->consulta($del2);
										}
									   
								   	}
									
								
							}
							
					}
					
					
					$del1 = "UPDATE pago_proveedor SET aplicado='1' WHERE id_pago='".$numero."';";
					$db->consulta($del1);
					echo "El pago a nombre del proveedor <b> ".$nombre_proveedor."</b> con la clave <b>".$numero."</b> ha sido liberado y puede ser cancelado o asignado a nuevas cargos<br>";
					echo "<p align=\"right\"><a href=\"pagos.php\">Regresar</a></p>";
				}
					
			
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