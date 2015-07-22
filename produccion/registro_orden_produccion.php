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
            <li><a href="ordenes_compra.php" class="current">Ordenes</a></li>
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
                
            <img src="../images/iconos/onebit_77.png" alt="image 3" />
            <a href="ordenes_compra.php">Ordenes de Compra</a>
            
        </div>

    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Orden</h2>
            
            
            <?php 
				$fecha = $_POST['fecha'];
				$cliente = $_POST['cliente'];
				$producto = $_POST['producto'];
				$entrega = $_POST['entrega'];
				if($_POST['urge']=="1"){
					$urgente ='si';
				}else{
					$urgente ='no';
				}
				if($_POST['nuevo_dis']=="1"){
					$nuevo_dis ='si';
				}else{
					$nuevo_dis ='no';
				}
				$ocompra = $_POST['orden'];
			  	$observaciones = $_POST['observaciones'];
			  	$operacion = $_POST['operacion'];
				$vendedor = $_POST['ordena'];

			  $db = new MySQL();
	
			  
			  if($operacion == "registrar"){
				  //si la operacion es de registrar, el query es de insert
				  $estado = 1;
				  $consulta = $db->consulta("insert into orden_produccion(fecha, id_estado, observaciones, id_cliente, id_vendedor, fecha_entrega, urgente, orden_compra, nuevo_dis) values('".$fecha."','".$estado."','".$observaciones."','".$cliente."','".$vendedor."','".$entrega."','".$urgente."','".$ocompra."','".$nuevo_dis."');");  
				  
				  //consulto el numero del ultimo registro
				  $cons = "select last_insert_id() as ultimo;";
				  $consulta_ultimo = $db->consulta($cons);
				  while ($row = $db->fetch_array($consulta_ultimo))
				  {$num_ultimo = $row['ultimo'];}
				  
				  //ahora en el detalle de la orden inserto el producto
				  $consulta = $db->consulta("insert into detalle_orden_produccion(id_orden, id_producto,id_completo) values('".$num_ultimo."','".$producto."','0');");  
				  
				  
				  echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>La orden de producci&oacute;n con la fecha ".$fecha." ha sido registrada exitosamente.</strong></p>";
				  $link = "Location: ficha_orden_prod.php?numero=$num_ultimo";
				  header($link);
				  echo "<p align=\"right\"><a href=\"ficha_o_produccion.php?numero=$num_ultimo\">Continuar</a></p>";
  
		  	  }else{
				  $numero = $_POST['numero'];
				  $estado = $_POST['estado'];
				  $producto_orig = $_POST['prod_original'];
				  //si la operacion es de edicion, el query sera de update
				  $consulta = $db->consulta("UPDATE orden_produccion SET fecha='$fecha', id_estado='$estado', observaciones='$observaciones', id_cliente='$cliente', id_vendedor='$vendedor', fecha_entrega='$entrega', urgente='$urgente', orden_compra='$ocompra', nuevo_dis='$nuevo_dis' WHERE id_orden='$numero';"); 
				  
				  //si elproducto cambio voy a actualizar el dato en el detalle, si no lo dejo igual
				  if($producto_orig!=$producto){
						//voy a borrar el detalle anterior
						$consulta = $db->consulta("DELETE FROM `detalle_orden_produccion` WHERE `id_orden` = $numero;"); 
						//ahora en el detalle de la orden inserto el producto
				  		$consulta = $db->consulta("insert into detalle_orden_produccion(id_orden, id_producto,id_completo) values('".$numero."','".$producto."','0');"); 
					
				   }
				   
				  echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>La &oacute;rden de producci&oacute;n con la fecha ".$fecha." ha sido actualizada exitosamente. Gracias! </strong></p>";
				  $link = "Location: ficha_orden_prod.php?numero=$numero";
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
ob_end_flush();
?>