<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
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

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: {2:{sorter: false},5:{sorter: false},6:{sorter: false}}});
		$("#tablesorter-test2").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: {2:{sorter: false},5:{sorter: false},6:{sorter: false}}});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea liberar el pago de la factura n\u00famero " + numero +"?\nTodos los registros de asigacion a facturas ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
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
			if (document.regProd.unitario.value == "") {
				alert ('Debe escribir el precio unitario');
				document.getElementById('unitario').focus();
				return false;
			}
			if (document.regProd.tiva.value == "-1") {
				alert ('Debe seleccionar la clase de iva que se va a calcular');
				document.getElementById('tiva').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 



<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['agregar1'])) {
	$cve_fac = $_GET['numero'];
	$cant = $_GET['cant'];
	$cve_prod = $_GET['producto'];
	$unitario = $_GET['unitario'];
	$tiva = $_GET['tiva'];
	
	//echo "el costo ".$costo."<br>";
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into detalle_factura(id_factura, id_producto, unitario, cantidad,id_clase_iva) values('".$cve_fac."','".$cve_prod."','".$unitario."','".$cant."','".$tiva."');");  
}
?>



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
            <li><a href="cobros_clientes.php" class="current">Cobranza</a></li>
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
                
            <img src="../images/iconos/onebit_55.png" alt="image 3" />
            <a href="nuevo_pago_cliente.php">Registro de pago</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="buscar_pago_cliente.php">Buscar</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Detalle de Pago</h2>
            <form name="asignaciones" id="asignaciones" method="post" action="registro_asignaciones.php">

			<?php
                $clave = $_GET['numero'];			
				
				
                $pago = $db->consulta("SELECT * FROM `pago_cliente` WHERE id_pago='".$clave."'");
                $existe = $db->num_rows($pago);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del pago con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($pago))
                {
					
					  	$id_cliente = $row['id_cliente'];
						//datos del cliente
						$sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
						$consulta_cliente = $db->consulta($sql_cliente);
						while($row_cliente = mysql_fetch_array($consulta_cliente)){
							$nom_cliente=$row_cliente['nombre'];
							$cve_cte=$row_cliente['clave'];
							$rfc_cliente=$row_cliente['rfc'];
							$calle_cliente=$row_cliente['calle'];
							$numero_cliente=$row_cliente['numero'];
							$numInt_cliente=$row_cliente['no_interior'];
							$colonia_cliente=$row_cliente['colonia'];
							$ciudad_cliente=$row_cliente['ciudad'];
							$estado_cliente=$row_cliente['estado'];
							
							if($numInt_cliente!=""){
								$dir_cliente= $calle_cliente." No.".$numero_cliente."Int.".$numInt_cliente." Col. ".$colonia_cliente;
							}else{
								$dir_cliente= $calle_cliente." No.".$numero_cliente." Col. ".$colonia_cliente;
							}
							
						
							$cp_cliente=$row_cliente['codigo_postal'];
							$tel_cliente=$row_cliente['telefono'];
						}
						
						$fecha = $row['fecha'];
						//voy a separar la fecha de la hora
						$fechas = explode("T", $fecha);
						$solo_fecha = $fechas[0];
						
						$id_tipo = $row['id_tipo_pago'];
						//tipo de pago
						$sql_tipo = "SELECT tipo_pago FROM tipo_pago_cliente WHERE id_tipo_pago='".$id_tipo."'";
						$consulta_tipo = $db->consulta($sql_tipo);
						$row_tipo = mysql_fetch_array($consulta_tipo);
						$tipo = $row_tipo['tipo_pago'];
						
						$id_status = $row['aplicado'];
						switch($id_status){
							case 1:
								$status = "Por Asignar";
							break;
							
							case 2:
								$status = "Asignado";
							break;
							
							case 3:
								$status = "Cancelado";
							break;
						}
						
						$id_motivo = $row['motivo_nota'];
						if($id_motivo>0){
							//si es una nota de credito consulto el motivo de la nota
							$sql_motivo = "SELECT motivo FROM motivo_nota_credito WHERE id_motivo='".$id_motivo."'";
							$consulta_motivo = $db->consulta($sql_motivo);
							$row_motivo = mysql_fetch_array($consulta_motivo);
							$motivo = $row_motivo['motivo'];
						}else{
							$motivo = "no aplica";
						}
						
						//reviso cuanto se ha asignado ya del pago
						$cons_asignacion = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_pago_cliente` = '".$clave."';");
					   	while ($row2 = $db->fetch_array($cons_asignacion)){
						   	$asignado = $row2['suma'];
						}
						
						$referencia = $row['referencia'];
						
						$id_mon = $row['id_moneda'];
						//moneda
						$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
						$consulta_mon = $db->consulta($sql_mon);
						$row_mon = mysql_fetch_array($consulta_mon);
						$moneda = $row_mon['moneda'];
						
						
						$t_cambio = $row['tipo_cambio'];
						$observaciones = $row['observaciones'];
						
						$monto = $row['monto'];
						$fmonto = number_format ($monto,2);
						

			?>
            
            <table align="center" border="0" width="100%" cellspacing="0" cellpadding="0">
            	
                <tr><td align="left">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos Generales del Pago</B></legend>
                    <table border="0" width="750px" align="center">	
                        <tr>
                            <td align="right"><b>Fecha:</b></td>
							<td align="left"><?php echo $solo_fecha; ?></td>
                            <td align="right"><b>Estado:</b></td>
							<td align="left"><?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" width="20%"><b>Cliente:</b></td>
							<td colspan="3" align="left" valign="top">
								<?php echo $cve_cte; ?><br />
								<?php echo $nom_cliente; ?><br />
                                <?php echo $rfc_cliente; ?><br />
                                <?php echo $dir_cliente; ?><br />
                                <?php echo $ciudad_cliente.", ".$estado_cliente; ?><br />
                                <?php echo "C.P. ".$cp_cliente; ?><br />
                                <?php echo "Tel. ".$tel_cliente; ?><br />
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><b>Tipo de Pago:</b></td>
							<td align="left">
							<?php 
								echo $tipo; 
								if($id_tipo==6){
									echo " por $motivo";
								}
							?>
                            </td>
                            
                            <td align="right"><b>Referencia:</b></td>
							<td align="left"><?php echo $referencia; ?></td>
                        </tr>
                        <tr>
                            <td align="right"><b>Moneda:</b></td>
							<td align="left"><?php echo $moneda; ?></td>
                            <td align="right"><b>Tipo de Cambio:</b></td>
							<td align="left"><?php echo number_format($t_cambio,2); ?></td>
                        </tr>
						<?php
							/*if($moneda!="MXN"){
                                echo "<tr>";
								echo "<td align=\"right\"><b>Monto:</b></td>";
                                echo "<td align=\"left\">$$fmonto</td>";
                                echo "<td align=\"right\"><b>Valor en MXN:</b></td>";
								$conversion = $monto * $t_cambio;
								$fconversion = number_format($conversion,2);
								$asignar = $conversion - $asignado;
                                echo "<td align=\"left\">$$fconversion</td>";
                                echo "</tr>";
                            }else{
								echo "<tr>";
								echo "<td align=\"right\"><b>Monto:</b></td>";
                                echo "<td align=\"left\" colspan=\"3\">$$fmonto</td>";
                                echo "</tr>";
								$asignar = $monto - $asignado;
								
							}*/
							
							echo "<tr>";
							echo "<td align=\"right\"><b>Monto:</b></td>";
							echo "<td align=\"left\" colspan=\"3\">$$fmonto</td>";
							echo "</tr>";
							$asignar = $monto - $asignado;
							?>
							<tr>
								<td align="right"><b>Disponible para asignar:</b></td>
								<td align="left" colspan="3"><b>$ <?php echo number_format($asignar,8); ?></b></td>
							</tr>
							<?php 
							if($observaciones!=""){
                                echo "<tr>";
                                echo "<td align=\"right\"><b>Observaciones:</b></td>";
                                echo "<td colspan=\"3\" align=\"left\">$observaciones</td>";
                                echo "</tr>";
                            }
                        ?>
                        
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                <?php 
				if($asignado>0){
				?>
                
                <tr>
                	<td>
                    <div id="data_form">
                    <br />
                      	<fieldset>								  
                        <legend> <B>Facturas Asignadas</B></legend>
                        
                        <table id="tablesorter-test2" class="tablesorter" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th class="header">Factura</th>
                                <th class="header">Fecha</th>
                                <th class="header">Total Factura</th>
                                <th class="header">Monto Asignado</th>
                                <th>Liberar</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
							$db = new MySQL();
							$lista_detalle = $db->consulta("SELECT * FROM `detalle_pago_cliente` WHERE `id_pago_cliente`=$clave;");
							while ($rowd = $db->fetch_array($lista_detalle))
								{
									$id_detalle_pago = $rowd['id_detalle'];
									$id_fac = $rowd['id_factura'];
									$monto_pago = $rowd['monto'];
									//ahora consulto datos de la factura
									$sql_fac = $db->consulta("SELECT * FROM `factura` WHERE `id_factura`=$id_fac;");
									while ($rowf = $db->fetch_array($sql_fac))
									{
										//voy a separar la fecha de la hora
										$fecha = $rowf['fecha'];
										$fechas = explode("T", $fecha);
										$solo_fecha = $fechas[0];
										
										$id_iva = $rowf['id_iva'];
										//voy a buscar la tasa del iva que vamos a usar
										$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
										$consulta_tas = $db->consulta($sql_tas);
										$row_tas = mysql_fetch_array($consulta_tas);
										$tasa_iva = $row_tas['tasa'];
										//datos de cantidades
										$sub_total = 0;
										$graba_normal = 0;
										$graba_cero = 0;
										$exento = 0;
										$iva = 0;
										$total_factura = 0;
										
										$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$id_fac."'");
										$existe = $db->num_rows($detalle);
										if($existe<=0){
											//no pasa nada
										}else{
											
											while ($row = $db->fetch_array($detalle))
											{
												$id_detalle = $row['id_detalle_fact'];
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
												
												//calculo de los valores totalizados
												$iva = $graba_normal * ($tasa_iva/100);
												$total_factura = $sub_total + $iva;
												
											}
											echo "<tr align=\"center\">
											 <td class=\"listado\">".$id_fac."</td>
											 <td class=\"listado\">".$solo_fecha."</td>";
											 echo "<td class=\"listado\" align=\"right\">$".number_format($total_factura,2)."</td>
											 <td class=\"listado\" align=\"right\">$".number_format($monto_pago,2)."</td>";
											 
											 echo "<td class=\"listado\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/ventas/liberar_factura.php?numero=$id_detalle_pago\",\"$id_fac\");><img src=\"../images/iconos/onebit_24.png\" width=\"24px\" align=\"center\"></a></td>";
											
										}
					
									
									}
									
									
									 
								}
								
						?>
                       
                        </tbody>
                        </table>
                        
                        </form>
                    	</fieldset>
                    </div>
                    </td>
                </tr>
                
                <?php 
				}
				?>
                <?php 
				if($asignar>0){
				?>
               
                <tr>
                	<td>
                    <div id="data_form">
                     <br />
                      	<fieldset>								  
                        <legend> <B>Seleccione Facturas</B></legend>
                        
                        <!-- Campos ocultos con datos del pago -->
                        <input type="hidden" name="clave" id="clave" value="<?php echo $clave;?>">	
                        <input type="hidden" name="asignar" id="asignar" value="<?php echo $asignar;?>">
                        <input type="hidden" name="cliente" id="cliente" value="<?php echo $id_cliente;?>">		
                        
                        
                        <table id="tablesorter-test" class="tablesorter" cellspacing="0">
                        <thead>
                            <tr align="center">
                                <th class="header">No.</th>
                                <th class="header">Fecha</th>
                                <th class="header">Vencimiento</th>
                                <th class="header">Total</th>
                                <th class="header">Saldo</th>
                                <th>Saldar</th>
                                <th>Parcial</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 
							$db = new MySQL();
							$i = 0;
							$lista_facturas = $db->consulta("SELECT * FROM `factura` WHERE `id_cliente`=$id_cliente and `id_status_factura`=2 and `id_status_cobranza` BETWEEN 2 AND 3 order by `fecha` ASC;");
							while ($rowf = $db->fetch_array($lista_facturas))
								{
									$id_fac = $rowf['id_factura'];
									$id_status_cob = $rowf['id_status_cobranza'];
									//voy a separar la fecha de la hora
									$fecha = $rowf['fecha'];
									$fechas = explode("T", $fecha);
									$solo_fecha = $fechas[0];
									$plazo = $rowf['plazo_pago'];
									
									$id_monf = $rowf['id_moneda'];
									//moneda
									$sql_monf = "SELECT moneda FROM moneda WHERE id_moneda='".$id_monf."'";
									$consulta_monf = $db->consulta($sql_monf);
									$row_monf = mysql_fetch_array($consulta_monf);
									$moneda_fac = $row_monf['moneda'];

									$t_cambio_fac = $rowf['tipo_cambio'];
									
									//seleccionar la fecha de vencimiento
									$cons_vence ="SELECT DATE_ADD('$solo_fecha',INTERVAL $plazo DAY) AS vence";
									$consulta_venc = $db->consulta($cons_vence);
									$row_venc = mysql_fetch_array($consulta_venc);
									$vencimiento = $row_venc['vence'];
									
									//obtengo la fecha de hoy
									$hoy = date("Y-m-d");
									
									//obtengo cuantos dias quedan o se han pasado del vencimiento
									$consDias="SELECT DATEDIFF ('$vencimiento','$hoy') AS dias";
									$lista_d = $db->consulta($consDias);
									while($rowDias= $db->fetch_array($lista_d)){
										$dias=$rowDias['dias']+1;
									}
									
									
									$id_iva = $rowf['id_iva'];
									//voy a buscar la tasa del iva que vamos a usar
									$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
									$consulta_tas = $db->consulta($sql_tas);
									$row_tas = mysql_fetch_array($consulta_tas);
									$tasa_iva = $row_tas['tasa'];
									
									$id_cli= $rowf['id_cliente'];
									//buscar el nombre del cliente
									$sql_cli = "SELECT * FROM cliente where `id_cliente`='".$id_cli."'";
									$consulta_cli = $db->consulta($sql_cli);
									while($row_cli = mysql_fetch_array($consulta_cli)){
										$nom_cliente=$row_cli['nombre'];
									}
									
									//consultare los abonos que se han hecho a la factura
									if($id_status_cob==3){
										  $cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_factura` = '".$id_fac."';");
										 while ($row2 = $db->fetch_array($cons_abonos)){
											 $abonos = $row2['suma'];
										 }
									}else{
										$abonos = 0;
									}
									
									//datos de cantidades
									$sub_total = 0;
									$graba_normal = 0;
									$graba_cero = 0;
									$exento = 0;
									$iva = 0;
									$total_factura = 0;
									
									$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$id_fac."'");
									$existe = $db->num_rows($detalle);
									if($existe<=0){
										//no pasa nada
									}else{
										
										while ($row = $db->fetch_array($detalle))
										{
											$id_detalle = $row['id_detalle_fact'];
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
											
											//calculo de los valores totalizados
											$iva = $graba_normal * ($tasa_iva/100);
											$total_factura = $sub_total + $iva;
											$total_mxn = $total_factura * $t_cambio_fac;
											$saldo = $total_factura - $abonos;
											
										}
										
									}
					
									
									 echo "<tr align=\"center\">
									 <td class=\"listado\">".$id_fac."</td>
									 <td class=\"listado\">".$solo_fecha."</td>
									 <td class=\"listado\">".$vencimiento;
										if($dias>=5){
										//banderita verde
                                			echo"<br><img src=\"../images/iconos/onebit_48.png\" width=\"20px\"/>";
											echo "&nbsp;&nbsp;&nbsp;";
											echo "<span>".abs($dias)." d&iacute;as para vencer</span>";
										}else{
											if($dias<0){
											//banderita roja
												echo"<br><img src=\"../images/iconos/onebit_49.png\" width=\"20px\"/>";
												echo "&nbsp;&nbsp;&nbsp;";
												echo "<span>Vencida hace ".abs($dias)." d&iacute;as</span>";
											}else{
											//banderita amarilla
												echo"<br><img src=\"../images/iconos/onebit_47.png\" width=\"20px\"/>";
												echo "&nbsp;&nbsp;&nbsp;";
												echo "<span>".abs($dias)." d&iacute;as para vencer</span>";
											}	
										}
									 echo "</td>
									 <td class=\"listado\" align=\"right\">$".number_format($total_factura,8)."</td>
									 <td class=\"listado\" align=\"right\">$".number_format($saldo,8)."</td>
									 <td class=\"listado\"><input type=checkbox name=\"saldar".$i."\" id=\"saldar".$i."\" value=\"si\" ></td>";
									 echo "<td class=\"listado\"><input type=\"text\" class=\"texto\" size=\"10\" name=\"parcial".$i."\" id=\"parcial".$i."\" ></td>";
									 
									echo "<input type=\"hidden\" name=\"tot".$i."\" id=\"tot".$i."\" value=\"$total_factura\">";
									echo "<input type=\"hidden\" name=\"saldo".$i."\" id=\"saldo".$i."\"value=\"$saldo\" >";
									echo "<input type=\"hidden\" name=\"id_fac".$i."\" id=\"id_fac".$i."\" value=\"$id_fac\">";
									 
									$i++;
								}
								
						?>
                       
                        </tbody>
                        </table>
                        <table border="0" width="100%">
                             <tr>
                                <td align="right">
                                    <BR> <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                     <input class="submit_btn reset" type="reset" value="Cancelar"/>
                                </td>
                            </tr>
                        </table>
                        </form>
                    	</fieldset>
                    </div>
                    </td>
                </tr>
                
                <?php 
				}
				?>
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