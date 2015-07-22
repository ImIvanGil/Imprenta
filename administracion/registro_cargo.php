<?php include("../adminuser/adminpro_class.php");
ob_start();
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
            <li><a href="cargos.php" class="current">Cargos</a></li>
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
                
            <img src="../images/iconos/onebit_47.png" alt="image 3" />
            <a href="nuevo_cargo.php">Nuevo Cargo</a>
            
        </div>

         <div class="sb_box">
            <a href="#">Buscar</a>
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <form method="get" action="cargos.php" name="busqueda" id="busqueda" onSubmit="return validarBusqueda()"> 
            <input type="text" class="texto" width="30" name="parametro" id="parametro" />
            <input class="submit_btn reset" type="submit" value="Ir"/>
            </form>
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Crago</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$id_proveedor = $_POST['proveedor'];
				$id_empresa = $_POST['empresa'];
				$status = $_POST['status'];
				$plazo = $_POST['plazo'];
				$observaciones = $_POST['observaciones'];
				$fecha = $_POST['fecha'];
				$forma_pago = $_POST['forma_pago'];
				$metodo_pago = $_POST['metodo_pago'];
				$moneda = $_POST['moneda'];
				$referencia = $_POST['referencia'];
				$subtotal = $_POST['subtotal'];
				$impuesto = $_POST['impuesto'];
				
				//consulto el nombre del proveedor
				$sql_nombre = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_proveedor=utf8_decode($row_nombre['nombre']);
				
				//consulto el tipo de cambio que corresponde a la operacion
				$sql_tc = "SELECT precio_compra FROM moneda where `id_moneda`='".$moneda."'";
				$consulta_tc = $db->consulta($sql_tc);
				$row_tc = mysql_fetch_array($consulta_tc);
				$tipo_cambio=$row_tc['precio_compra'];
				
				//asigno a la variable estatus de cobro como indefinido para que cuando se certifique se ponga por cobrar
				$estado_cobro = 1;
				
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es una cargo nueva
					
					$query = "INSERT INTO `cargo` (`id_proveedor`, `id_empresa`, `id_status_cargo`, `fecha`, `id_forma_pago`, `id_metodo_pago`, `id_moneda`, `tipo_cambio`,`referencia`,`plazo_pago`, `observaciones`,`id_status_cobranza`,`sub_total`,`impuestos`) VALUES ('".$id_proveedor."','".$id_empresa."','".$status."','".$fecha."','".$forma_pago."','".$metodo_pago."','".$moneda."','".$tipo_cambio."','".$referencia."','".$plazo."','".$observaciones."','".$estado_cobro."','".$subtotal."','".$impuesto."');";
					//echo "la consulta es: ".$query."<br>";
					$consulta = $db->consulta($query); 
					 
					$clave = mysql_insert_id(); 
					//echo "el id fue ".$id."<br>";
					//$nombre = utf8_encode($nombre);
					//echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>La cargo a nombre del proveedor ".$nombre_proveedor." ha sido registrada exitosamente. Gracias! </strong></p>";
					//echo "<p align=\"right\"><a href=\"ficha_cargo.php?numero=$clave\">Continuar</a></p>";
					$link = "Location: ficha_cargo.php?numero=$clave";
					//$link = "Location: cargos.php";
					header($link);
					
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar cargo
					$query = "UPDATE cargo SET id_proveedor='$id_proveedor',id_forma_pago='$forma_pago', id_metodo_pago='$metodo_pago', fecha='$fecha', id_moneda='$moneda', tipo_cambio='$tipo_cambio', referencia='$referencia', plazo_pago='$plazo', observaciones='$observaciones', sub_total='$subtotal', impuestos='$impuesto' WHERE id_cargo ='$numero_original';";
					//echo "La consulta es ". $query."<br>";
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El cargo a nombre del proveedor ".$nombre_proveedor." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"cargos.php\">Regresar</a></p>";
				
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
ob_end_flush();
?>