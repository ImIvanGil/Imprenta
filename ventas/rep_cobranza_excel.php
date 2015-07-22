<?php
session_start();
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();

	  header("Pragma: ");
	  header('Cache-control: ');
	  header("Expires: Mon, 26 Jul 2017 05:00:00 GMT");
	  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	  header("Cache-Control: no-store, no-cache, must-revalidate");
	  header("Cache-Control: post-check=0, pre-check=0", false);
	  header("Content-type: application/vnd.ms-excel");
	  header("Content-disposition: attachment; filename=reporte_cobranza.xls");
	  
	  function cambiarFormatoFecha($fecha){
			list($anio,$mes,$dia)=explode("-",$fecha);
			return $dia."-".$mes."-".$anio;
	  }

	  //recibo los datos de fechas
	  	$inicio = $_GET['inicio'];
		$ninicio = $inicio."T00:00:00";	
		$fin = $_GET['fin'];	
		$nfin = $fin."T24:59:59";
		$finicio = cambiarFormatoFecha($inicio);
		$ffin = cambiarFormatoFecha($fin);
		
		include("../lib/mysql.php");
		$db = new MySQL();
		//obtengo cuantos dias hay entre las dos fechas
		$consDias="SELECT DATEDIFF ('$fin','$inicio') AS dias";
		$lista_d = $db->consulta($consDias);
		while($rowDias= $db->fetch_array($lista_d)){
			$dias=$rowDias['dias']+1;
		}
		
		
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="8"><h4>Impresos Gráficos - Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="8"><b>Reporte de Cuentas por Cobrar</b></td></tr>
            <tr><td align="center" colspan="8"><?php echo "de $finicio al $ffin"?></td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
            <tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>No.</td>
                <td><b>Cliente</td>
                <td><b>Venta <br />Moneda</td>
                <td><b>Anticipos</td>
                <td><b>Por Cobrar</td>
                <td><b>de 1 a 30 D&iacute;as</td>
                <td><b>de 31 a 60 D&iacute;as</td>
                <td><b>m&aacute;s de 60 D&iacute;as</td>
            </tr>
			
			<?php 
				
							
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
				//variables para acumular los totales del reporte
				$glo_1_30_mx = 0;
				$glo_1_30_us = 0;
				$glo_31_60_mx = 0;
				$glo_31_60_us = 0;
				$glo_61_mas_mx = 0;
				$glo_61_mas_us = 0;
				$glo_ant_mx = 0;
				$glo_ant_us = 0;
				$glo_saldo_mx = 0;
				$glo_saldo_us = 0;
				
				//consulto a todos los clientes 
				$cons_cte= "SELECT * FROM `cliente`";
				$cte = $db->consulta($cons_cte);
				while ($rowc = $db->fetch_array($cte))
				//se abre el while principal
				{
				
					$id_cte = $rowc['id_cliente'];
					$nom_cte = utf8_encode($rowc['nombre']);
					
					
					//variables de cantidades
						$v1_30_mx = 0;
						$v1_30_us = 0;
						$v31_60_mx = 0;
						$v31_60_us = 0;
						$v61_mas_mx = 0;
						$v61_mas_us = 0;
						$ant_mx = 0;
						$ant_us = 0;
						$saldo_mx = 0;
						$saldo_us = 0;
					
					//consulto las facturas del periodo para el cliente
					$lista = $db->consulta("SELECT * FROM `factura` WHERE `fecha` between '$ninicio' and '$nfin' AND `id_cliente`=$id_cte and `id_status_factura`='2'");
					
					 
						while ($rowf = $db->fetch_array($lista))
							{
								$clave = $rowf['id_factura'];
								
								//status de la factura
								$id_status = $rowf['id_status_factura'];
								
								//fecha de la factura
								$fecha_fac = $rowf['fecha'];
								$fechas = explode("T", $fecha_fac);
								$solo_fecha = $fechas[0];
								
								//plazo
								$plazo = $rowf['plazo_pago'];
								
								//obtengo la fecha de vencimiento
								$consVenc="SELECT DATE_ADD('".$solo_fecha."',INTERVAL ".$plazo." DAY) as vence";
								$lista_v = $db->consulta($consVenc);
								while($rowVence= $db->fetch_array($lista_v)){
									$vence=$rowVence['vence'];
								}
								
								//obtengo los dias para vencer
								$hoy = date("Y-m-d");
								$consD="SELECT DATEDIFF ('$vence','$hoy') AS dias";
								$lista_dias = $db->consulta($consD);
								while($rowD= $db->fetch_array($lista_dias)){
									$dias_vencer=$rowD['dias']+1;
								}
								
								//buscar el status
								$consulta_sta = $db->consulta("SELECT *  FROM `status_factura` WHERE `id_status_factura` = '".$id_status."';");
								while ($row3 = $db->fetch_array($consulta_sta)){
									$status = $row3['status'];
								}
								
								$id_status_cob = $rowf['id_status_cobranza'];
								//buscar el status de cobro
								$consulta_sta_cob = $db->consulta("SELECT *  FROM `status_cobranza` WHERE `id_status_cobranza` = '".$id_status_cob."';");
								while ($row8 = $db->fetch_array($consulta_sta_cob)){
									$cobranza = $row8['status_cobranza'];
								}
								
								//consultare los abonos que se han hecho a la factura
								  $cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_factura` = '".$clave."';");
								 while ($row2 = $db->fetch_array($cons_abonos)){
									 $abonos = $row2['suma'];
								 }
								
								
								$id_forma = $rowf['id_forma_pago'];
								
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
								
								//datos de cantidades
								$sub_total = 0;
								$graba_normal = 0;
								$graba_cero = 0;
								$exento = 0;
								$iva = 0;
								$total_factura = 0;
								
								$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$clave."'");
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
									//Sumo a los globales segun la moneda
									if($moneda=='MXN'){
										//acumulo los anticipos
										$ant_mx = $ant_mx+$abonos;
										//acumulo el saldo 
										$saldo_mx = $saldo_mx + $saldo;
										//ahora segun el tiempo para vencer acumulo los saldos por plazo
										if($dias_vencer<=30){
											$v1_30_mx = $v1_30_mx + $saldo;
										}else{
											if($dias_vencer<=60){
												$v31_60_mx = $v31_60_mx + $saldo;
											}else{
												$v61_mas_mx = $v61_mas_mx + $saldo; 
											}
										}
									}else{
										if($moneda=='USD'){
											//acumulo los anticipos
											$ant_us = $ant_us+$abonos;
											//acumulo el saldo 
											$saldo_us = $saldo_us + $saldo;
											//ahora segun el tiempo para vencer acumulo los saldos por plazo
											if($dias_vencer<=30){
												$v1_30_us = $v1_30_us + $saldo;
											}else{
												if($dias_vencer<=60){
													$v31_60_us = $v31_60_us + $saldo;
												}else{
													$v61_mas_us = $v61_mas_us + $saldo; 
												}
											}
											
										}
									}
										
									
								}
						 
							}
							
					if($saldo_mx!=0){
						 echo "<tr>
						 <td>".$id_cte."</td>
						 <td>".utf8_decode($nom_cte)."</td>
						 <td>".$moneda."</td>
						 <td>$ ".number_format($ant_mx,2)."</td>
						 <td>$ ".number_format($saldo_mx,2)."</td>
						 <td>$ ".number_format($v1_30_mx,2)."</td>
						 <td>$ ".number_format($v31_60_mx,2)."</td>
						 <td>$ ".number_format($v61_mas_mx,2)."</td>
					     ";
					}
					if($saldo_us!=0){
						echo "<tr>
						 <td>".$id_cte."</td>
						 <td>".utf8_decode($nom_cte)."</td>
						 <td>".$moneda."</td>
						 <td>$ ".number_format($ant_us,2)."</td>
						 <td>$ ".number_format($saldo_us,2)."</td>
						 <td>$ ".number_format($v1_30_us,2)."</td>
						 <td>$ ".number_format($v31_60_us,2)."</td>
						 <td>$ ".number_format($v61_mas_us,2)."</td>
					     ";
					}
					
					//acumulo a los globales
					$glo_1_30_mx = $glo_1_30_mx + $v1_30_mx;
					$glo_1_30_us = $glo_1_30_us + $v1_30_us;
					$glo_31_60_mx = $glo_31_60_mx + $v31_60_mx;
					$glo_31_60_us = $glo_31_60_us + $v31_60_us;
					$glo_61_mas_mx = $glo_61_mas_mx + $v61_mas_mx;
					$glo_61_mas_us = $glo_61_mas_us + $v61_mas_us;
					$glo_ant_mx = $glo_ant_mx + $ant_mx;
					$glo_ant_us = $glo_ant_us + $ant_us;
					$glo_saldo_mx = $glo_saldo_mx + $saldo_mx;
					$glo_saldo_us = $glo_saldo_us + $saldo_us;
					
					 
					
				}
				$cobranza_us_mx = $glo_saldo_us * $tc_usd;
				$global_cobrar = $glo_saldo_mx + $cobranza_us_mx;
				$porc_mx= ($glo_saldo_mx/$global_cobrar)*100;
				$porc_us= ($cobranza_us_mx/$global_cobrar)*100;
				//se cierra el while principal
				echo "</tbody></table>";
				
				echo "<table width=\"100%\" border=\"0\">
					<tr>
						<td colspan=\"2\">
							<b>Resumen Global</b><hr>
							<table  width=\"100%\" border=\"0\">
								<tr>
									<td align=\"right\"><b>Total global por cobrar en M.N.: $</b></td><td align=\"left\">".number_format($global_cobrar,2)."</td>
									<td align=\"right\"><b>Total por cobrar en MXN: $</b></td><td align=\"left\">".number_format($glo_saldo_mx,2)."</td>
									<td align=\"right\"><b>Total por cobrar en USD: $</b></td><td align=\"left\">".number_format($glo_saldo_us,2)."</td>
									
								</tr>
								<tr>
									<td colspan=\"2\"></td>
									<td align=\"right\"><b>% de cuentas en MXN: </b></td><td align=\"left\">".number_format($porc_mx,2)."%</td>
									<td align=\"right\"><b>% de cuentas en USD:</b></td><td align=\"left\">".number_format($porc_us,2)."%</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
					<td width=\"50%\" valign=\"top\">
							<b>Resumen MXN</b><hr>
							<table  width=\"100%\" border=\"0\">
								<tr>
									<td align=\"right\"><b>Total por cobrar en MXN.: $</b></td><td align=\"left\">".number_format($glo_saldo_mx,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total anticipos en MXN: $</b></td><td align=\"left\">".number_format($glo_ant_mx,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total por cobrar a 30 d&iacute;as.: $</b></td><td align=\"left\">".number_format($glo_1_30_mx,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total por cobrar de 31 a 60 d&iacute;as.: $</b></td><td align=\"left\">".number_format($glo_31_60_mx,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total por cobrar a m&aacute;s de 60 d&iacute;as.: $</b></td><td align=\"left\">".number_format($glo_61_mas_mx,2)."</td>
								</tr>
									
							</table>
						</td>
						<td width=\"50%\" valign=\"top\">
							<b>Resumen USD</b><hr>
							<table  width=\"100%\" border=\"0\">
								<tr>
									<td align=\"right\"><b>Total por cobrar en USD.: $</b></td><td align=\"left\">".number_format($glo_saldo_us,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total anticipos en USD: $</b></td><td align=\"left\">".number_format($glo_ant_us,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total por cobrar a 30 d&iacute;as.: $</b></td><td align=\"left\">".number_format($glo_1_30_us,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total por cobrar de 31 a 60 d&iacute;as.: $</b></td><td align=\"left\">".number_format($glo_31_60_us,2)."</td>
								</tr>
								<tr>
									<td align=\"right\"><b>Total por cobrar a m&aacute;s de 60 d&iacute;as.: $</b></td><td align=\"left\">".number_format($glo_61_mas_us,2)."</td>
								</tr>
									
							</table>
						</td>
					</tr>
					<tr>
						<td align=\"left\" colspan=\"6\"><span>*</span>Datos globales de cobranza en base a tipo de cambio actual, datos de ventas en base a tipo de cambio al momento de facturar. Base para comisi&oacute;n desglosando el IVA a tasa normal</td>
					</tr>
				</table>";
		
                
		
			?>
            
</body>
</html>
<?php
}
?> 
<?php
ob_end_flush();
?> 
