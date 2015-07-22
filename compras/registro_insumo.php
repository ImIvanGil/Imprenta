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
            <li><a href="compras.php">Compras</a></li>
            <li><a href="insumos.php" class="current">Insumos</a></li>
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
                
            <img src="../images/iconos/onebit_14.png" alt="image 3" />
            <a href="nuevo_insumo.php">Nuevo Proveedor</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_insumo.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Insumo</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$noparte = $_POST['noparte'];
				$nombre = $_POST['nombre'];
				//$nombre = utf8_decode($nombre);
				$descripcion = $_POST['descripcion'];
				$linea = $_POST['linea'];
				$clase = $_POST['clase'];
				$tipo = $_POST['tipo'];
				$mon = $_POST['moneda'];
				$max = $_POST['max'];
				$min = $_POST['min'];
				$unidad = $_POST['unidad'];
				//$contacto = utf8_decode($contacto);
				
				/*echo "operacion es ".$operacion."<br>";
				echo "el nombre es ".$nombre."<br>";
				echo "el contacto es ".$contacto."<br>";
				echo "la calle es ".$calle."<br>";
				echo "el numero es ".$num."<br>";
				echo "la colonia es ".$colonia."<br>";
				echo "el cp es ".$cp."<br>";
				echo "el pais es ".$pais."<br>";
				echo "el estado es ".$estado."<br>";
				echo "la ciudad es ".$ciudad."<br>";
				echo "el tel es ".$tel."<br>";
				echo "el mail es ".$mail."<br>";
				echo "el rfc es ".$rfc."<br>";*/
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es un insumo nuevo
					$consulta = $db->consulta("insert into insumo(nombre, num_parte, descripcion,id_linea,tipo,maximo, minimo, id_unidad,id_moneda,id_clase) values('".$nombre."','".$noparte."','".$descripcion."','".$linea."','".$tipo."','".$max."','".$min."','".$unidad."','".$mon."','".$clase."');");  
					$clave = mysql_insert_id(); 
					//echo "el id fue ".$id."<br>";
					//$nombre = utf8_encode($nombre);
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El insumo ".$nombre." ha sido registrado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"nuevo_insumo.php?numero=$clave\">Continuar</a></p>";
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar proveedor
					$query = "UPDATE insumo SET nombre='$nombre',num_parte='$noparte',descripcion='$descripcion' ,id_linea='$linea',tipo='$tipo',maximo='$max',minimo='$min',id_unidad='$unidad',id_moneda='$mon',id_clase='$clase' WHERE id_insumo ='$numero_original';";
							
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El insumo ".$nombre." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"insumos.php\">Regresar</a></p>";
				
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