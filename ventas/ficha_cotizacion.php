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
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el elemento n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
</script>

<!--VALIDAR EL FORMULARIO DE PRODUCTO -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarProd() {
			if (document.regProd.producto.value == "-1") {
				alert ('Debe seleccionar un producto');
				document.getElementById('producto').focus();
				return false;
			}
			if (document.regProd.cant.value == "") {
				alert ('Debe escribir la cantidad');
				document.getElementById('cant').focus();
				return false;
			}
			if (document.regProd.cant.value == "") {
				alert ('Debe escribir el margen de utilidad deseado');
				document.getElementById('margen').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 



<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['agregar1'])) {
	$cve_cot = $_GET['numero'];
	$tiva = $_GET['tiva'];
	$cant = $_GET['cant'];
	$margen = $_GET['margen'];
	$cve_prod = $_GET['producto'];
	$cons_cos = $db->consulta("SELECT sum(`costo`) as suma FROM `producto_insumo` WHERE `id_producto`=$cve_prod;");
	while ($row3 = $db->fetch_array($cons_cos))
	{
	  $costo= $row3['suma'];
	}
	//echo "el costo ".$costo."<br>";
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into detalle_cotizacion(id_cotizacion, id_producto, costo, cantidad, margen,id_clase_iva) values('".$cve_cot."','".$cve_prod."','".$costo."','".$cant."','".$margen."','".$tiva."');");  
}
?>



</head>
<body>

