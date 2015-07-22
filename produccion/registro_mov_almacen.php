<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
$userId = $prot->getId();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../styles/ui-lightness/jquery.ui.all.css">



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
            <li><a href="produccion.php">Producci&oacute;n</a></li>
            <li><a href="almacen.php" class="current">Almac&eacute;n</a></li>
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
            <a href="nueva_orden_produccion.php">Nueva Orden de Producci&oacute;n</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="#">Buscar</a>
            
        </div>
         
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
        
    
<?php 
			  $producto = $_POST['producto'];
			  //nombre del producto
			  $lista_det = $db->consulta("SELECT `nombre` FROM `producto` WHERE `id_producto`=$producto;");
			  while($row19 = $db->fetch_array($lista_det)){
				  $nom_producto = $row19['nombre'];
				}
				
	      	  //nombre del usuario
			  $nom_us = $db->consulta("SELECT `userRemark` FROM `myuser` WHERE `ID`=$userId;");
			  while($row19 = $db->fetch_array($nom_us)){
				  $nom_usuario = $row19['userRemark'];
				}
				
						
			  $fecha = $_POST['fecha'];
			  $tipo = $_POST['tipo'];
			  $unidades = $_POST['unidades'];
			  $operacion = $_POST['operacion'];
	    	  $orden = $_POST['o_prod'];
			  //si el tipo es 1 entonces obtengo el costo del post si no lo tengo que calcular
			  if($tipo==1 || $tipo==3){
				  $unitario = $_POST['unitario'];
			  }else{
				  //si es una salida voy a obtener el costo promedio para poder registrarlo en el unitario al que salen
				  //para la consulta si es editar voy a consultra los movimientos previos al id, si es uno nuevo voy a consultar segun las fechas anteriores
				  if($operacion=="registrar"){
					  $movimientos = $db->consulta("SELECT * FROM `producto_almacen` WHERE id_producto='".$producto."' AND fecha<='".$fecha."' ORDER BY `fecha`,`id_movimiento` ASC");
				  }else{
					  $key = $_POST['key'];
					  $movimientos = $db->consulta("SELECT * FROM `producto_almacen` WHERE id_producto='".$producto."' AND id_movimiento<'".$key."'  ORDER BY `fecha`,`id_movimiento` ASC");
				  }
				  
				  $existe = $db->num_rows($movimientos);
				  if($existe<=0){
					  $existencia = 0;
				  }else{
					  $existencia = 0;
					  $saldo = 0;
					  while ($row3 = $db->fetch_array($movimientos))
					  {
						  $tipo_mov = $row3['id_tipo_movimiento'];
						  $uni = $row3['cantidad'];
						  $unit = $row3['unitario'];
						  $total_mov = $uni * $unit;
						  if($tipo_mov==1 || $tipo_mov==3){
							  $existencia = $existencia + $uni;
							  $saldo = $saldo + $total_mov;
						 }else{
							  $existencia = $existencia - $uni;
							  $saldo = $saldo - $total_mov;
						 }
						 $promedio = $saldo/$existencia;
						 $unitario = $promedio;
					  }
				  }
			  }
						  


			  $db = new MySQL();
			  
			  if($tipo==2&&$unidades>$existencia){
				  //si es de tipo salida  y no hay suficientes unidades no resgitro la operacion
				   	echo "<p align=\"left\" style=\"font-size: 14px;\"><strong>La operaci&oacute;n no puede ser registrada por que no existen suficientes unidades en inventario. Verifique sus datos.</strong></p>";
					echo "<p align=\"left\" style=\"font-size: 14px;\"><strong>Existencia actual: </strong>$existencia unidades</p>";
					echo "<p align=\"right\"><a href=\"ficha_almacen.php?numero=$producto\">Regresar</a></p>";
					
	  
			  }else{
				  
				  if($operacion == "registrar"){

							//si la operacion es de registrar, el query es de insert, 
					  		$consulta = $db->consulta("insert into producto_almacen (id_producto, fecha, id_tipo_movimiento, cantidad, unitario, id_orden_produccion) values('".$producto."','".$fecha."','".$tipo."','".$unidades."','".$unitario."','".$orden."');");  
						  
							//si la operacion es salida mandare a imprimir la ficha de salida
							if($tipo_mov==2){
								$clave_mov = mysql_insert_id();
								$link = "Location: imprime_salida.php?movimiento=$clave_mov&numero=$producto";
								echo "<h3>Se ha registrado una salida de inventario de producto terminado</h3>";
								echo "<b>Datos de la salida </b><br>";
								echo "<b>Producto: </b>$nom_producto<br>";
								echo "<b>Cantidad: </b>$unidades<br>";
								echo "<b>Solicitante: </b>$nom_usuario<br>";
								echo "<p align=\"right\"><a href=\"ficha_salida_almacen_pdf.php?movimiento=$clave_mov&numero=$producto&fecha=$fecha&solicita=$nom_usuario&cantidad=$unidades&nomprod=$nom_producto\">Imprimir Ficha de Salida</a></p>";

								echo "<p align=\"right\"><a href=\"ficha_almacen.php?numero=$producto\">Regresar</a></p>";

								
								//header($link);
							}else{
								//si es una entrada regreso al movimiento de almacen
								$link = "Location: ficha_almacen.php?numero=$producto";
								header($link);
							}
							
							
				  }else{
					  $numero = $_POST['key'];
					  //si la operacion es de edicion, el query sera de update
					  $consulta = $db->consulta("UPDATE producto_almacen SET fecha='$fecha', id_tipo_movimiento='$tipo', cantidad='$unidades', unitario='$unitario' WHERE id_movimiento='$numero';");  
					  echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>La operaci&oacute;n ha sido actualizada exitosamente. Gracias! </strong></p>";
					  echo "<p align=\"right\"><a href=\"ficha_almacen.php?numero=$producto\">Regresar</a></p>";
					  $link = "Location: ficha_almacen.php?numero=$producto";
					  header($link);
				  }
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