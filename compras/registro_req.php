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
            <li><a href="compras.php">Compras</a></li>
            <li><a href="requisiciones.php" class="current">Requisiciones</a></li>
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
            <a href="requisiciones.php">Requisiciones</a>
            
        </div>

    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Requisicion</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$id_proveedor = $_POST['proveedor'];
				$id_empresa = $_POST['empresa'];
				$status = $_POST['status'];
				$observaciones = $_POST['observaciones'];
				$fecha = $_POST['fecha'];
				$requisiciona = $_POST['id_ordena'];
				
				
				//consulto el nombre del proveedor
				$sql_nombre = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_proveedor=utf8_decode($row_nombre['nombre']);
				
				
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es una requisicion nueva
					
					$query = "INSERT INTO `requisicion` (`id_empresa`,`id_proveedor`, `fecha`, `id_status`, `id_ordena`, `observaciones` ) VALUES ('".$id_empresa."','".$id_proveedor."','".$fecha."','".$status."','".$requisiciona."','".$observaciones."');";
					$consulta = $db->consulta($query); 
					 
					$clave = mysql_insert_id(); 
					$link = "Location: ficha_requisicion.php?numero=$clave";
					header($link);
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar requisicion
					$query = "UPDATE requisicion SET id_proveedor='$id_proveedor', fecha='$fecha', observaciones='$observaciones' WHERE id_requisicion ='$numero_original';";
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>La requisicion con la clave ".$numero_original." ha sido actualizada exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"requisiciones.php\">Regresar</a></p>";
				
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