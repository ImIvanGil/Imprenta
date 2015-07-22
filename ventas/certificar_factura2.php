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

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-ins").tablesorter({sortList:[[1,0]], widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false},6:{sorter: false},7:{sorter: false}}});
		} 
	);
</script>



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
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="insumos.php">Insumos</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="facturas.php" class="current">Facturas</a></li><!-- li><a href="reportes.php">Reportes</a></li -->        
			<li><a href="administracion.php">Configuraci&oacute;n</a></li>
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
                
            <img src="../images/iconos/onebit_78.png" alt="image 3" />
            <a href="nueva_factura.php">Nueva Factura</a>
            
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
    
            <h2>Certificaci&oacute;n de Comprobante</h2>

			<?php
                $clave = $_GET['numero'];	
				//variable que va a air almacenando la cadena 
				$cadena_original = "||3.0";	
				//echo $cadena_original."<br>";	
				//variables que acumularan los totales
				$sub_total = 0;
				$graba_normal = 0;
				$graba_cero = 0;
				$exento = 0;
				$iva = 0;
				$total_factura = 0;
				
                $factura = $db->consulta("SELECT * FROM `factura` WHERE id_factura='".$clave."'");
                $existe = $db->num_rows($factura);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la factura con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($factura))
                {
						$fecha = $row['fecha'];
						$cadena_original = $cadena_original."|".$fecha;
						//echo $cadena_original."<br>";	
					
						//tipo de comprobante
						$id_tipo_comp = $row['id_tipo_comprobante'];
						$sql_tipo = "SELECT tipo_comprobante FROM tipo_comprobante WHERE id_tipo_comprobante='".$id_tipo_comp."'";
						$consulta_tipo = $db->consulta($sql_tipo);
						$row_tipo = mysql_fetch_array($consulta_tipo);
						$tipo = $row_tipo['tipo_comprobante'];
						$cadena_original = $cadena_original."|".$tipo;
						//echo $cadena_original."<br>";	
						
						//forma de pago
						$id_forma = $row['id_forma_pago'];
						$sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
						$consulta_forma = $db->consulta($sql_forma);
						$row_forma = mysql_fetch_array($consulta_forma);
						$forma = $row_forma['forma_pago'];
						$cadena_original = $cadena_original."|".$forma;
						//echo $cadena_original."<br>";	
						
					
						$id_empresa = $row['id_empresa'];
						//datos de la empresa
						$sql_empresa = "SELECT * FROM empresa where `id_empresa`='".$id_empresa."'";
						$consulta_empresa = $db->consulta($sql_empresa);
						while($row_emp = mysql_fetch_array($consulta_empresa)){
							$nom_emp=$row_emp['nombre'];
							$rfc_emp=$row_emp['rfc'];
							$calle_emp=$row_emp['calle'];
							$numero_emp=$row_emp['numero'];
							$colonia_emp=$row_emp['colonia'];
							$ciudad_emp=$row_emp['ciudad'];
							$estado_emp=$row_emp['estado'];
							$pais_emp=$row_emp['pais'];
							$cp_emp=$row_emp['codigo_postal'];
							
							$cadena_emisor = $rfc_emp."|".$nom_emp."|".$calle_emp."|".$numero_emp."|".$colonia_emp."|".$ciudad_emp."|".$estado_emp."|".$pais_emp."|".$cp_emp;
							
							$cadena_expedido_en = $calle_emp."|".$numero_emp."|".$colonia_emp."|".$ciudad_emp."|".$estado_emp."|".$pais_emp."|".$cp_emp;
							
						}
						
					  	$id_cliente = $row['id_cliente'];
						//datos del cliente
						$sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
						$consulta_cliente = $db->consulta($sql_cliente);
						while($row_cliente = mysql_fetch_array($consulta_cliente)){
							$nom_cliente=$row_cliente['nombre'];
							$rfc_cliente=$row_cliente['rfc'];
							$calle_cliente=$row_cliente['calle'];
							$numero_cliente=$row_cliente['numero'];
							$colonia_cliente=$row_cliente['colonia'];
							$ciudad_cliente=$row_cliente['ciudad'];
							$estado_cliente=$row_cliente['estado'];
							$cp_cliente=$row_cliente['codigo_postal'];
							$pais_cliente=$row_cliente['pais'];
							
							$cadena_receptor = $rfc_cliente."|".$nom_cliente."|".$calle_cliente."|".$numero_cliente."|".$colonia_cliente."|".$ciudad_cliente."|".$estado_cliente."|".$pais_cliente."|".$cp_cliente;
						}
						
						//metodo de pago
						$id_met = $row['id_metodo_pago'];
						$sql_met = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo_pago='".$id_met."'";
						$consulta_met = $db->consulta($sql_met);
						$row_met = mysql_fetch_array($consulta_met);
						$metodo = $row_met['metodo_pago'];
						
						//moneda
						$id_mon = $row['id_moneda'];
						$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
						$consulta_mon = $db->consulta($sql_mon);
						$row_mon = mysql_fetch_array($consulta_mon);
						$moneda = $row_mon['moneda'];
						
						
						$t_cambio = $row['tipo_cambio'];
						
						//voy a buscar la tasa del iva que vamos a usar
						$id_iva = $row['id_iva'];
						$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
						$consulta_tas = $db->consulta($sql_tas);
						$row_tas = mysql_fetch_array($consulta_tas);
						$tasa_iva = $row_tas['tasa'];

						//voy a consultar las lineas de detalle de la factura
						$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$clave."'");
						$existe = $db->num_rows($detalle);
						if($existe<=0){
							echo "<p align=\"center\">No se han agregado productos a la factura</p>";
						
						}else{
							$cadena_concepto = "";		
							while ($row = $db->fetch_array($detalle))
							{
								$id_detalle = $row['id_detalle_fact'];
								$id_producto = $row['id_producto'];
								$cantidad = $row['cantidad'];
								
								$unitario = $row['unitario'];
								$clase_iva = $row['id_clase_iva'];
								
								//consultare el nombre del tipo de iva
								$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
								while ($row2 = $db->fetch_array($consulta_cl)){
									 $nom_clase = $row2['clase_iva'];
								}
								
								
								$precio = $unitario * $cantidad;
								$sub_total = $sub_total + $precio;
								
								//$precio = number_format($precio,2);
								//$unitario = number_format($unitario,2);
								
								//consultare el nombre del producto
								$consulta_prod = $db->consulta("SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';");
								 while ($row2 = $db->fetch_array($consulta_prod)){
									 $nom_producto = $row2['nombre'];
									 $desc_producto = $row2['descripcion'];
									 }
								//voy a acumular las cantidades para calcular el iva segun como sea la clase
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
								
									$fprecio = number_format($precio, 2, '.', '');
									$funitario = number_format($unitario, 2, '.', '');
									$cadena_concepto = $cadena_concepto."|".$cantidad."|".$nom_producto."|".$funitario."|".$fprecio;
						  
								  }
							  }
						 
						  //calculo de los valores totalizados
						  $iva = $graba_normal * ($tasa_iva/100);
						  $total_factura = $sub_total + $iva;
						  $sub_total = number_format($sub_total, 2, '.', '');
						  $iva = number_format($iva, 2, '.', '');
						  $total_factura = number_format($total_factura, 2, '.', '');
						  
						  $cadena_traslado = "IVA|".$tasa_iva."|".$iva."|".$iva;
						  
						  $cadena_original = $cadena_original."|".$sub_total;
						  //echo $cadena_original."<br>";	
						  
						  $cadena_original = $cadena_original."|".$t_cambio;
						  //echo $cadena_original."<br>";	
						 
						 $cadena_original = $cadena_original."|".$moneda;
						 //echo $cadena_original."<br>";
						 
						 $cadena_original = $cadena_original."|".$total_factura;
						 //echo $cadena_original."<br>";	
						 
						 $cadena_original = $cadena_original."|".$cadena_emisor;
						 //echo $cadena_original."<br>";
						 
						 $cadena_original = $cadena_original."|".$cadena_expedido_en;
						 //echo $cadena_original."<br>";		
						 
						 $cadena_original = $cadena_original."|".$cadena_receptor;
						 //echo $cadena_original."<br>";
						 
						 $cadena_original = $cadena_original.$cadena_concepto;
						 //echo $cadena_original."<br>";
						 
						 $cadena_original = $cadena_original."|".$cadena_traslado."||";
						 echo $cadena_original."<br>";
						 
						 //$cadena_original=strtoupper($cadena_original);
						 //echo $cadena_original."<br>";
						 
						 
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
?>