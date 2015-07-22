<?php
ob_start();
?>
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
            <li><a href="empleados.php" class="current">Empleados</a></li>
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
                
            <img src="../images/iconos/onebit_31.png" alt="image 3" />
            <a href="nuevo_cliente.php">Nuevo Empleado</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_empleado.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Borrar Empleado</h2>
            <div id="data_form">
            
			 <?php
        		$db = new MySQL();
				$numero = $_GET['numero'];
				
				//consulta el nombre del empleado
				$sql_nombre = "SELECT nombre FROM empleado WHERE id_empleado='".$numero."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre=$row_nombre['nombre'];
				
				
				$db->consulta("DELETE FROM empleado WHERE id_empleado='".$numero."';");
				echo "El empleado, <strong> ".$nombre."</strong> con la clave No. <strong>".$numero."</strong> ha sido eliminado<br>";
				echo "<p align=\"right\"><a href=\"empleados.php\"><b> Regresar</b></a></p>";
				
				/*DESACTIVARE LA REVISION DE NOMINA POR QUE NO EXISTE ESA TABLA AUN
				revisa si el empleado esta incluido en alguna lista de nomina y no nos deja borrarlo si esto pasa
				$consulta_pagos = $db->consulta("select count(id_empleado) as cuenta FROM detalle_nomina WHERE id_empleado='".$numero."';"); 
				$row_pagos = mysql_fetch_array($consulta_pagos);
				$cuenta=$row_pagos['cuenta'];
				if($cuenta>0){
					echo "El empleado, <strong> ".$nombre."</strong> con la clave No. <strong>".$numero."</strong> se encuentra registrado en alguna lista de pagos, al borrarlo, se perder&iacute;a la integridad de los datos, elimine primero la lista de pagos en la que se encuentra y luego podr&aacute; borrar al empleado<br>";
					echo "<p align=\"right\"><a href=\"empleados.php\"><b> Regresar</b></a></p>";
				}else{
					$db->consulta("DELETE FROM empleado WHERE id_empleado='".$numero."';");
					echo "El empleado, <strong> ".$nombre."</strong> con la clave No. <strong>".$numero."</strong> ha sido eliminado<br>";
					echo "<p align=\"right\"><a href=\"empleados.php\"><b> Regresar</b></a></p>";
				}*/

              ?>
        
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
ob_end_flush();
?>