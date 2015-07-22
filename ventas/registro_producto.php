<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
            <li><a href="productos.php" class="current">Productos</a></li>
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
                
            <img src="../images/iconos/onebit_59.png" alt="image 3" />
            <a href="nuevo_producto.php">Nuevo Producto</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_producto.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Producto</h2>
            
            
           <?php 
																	
				$operacion = $_POST['operacion'];
				$nombre = $_POST['nombre'];
				$cve = $_POST['cve'];
				$cant = $_POST['cant'];
				$core = $_POST['core'];
				//$nombre = utf8_decode($nombre);
				$descripcion = $_POST['descripcion'];
				//$tamano = $_POST['tamano'];
				$tinta = $_POST['tinta'];
				$linea = $_POST['linea'];
				$status = $_POST['status'];
				$unidad = $_POST['unidad'];
				$precio = $_POST['precio'];
				$cliente = $_POST['cliente'];
				$papel = $_POST['papel'];
				$laminado = $_POST['laminado'];
				$barnizado = $_POST['barnizado'];
				$etiquetadora = $_POST['etiquetadora'];
				$rebobinado = $_POST['rebobinado'];
				$color = $_POST['color'];
				$prensa = $_POST['prensa'];
				$dado = $_POST['dado'];
				$largo = $_POST['largo'];
				$ancho = $_POST['ancho'];
				if(isset($_POST['precorte'])){
					$precorte ='1';
				}else{
					$precorte ='0';
				}
				$ext_largo = $_POST['ext_largo'];
				$ext_ancho = $_POST['ext_ancho'];
				if(isset($_POST['grapado'])){
					$grapado ='1';
				}else{
					$grapado ='0';
				}
				$pegado = $_POST['pegado'];
				$marginal = $_POST['marginal'];
				$prefijo = $_POST['prefijo'];
				$sufijo = $_POST['sufijo'];
				$impresion = $_POST['impresion'];
				if(isset($_POST['perforacion'])){
					$perforacion ='1';
				}else{
					$perforacion ='0';
				}
				
				if(isset($_POST['engargolado'])){
					$engargolado ='1';
				}else{
					$engargolado ='0';
				}
				
				if(isset($_POST['encuadernado'])){
					$encuadernado ='1';
				}else{
					$encuadernado ='0';
				}
				
				
				
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					
					$sql_verifica = "SELECT * FROM producto WHERE clave ='$cve'";
					$consulta_verifica = $db->consulta($sql_verifica);
					if($db->num_rows($consulta_verifica)>0){
						echo"No es posible registrar el producto con la clave <b>".$cve."</b> por que actualmente hay un producto con esa clave, favor de verificar sus datos<br><br>";
						echo"<p align=\"right\"><a href=\"productos.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
					}else{
					
						//si es un producto nuevo
						$consulta = $db->consulta("insert into producto(clave, nombre, id_tintas, descripcion, precio,id_linea, id_status_prod, id_unidad, id_cliente, id_tipo_papel, laminado, barnizado, etiquetadora, prensa,rebobinado, color, dado, largo, ancho, precorte, ext_largo, ext_ancho, grapado, pegado, marginal, prefijo, sufijo, perforacion, engargolado, encuadernado, impresion, cant, core) values('".$cve."','".$nombre."','".$tinta."','".$descripcion."','".$precio."','".$linea."','".$status."','".$unidad."','".$cliente."','".$papel."','".$laminado."','".$barnizado."','".$etiquetadora."','".$prensa."','".$rebobinado."','".$color."','".$dado."','".$largo."','".$ancho."','".$precorte."','".$ext_largo."','".$ext_largo."','".$grapado."','".$pegado."','".$marginal."','".$prefijo."','".$sufijo."','".$perforacion."','".$engargolado."','".$encuadernado."','".$impresion."','".$cant."','".$core."');");   
						$clave = mysql_insert_id(); 
						echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El producto ".$nombre." ha sido registrado exitosamente. Gracias!</strong></p>";
						echo "<p align=\"right\"><a href=\"ficha_producto.php?numero=$clave\">Continuar</a></p>";
						 $link = "Location: ficha_producto.php?numero=$clave";
						 header($link);
					}
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar producto
					$query = "UPDATE producto SET clave='$cve', nombre='$nombre', id_tintas='$tinta', descripcion='$descripcion', precio='$precio', id_linea='$linea', id_status_prod='$status', id_unidad='$unidad', id_cliente='$cliente', id_tipo_papel='$papel', laminado='$laminado', barnizado='$barnizado', etiquetadora='$etiquetadora', prensa='$prensa', rebobinado='$rebobinado', color='$color', dado='$dado', largo='$largo', ancho='$ancho', precorte='$precorte', ext_largo='$ext_largo', ext_ancho='$ext_ancho', grapado='$grapado', pegado='$pegado', marginal='$marginal', prefijo='$prefijo', sufijo='$sufijo', perforacion='$perforacion', engargolado='$engargolado', encuadernado='$encuadernado', impresion='$impresion', cant='$cant', core='$core'  WHERE id_producto ='$numero_original';";
					
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El producto ".$nombre." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"productos.php\">Regresar</a></p>";
					$link = "Location: ficha_producto.php?numero=$numero_original";
					header($link);
				
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