<div id="templatemo_wrapper">

	<div id="templatemo_header">

    	<div id="site_title">
            <h1><a href="#">Sistema Cotizador<span><?php echo 'Bienvenido, <b> '.$curUser.' </b>'; ?></span></a></h1>
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
    
            <h2>Ficha de Datos de Cotizaci&oacute;n</h2>

			<?php
                $clave = $_GET['numero'];
				//variables que acumularan los totales
				$sub_total = 0;
				$graba_normal = 0;
				$graba_cero = 0;
				$exento = 0;
				$iva = 0;
				//variable que acumulara el precio total
				$precio_total = 0;
                $cotizacion = $db->consulta("SELECT * FROM `cotizacion` WHERE id_cotizacion='".$clave."'");
                $existe = $db->num_rows($cotizacion);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la cotizaci&oacute;n con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($cotizacion))
                {
					  	$id_cliente = $row['id_cliente'];
						//datos del cliente
						$sql_cliente = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
						$consulta_cliente = $db->consulta($sql_cliente);
						while($row_cliente = mysql_fetch_array($consulta_cliente)){
							$cliente=$row_cliente['nombre'];
						}
						
						$fecha = $row['fecha'];
						
						$id_iva = $row['id_iva'];
						//voy a buscar la tasa del iva que vamos a usar
						$sql_tas = "SELECT * FROM iva WHERE id_iva='".$id_iva."'";
						$consulta_tas = $db->consulta($sql_tas);
						$row_tas = mysql_fetch_array($consulta_tas);
						$tasa_iva = $row_tas['tasa'];
						$tipo_iva = $row_tas['tipo'];
						
						$id_condicion = $row['id_tipo_pago'];
						//condicion de pago
						$sql_condicion = "SELECT tipo_pago FROM tipo_pago_cliente WHERE id_tipo_pago='".$id_condicion."'";
						$consulta_condicion = $db->consulta($sql_condicion);
						$row_condicion = mysql_fetch_array($consulta_condicion);
						$condicion = $row_condicion['tipo_pago'];
						
						
						$dias = $row['vigencia'];
						
						$id_status = $row['id_status_cotizacion'];
						// estado de la cotizacion
						$sql_status = "SELECT status FROM status_cotizacion WHERE id_status_cotizacion='".$id_status."'";
						$consulta_status = $db->consulta($sql_status);
						$row_status = mysql_fetch_array($consulta_status);
						$status = $row_status['status'];

			?>
            
            <table align="center" border="0" width="100%">
            	<tr>
                    <td align="right">
					<?php 
                        //echo "<a href=\"#\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"images/iconos/onebit_39.png\" width=\"24px\" />";
						//echo "<a href=\"ficha_producto_pdf.php?numero=$clave\" target=\"blank\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"images/iconos/onebit_39.png\" width=\"24px\" />";
                    ?>                 
                     </td>
                </tr>
                <tr><td align="center">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos de Cotizaci&oacute;n </B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="left"><b>Fecha:</b>
							<?php echo $fecha; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Cliente:</b>
							<?php echo utf8_decode($cliente); ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Estado:</b>
							<?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Condici&oacute;n de Pago:</b>
							<?php echo $condicion; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Vigencia:</b>
							<?php echo $dias; ?> d&iacute;as</td>
                        </tr>
                        <tr>
                            <td align="left"><b>Tipo I.V.A.:</b>
							<?php echo $tipo_iva." ".$tasa_iva."%"; ?></td>
                        </tr>
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="center">
                  <!-- Recuadro con los datos de insumos necesarios para el producto -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Productos</B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="center">
                            <form method="get" action="ficha_cotizacion.php" name="regProd" onsubmit="return validarProd()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">	
                            	<table border="0" width="100%">	
                                    <tr>
                                    	<td align="right"><b>Producto: </b></td>
                                    	<td align="left" valign="middle" colspan="9">
                                        <select id="producto" name="producto">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_prod = $db->consulta("SELECT * FROM `producto` ORDER BY id_producto ASC;");
													while ($row3 = $db->fetch_array($lista_prod))
													{
													  $id_prod = $row3['id_producto'];
													  $nombre = utf8_decode($row3['nombre']);
													  
													  //consultare el costo de producto para mostrarlo
													  $cons_cos = $db->consulta("SELECT sum(`costo`) as suma FROM `producto_insumo` WHERE `id_producto`=$id_prod;");
													  while ($row3 = $db->fetch_array($cons_cos))
														{
														  $costo_uni= $row3['suma'];
														  $costo_uni_for = number_format($costo_uni,2);
														}
													  
													  
													  
													  $desc = $nombre." - $".$costo_uni_for;
													  
													  echo "<option value=\"".$id_prod."\">".$desc."</option>";
													}
											?>
                                        </select></td>
                                        </tr><tr>
                                        <td align="right"><b>Cantidad: </b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="5" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>M&aacute;rgen: </b></td>
                                    	<td><input class="texto" type="text" id="margen" name="margen" size="5" /> % <br /></td>
                                        
                                        <td>&nbsp;</td>
                                        
                                        <td align="right"><b>Tipo I.V.A.: </b></td>
                                    	<td align="left" valign="middle">
                                        <select id="tiva" name="tiva">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_iva = $db->consulta("SELECT * FROM `clase_iva` ORDER BY id_clase ASC;");
													while ($row3 = $db->fetch_array($lista_iva))
													{
													  $id_clase = $row3['id_clase'];
													  $clase_iva = $row3['clase_iva'];
													  echo "<option value=\"".$id_clase."\">".$clase_iva."</option>";
													}
											?>
                                        </select></td>
                                        <td>&nbsp;</td>
                                        <td><input class="submit_btn reset" type="submit" name="agregar1" id="agregar1" value="Agregar"/></td>
                                    </tr>
                                </table>
                            </form>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
								<?php 
                                    $detalle = $db->consulta("SELECT * FROM `detalle_cotizacion` WHERE id_cotizacion='".$clave."'");
                                    $existe = $db->num_rows($detalle);
                                    if($existe<=0){
                                        echo "<p align=\"center\">No se han agregado productos a la cotizaci&oacute;n</p>";
                                    
                                    }else{
									?>
										<table id="tablesorter-ins" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Producto</th>
                                                <th class="header">Descripci&oacute;n</th>
                                                <th class="header">Tipo I.V.A.</th>
                                                <th class="header">Costo Unitario</th>
                                                <th class="header">Cantidad</th>
                                                <th class="header">M&aacute;rgen</th>
                                                <th class="header">Precio Total</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$i = 1;
										while ($row = $db->fetch_array($detalle))
										{
											$id_detalle = $row['id_detalle_cot'];
											$id_producto = $row['id_producto'];
											$cantidad = $row['cantidad'];
											
											$margen = $row['margen'];
											$factor = 1+($margen/100);
											
											$costo = $row['costo'];
											
											$clase_iva = $row['id_clase_iva'];
											//consultare el nombre del tipo de iva
											$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
										   while ($row2 = $db->fetch_array($consulta_cl)){
										   	$nom_clase = $row2['clase_iva'];
										   }
											
											$precio = $costo * $cantidad * $factor;
											$sub_total = $sub_total + $precio;
											
											//lo multiplico por 100 para expresarlo en porcentaje
											//$margen = $margen*100;
											
											
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
											
											//ahora imprimire la tabla
											?>
                                                
            
													<?php 
                                        
                                                       echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
                                                             <td>".utf8_decode($nom_producto)."</td>
															 <td align=\"center\">".$desc_producto."</td>
															 <td align=\"center\">".$nom_clase."</td>
															 <td align=\"right\">$".number_format($costo,2)."</td>
															 <td align=\"right\">".number_format($cantidad,2)."</td>
															 <td align=\"right\">".number_format($margen,2)."%</td>
															 <td align=\"right\">$".number_format($precio,2)."</td>
                                                             <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_cot_prod.php?numero=$clave&key=$id_detalle\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
                                                             <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/ventas/borrar_cot_prod.php?numero=$clave&key=$id_detalle\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
                                                             
                                                             </tr>";
                                                            
                                                            
                                                    ?>
                                                
											
											<?php	
											$i++;
										}
									}
                                ?>
                            		</tbody>
                                </table>
                                <?php 
									//calculo de los valores totalizados
									
									$iva = $graba_normal * ($tasa_iva/100);
									$precio_total = $sub_total + $iva;
								
								?>
                                
                                <b>Sub Total: $</b><?php echo number_format($sub_total,2);?><br />
                                <b>I.V.A.: $</b><?php echo number_format($iva,2);?><br />
                                <b>Total: $</b><?php echo number_format($precio_total,2);?><br />
                                <?php echo "<p><a href=\"cotizacion_pdf.php?clave=$clave\"><i>Descargar cotizaci&oacute;n en PDF</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></p>";
						?>
                            </td>
                        </tr>
                        
                    </table>
                    
                  </fieldset>
                  </div>
                </td></tr>
                
                
               
            </table>  
            </form>
            <?php
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