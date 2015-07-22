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
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="remisiones.php" class="current">Remisiones</a></li>
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
                
            <img src="../images/iconos/onebit_06.png" alt="image 3" />
            <a href="nueva_remision.php">Nueva remision</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="#">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de remision</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$id_cliente = $_POST['cliente'];
				$status = $_POST['status'];
				$id_iva = $_POST['id_iva'];
				$plazo = $_POST['plazo'];
				$observaciones = $_POST['observaciones'];
				
				$fecha = $_POST['fecha'];
				$forma_pago = $_POST['forma_pago'];
				$metodo_pago = $_POST['metodo_pago'];
				$moneda = $_POST['moneda'];
				$oc_cliente = $_POST['oc_cliente'];
				
				//consulto el nombre del cliente
				$sql_nombre = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_cliente=utf8_decode($row_nombre['nombre']);
				
				//consulto el tipo de cambio que corresponde a la operacion
				$sql_tc = "SELECT precio_compra FROM moneda where `id_moneda`='".$moneda."'";
				$consulta_tc = $db->consulta($sql_tc);
				$row_tc = mysql_fetch_array($consulta_tc);
				$tipo_cambio=$row_tc['precio_compra'];
				
				
				
				
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es una remision nueva
					
					$query = "INSERT INTO `remision` (`id_cliente`, `id_status_remision`, `fecha`, `id_forma_pago`, `id_metodo_pago`,`id_moneda`, `tipo_cambio`, `id_iva`,`oc_cliente`,`plazo_pago`, `observaciones`) VALUES ('".$id_cliente."','".$status."','".$fecha."','".$forma_pago."','".$metodo_pago."','".$moneda."','".$tipo_cambio."','".$id_iva."','".$oc_cliente."','".$plazo."','".$observaciones."');";
					//echo "la consulta es: ".$query."<br>";
					$consulta = $db->consulta($query); 
					 
					$clave = mysql_insert_id(); 
					//echo "el id fue ".$id."<br>";
					//$nombre = utf8_encode($nombre);
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El remision a nombre del cliente ".$nombre_cliente." ha sido registrado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"ficha_remision.php?numero=$clave\">Continuar</a></p>";
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar remision
					$query = "UPDATE remision SET id_cliente='$id_cliente',id_forma_pago='$forma_pago', id_metodo_pago='$metodo_pago', fecha='$fecha', id_moneda='$moneda', tipo_cambio='$tipo_cambio', id_iva='$id_iva', oc_cliente='$oc_cliente', plazo_pago='$plazo', observaciones='$observaciones' WHERE id_remision ='$numero_original';";
					//echo "La consulta es ". $query."<br>";
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El remision a nombre del cliente ".$nombre_cliente." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"remisiones.php\">Regresar</a></p>";
				
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