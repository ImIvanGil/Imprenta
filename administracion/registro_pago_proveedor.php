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
    
            <h2>Registro de Pago a Proveedor</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$id_proveedor = $_POST['proveedor'];
				$observaciones = $_POST['observaciones'];
				$tipo_pago = $_POST['tipo_pago'];
				
				//consulto el nombre del proveedor
				$sql_nombre = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_proveedor=utf8_decode($row_nombre['nombre']);
				
				if($tipo_pago==6){
					
					$referencia = $_POST['id_nota'];
					$cons= "SELECT * FROM `nota_credito` WHERE id_nota=$referencia";
					$lista_not = $db->consulta($cons);
						while ($row8 = $db->fetch_array($lista_not))
						{
						  $id_nota = $row8['id_nota'];
						  $fecha_hora = $row8['fecha'];
						  list($fecha,$hora) = explode("T",$fecha_hora);
						  
						$id_moneda = $row8['id_moneda'];
						
						$id_iva = $row8['id_iva'];
						//voy a buscar la tasa del iva que vamos a usar
						$sql_tas = "SELECT * FROM iva WHERE id_iva='".$id_iva."'";
						$consulta_tas = $db->consulta($sql_tas);
						$row_tas = mysql_fetch_array($consulta_tas);
						$tasa_iva = $row_tas['tasa'];
						$tipo_iva = $row_tas['tipo'];
						$sub_total=0;
						$graba_normal=0;
						$graba_cero =0;
						$exento=0;
						$detalle = $db->consulta("SELECT * FROM `detalle_nota_credito` WHERE id_nota='".$referencia."'");
						
						while ($row = $db->fetch_array($detalle))
						{
							$cantidad = $row['cantidad'];
							$unitario = $row['unitario'];
							$clase_iva = $row['id_clase_iva'];
							$precio = $unitario * $cantidad;
							$sub_total = $sub_total + $precio;
							
							switch($clase_iva){
								case 1:
									$graba_normal = $graba_normal+$precio;
								break;
								case 2:
									$graba_cero = $graba_cero+$precio;
								break;
								case 3:
									$exento = $exento + $precio;
								break;
							}
							
						}
						//calculo de los valores totalizados
						$iva = $graba_normal * ($tasa_iva/100);
						$monto = $sub_total + $iva;
						//asigno a la nota el estatus de cobranza 4-liquidado para que ya no aparezca disponible para seleccionarse
						$cambio = $db->consulta("UPDATE `nota_credito` SET id_status_cobranza=4 WHERE id_nota='".$referencia."'");
						
					}
					
				}else{
					$monto = $_POST['monto'];
					$referencia = $_POST['ref'];
					$fecha = $_POST['fecha'];
					$id_moneda = $_POST['moneda'];
				}
				
				//consulto el tipo de cambio que corresponde a la operacion
				$sql_tc = "SELECT precio_compra FROM moneda where `id_moneda`='".$id_moneda."'";
				$consulta_tc = $db->consulta($sql_tc);
				$row_tc = mysql_fetch_array($consulta_tc);
				$tipo_cambio=$row_tc['precio_compra'];
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es un pago nuevo
					
					//si es una nota de credito se captura el motivo, si no el motivo sera -1
					//en aplicado, si es 1 esta para asiganr, si es 2 esta asignado a cargos si es 3 esta cancelado
					$aplicado = 1;
					$query = "INSERT INTO `pago_proveedor` (`id_proveedor`,`fecha`, `id_tipo_pago`, `referencia`, `id_moneda`, `tipo_cambio`, `monto`, `usuario`, `observaciones`, `aplicado`) VALUES ('".$id_proveedor."','".$fecha."','".$tipo_pago."','".$referencia."','".$id_moneda."','".$tipo_cambio."','".$monto."','".$curUser."','".$observaciones."','".$aplicado."');";
					
					//echo "la consulta es: ".$query."<br>";
					$consulta = $db->consulta($query); 
					 
					$clave = mysql_insert_id(); 
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El pago al proveedor ".$nombre_proveedor." ha sido registrado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"pagos.php?\">Regresar</a></p>";
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar pago
					$query = "UPDATE pago_proveedor SET id_proveedor='$id_proveedor',fecha='$fecha', id_tipo_pago='$tipo_pago', referencia='$referencia', id_moneda='$id_moneda', tipo_cambio='$tipo_cambio', monto='$monto', usuario='$curUser', observaciones='$observaciones' WHERE id_pago ='$numero_original';";
					//echo "La consulta es ". $query."<br>";
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El pago a nombre del proveedor ".$nombre_proveedor." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
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