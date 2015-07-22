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
            <li><a href="cotizaciones.php" class="current">Cotizador</a></li>
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
                
            <img src="../images/iconos/onebit_60.png" alt="image 3" />
            <a href="nueva_cotizacion.php">Nueva Cotizaci&oacute;n</a>
            
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
    
            <h2>Registro de Cotizaci&oacute;n</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$id_cliente = $_POST['cliente'];
				
				//$nombre = utf8_decode($nombre);
				$fecha = $_POST['fecha'];
				$condicion = $_POST['condicion'];
				$vigencia = $_POST['vigencia'];				
				$status = $_POST['status'];
				$id_iva = $_POST['id_iva'];
				
				//consulto el nombre del cliente
				$sql_nombre = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_cliente=$row_nombre['nombre'];
				
				
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es una cotizacion nueva
					$consulta = $db->consulta("INSERT INTO `cotizacion` (`id_cliente`, `id_tipo_pago`, `id_status_cotizacion`, `fecha`, `vigencia`,`id_iva`) VALUES ('".$id_cliente."','".$condicion."','".$status."','".$fecha."','".$vigencia."','".$id_iva."');");  
					$clave = mysql_insert_id(); 
					//echo "el id fue ".$id."<br>";
					//$nombre = utf8_encode($nombre);
					echo "<p align=\"center\" style=\"font-size: 14px;\">La cotizaci&oacute;n a nombre del cliente <strong>".$nombre_cliente." </strong>ha sido registrada exitosamente. Gracias! </p>";
					echo "<p align=\"right\"><a href=\"ficha_cotizacion.php?numero=$clave\">Continuar</a></p>";
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar cotizacion
					$query = "UPDATE cotizacion SET id_cliente='$id_cliente',id_tipo_pago='$condicion', id_status_cotizacion='$status', fecha='$fecha', vigencia='$vigencia', id_iva='$id_iva' WHERE id_cotizacion ='$numero_original';";
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\">La cotizacion a nombre del cliente <strong>".$nombre_cliente." </strong> con la clave ".$numero_original." ha sido actualizada exitosamente. Gracias!</p>";
					echo "<p align=\"right\"><a href=\"cotizaciones.php\">Regresar</a></p>";
				
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