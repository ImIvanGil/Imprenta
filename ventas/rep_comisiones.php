<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
$db = new MySQL();

function cambiarFormatoFecha($fecha){
    list($anio,$mes,$dia)=explode("-",$fecha);
    return $dia."-".$mes."-".$anio;
} 
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
		$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra']});
		} 
	);
</script>

<?php
//recibo los datos de fechas
$inicio = $_GET['inicio'];
$ninicio = $inicio."T00:00:00";	
$fin = $_GET['fin'];	
$nfin = $fin."T24:59:59";
//obtengo cuantos dias hay entre las dos fechas
$consDias="SELECT DATEDIFF ('$fin','$inicio') AS dias";
$lista_d = $db->consulta($consDias);
while($rowDias= $db->fetch_array($lista_d)){
	$dias=$rowDias['dias']+1;
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
            <li><a href="reportes.php" class="current">Reportes</a></li>
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
                
            <img src="../images/iconos/onebit_29.png" alt="image 3" />
            <a href="reportes.php">Regresar</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/logo_excel.png" alt="image 1" />
            <a href="<?php echo "rep_comisiones_excel.php?inicio=$inicio&fin=$fin";?>">Exportar a MS Excel</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Reporte de comisiones</h2>
            
			<?php
				$finicio = cambiarFormatoFecha($inicio);
				$ffin = cambiarFormatoFecha($fin);
				echo "<p>Del <b>$finicio</b> al <b>$ffin</b></p>";
				
			?>
                <table cellspacing="0" width="100%" border="0px">
               
            <?php 
				
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
				
				//consulto a todos los vendedores 
				$cons_vend= "SELECT * FROM `empleado` WHERE `id_puesto`='1' AND `id_status`='1'";
				$vend = $db->consulta($cons_vend);
				while ($rowv = $db->fetch_array($vend))
				//se abre el while principal
				{
					//variables para acumular los pagos recibidos
					$suma_pago =0;
					$suma_asignado =0;
					$suma_disponible=0;
					
					$suma_base =0;
					
					
					
					$id_vendedor = $rowv['id_empleado'];
					$nom_emp = $rowv['nombre'];
					$porc_comi = $rowv['comision'];
					echo "<tr><td><h3>Vendedor: $id_vendedor - $nom_emp</h3>";
					//consulto los pagos recibidos en el periodo por los clientes del vendedor
					$cons_pagos_mx = "SELECT * FROM `pago_cliente` WHERE `fecha` BETWEEN '$inicio' AND '$fin' AND `id_cliente` IN (SELECT `id_cliente` FROM `cliente` WHERE `id_vendedor` = '$id_vendedor') ORDER BY fecha";
					$pagos_mx = $db->consulta($cons_pagos_mx);
					
					$cuenta_pagos = $db->num_rows($pagos_mx);
					if($cuenta_pagos<=0){
						echo "<p><i><b>No hay registros de pagos en el periodo</i></b></p>";
					}else{
						
						
						while ($rowp = $db->fetch_array($pagos_mx))
						{
							$id_pago = $rowp['id_pago'];
							$fecha = $rowp['fecha'];
							$id_moneda = $rowp['id_moneda'];
							$tipo_cambio = $rowp['tipo_cambio'];
							$monto = $rowp['monto'];
							
							$monto_mxn = $monto * $tipo_cambio;
							$fmonto_mxn = number_format($monto_mxn,2);
							
							//consulto el monto asignado del pago
							$cons_asi = "SELECT sum(monto) as suma FROM `detalle_pago_cliente` WHERE `id_pago_cliente`=$id_pago";
							$monto_asi= $db->consulta($cons_asi);
							
							$row_asi = $db->fetch_array($monto_asi);
							$monto_asignado = $row_asi['suma'];
							$monto_asignado = $monto_asignado*$tipo_cambio;
							$fmonto_asignado = number_format($monto_asignado,2);							
							
							//calculo si hay monto pendiente de asignar
							$monto_pendiente = $monto_mxn - $monto_asignado;
							$fmonto_pendiente = number_format($monto_pendiente,2);
							
							//Acumulo a los totales
							$suma_pago = $suma_pago+$monto_mxn;
							$suma_asignado =$suma_asignado+$monto_asignado;
							$suma_disponible=$suma_disponible+$monto_pendiente;
							
							$id_cli= $rowp['id_cliente'];
										
							$sql_cli = "SELECT * FROM cliente where `id_cliente`='".$id_cli."'";
							$consulta_cli = $db->consulta($sql_cli);
							while($row_cli = mysql_fetch_array($consulta_cli)){
								$nom_cliente=$row_cli['nombre'];
							}
							
							
							echo "<table cellspacing=\"0\" border=\"1px\" bordercolor=\"#000000\" width=\"100%\" bgcolor=\"#ffffff\">";
							echo "<tr><td><b>No. Pago: </b>$id_pago</td><td><b>Cliente: </b>$nom_cliente</td><td><b>Fecha: </b>$fecha</td></tr>";
							echo "<tr><td><b>Monto: $</b>$fmonto_mxn</td><td><b>Asignado: $</b>$fmonto_asignado</td><td><b>Pendiente: $</b>$fmonto_pendiente</td></tr>";
							echo "</table><br>";
	
	
							//por cada pago voy a consultar las facturas asignadas
							$cons_asigna = "SELECT * FROM `detalle_pago_cliente` WHERE `id_pago_cliente`=$id_pago";
							$asignados = $db->consulta($cons_asigna);
							$cuenta_asigna = $db->num_rows($asignados);
							if($cuenta_asigna<=0){
								//echo "No hay facturas asignadas para el pago con folio $id_pago<br>";
								//echo "Monto no asignado $".number_format($monto)."<br><br>";
							}else{
								
								echo "<table cellspacing=\"0\" border=\"1px\" width=\"100%\">";
							
								echo "<tr align=\"center\" bgcolor=\"#E4EBA9\">
								 <td><b>Folio Fac</b></td>
								 <td><b>Fecha</b></td>
								 <td><b>Vence</b></td>
								 <td><b>Moneda</b></td>
								 <td><b>Tipo Cambio</b></td>
								 <td><b>Sub Total</b></td>
								 <td><b>IVA</b></td>
								 <td><b>Total</b></td>
								 <td><b>Total MXN</b></td>
								 <td><b>Monto Asigna MXN</b></td>
								 <td><b>Base Comision MXN</b></td></tr>
								 ";
								//echo "las facturas asignadas son $cuenta_asigna<br>";
								//obtengo datos de las facturas
								while($row_as = $db->fetch_array($asignados)){
									$id_factura = $row_as['id_factura'];
									$monto_asignado = $row_as['monto'];
									$asignado_mxn = $monto_asignado*$tipo_cambio;
									//ahora los datos de la factura
									$consulta_factura = "SELECT * FROM `factura` WHERE id_factura=$id_factura;";
									$factura = $db->consulta($consulta_factura);
									while($rowf = $db->fetch_array($factura)){
										//voy a separar la fecha de la hora
										$fecha = $rowf['fecha'];
										$fechas = explode("T", $fecha);
										$solo_fecha = $fechas[0];
										
										//plazo de cobro
										$plazo = $rowf['plazo_pago'];
										
										//fecha de vencimiento
										$cons_vence ="SELECT DATE_ADD('$solo_fecha',INTERVAL $plazo DAY) AS vence";
										$consulta_venc = $db->consulta($cons_vence);
										$row_venc = mysql_fetch_array($consulta_venc);
										$vencimiento = $row_venc['vence'];
										
										
										$id_status_cob = $rowf['id_status_cobranza'];
										//buscar el status de cobro
										$consulta_sta_cob = $db->consulta("SELECT *  FROM `status_cobranza` WHERE `id_status_cobranza` = '".$id_status_cob."';");
										while ($row8 = $db->fetch_array($consulta_sta_cob)){
											$cobranza = $row8['status_cobranza'];
										}
										$id_mon = $rowf['id_moneda'];
										//moneda
										$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
										$consulta_mon = $db->consulta($sql_mon);
										$row_mon = mysql_fetch_array($consulta_mon);
										$moneda = $row_mon['moneda'];
							
							
										$t_cambio = $rowf['tipo_cambio'];
										$t_cambio = number_format($t_cambio,2);
										
										$id_iva = $rowf['id_iva'];
										//voy a buscar la tasa del iva que vamos a usar
										$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
										$consulta_tas = $db->consulta($sql_tas);
										$row_tas = mysql_fetch_array($consulta_tas);
										$tasa_iva = $row_tas['tasa'];
										
										//consultare los abonos que se han hecho a la factura
										 $cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_factura` = '".$id_factura."';");
									   while ($row2 = $db->fetch_array($cons_abonos)){
										   $abonos = $row2['suma'];
									   }
										
										//datos de cantidades
										$sub_total = 0;
										$graba_normal = 0;
										$graba_cero = 0;
										$exento = 0;
										$iva = 0;
										$total_factura = 0;
										
										$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$id_factura."'");
										$existe2 = $db->num_rows($detalle);
										if($existe2<=0){
											//no pasa nada
											$total_mn = 0;
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
												$saldo = $total_factura - $abonos;
												if($total_factura <=0){
													$total_mn = 0;
												}else{
													$total_mn = $total_factura * $t_cambio;
												}
												
												
												
											}
											
												
											
										}
										$factor = $tasa_iva/100;
										$factor = $factor+1;
										$base_comi = $asignado_mxn/$factor;
										// acumulo a la base de comsion
										$suma_base = $suma_base+$base_comi;
								
										
										 echo "<tr align=\"center\" bgcolor=\"#FFFFFF\">
										 <td class=\"listado\">".$id_factura."</td>
										 <td class=\"listado\">".$solo_fecha."</td>
										 <td class=\"listado\">".$vencimiento."</td>
										 <td class=\"listado\">".$moneda."</td>
										 <td class=\"listado\">".$t_cambio."</td>
										 <td class=\"listado\">$ ".number_format($sub_total,2)."</td>
										 <td class=\"listado\">$ ".number_format($iva,2)."</td>
										 <td class=\"listado\">$ ".number_format($total_factura,2)."</td>
										 <td class=\"listado\">$ ".number_format($total_mn,2)."</td>
										 <td class=\"listado\">$ ".number_format($asignado_mxn,2)."</td>
										 <td class=\"listado\">$ ".number_format($base_comi,2)."</td>
										 </tr>";
										
									}
								}
								echo "</table><br>";
							}
							
							//echo "<tr><td>$id_pago</td><td>$fecha</td><td>$id_moneda</td><td>$tipo_cambio</td><td>$monto</td><td>$monto_mxn</td></tr>";
						}
						$calculada = $suma_base * $porc_comi;
						
						$comi = $porc_comi*100;
						$comi = number_format($comi,2);
						echo "<b>Resumen</b><br>";
						echo "<table cellspacing=\"0\" border=\"1px\" width=\"100%\" bgcolor=\"#FFFFFF\">";
						echo "<tr><td><b>Monto total pagos: $</b>".number_format($suma_pago,2)."</td>
						<td><b>Monto asignado: $</b>".number_format($suma_asignado,2)."</td>
						<td><b>Pendiente de asignar: $</b>".number_format($suma_disponible,2)."</td>
						</tr>";
						echo "<tr><td><b>Base para comisiones: $</b>".number_format($suma_base,2)."</td>
						<td><b>% aplicable: </b> $comi%</td>
						<td bgcolor=\"#E4EBA9\"><b>Comision Calculada: $ ".number_format($calculada,2)."</b></td>
						</tr>";
						echo "</table>";
						echo "<hr>";
					}
					
					echo "</td></tr>";
				}
				//se cierra el while principal
				echo "</table>";
				
				
		
                
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