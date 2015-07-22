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
	  header("Content-disposition: attachment; filename=ventas_cliente.xls");
	  
	  function cambiarFormatoFecha($fecha){
			list($anio,$mes,$dia)=explode("-",$fecha);
			return $dia."-".$mes."-".$anio;
	  }

	  //recibo los datos
	 	$id_cliente = $_GET['id_cte'];
	  	$inicio = $_GET['inicio'];
		$ninicio = $inicio."T00:00:00";	
		$fin = $_GET['fin'];	
		$nfin = $fin."T24:59:59";
		$finicio = cambiarFormatoFecha($inicio);
		$ffin = cambiarFormatoFecha($fin);
		
		include("../lib/mysql.php");
		$db = new MySQL();
		
		//consulto los datos del cliente
		$sql_cli = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
		$consulta_cli = $db->consulta($sql_cli);
		while($row_cli = mysql_fetch_array($consulta_cli)){
			$nom_cliente=$row_cli['nombre'];
			$rfc=utf8_decode($row_cli['rfc']);
		}
		
		
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="11"><h4>Impresos Gráficos - Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="11"><b>Reporte de Ventas por Cliente</b></td></tr>
            <tr><td align="center" colspan="11"><?php echo "Para $nom_cliente de $finicio al $ffin"?></td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Fecha</b></td>
                <td><b>Estado</b></td>
                <td><b>Tipo de Pago</b></td>
                <td><b>Moneda</b></td>
                <td><b>Tipo Cambio</b></td>
                <td><b>Cobranza</b></td>
                <td><b>Subtotal</b></td>
                <td><b>IVA</b></td>
                <td><b>Total</b></td>
                <td><b>Total M.N.</b></td>
            </tr>
			
			<?php 
				
				//obtengo cuantos dias hay entre las dos fechas
				$consDias="SELECT DATEDIFF ('$fin','$inicio') AS dias";
				$lista_d = $db->consulta($consDias);
				while($rowDias= $db->fetch_array($lista_d)){
					$dias=$rowDias['dias']+1;
				}
				
				
				//variables globales de cantidades
				$glo_sub = 0;
				$glo_iva = 0;
				$glo_tot = 0;
				
				$glo_sub_mx = 0;
				$glo_iva_mx = 0;
				$glo_tot_mx = 0;
				
				$glo_sub_glo = 0;
				$glo_iva_glo = 0;
				$glo_tot_glo = 0;
				
				$glo_act = 0;
				$glo_canc = 0;
				$glo_cert = 0;
				
				$glo_liq = 0;
				$glo_pcob =0;
				$glo_parc = 0;
				
				$cobrar_mx = 0;
				$cobrar_usd = 0;
				
				//consulto los pagos recibidos en el periodo en pesos mexicanos
				$cons_pagos_mx = "SELECT sum(`monto`) as suma FROM `pago_cliente` WHERE `id_cliente`='$id_cliente' AND `id_moneda`='1' AND `fecha` BETWEEN '$inicio' AND '$fin'";
				$pagos_mx = $db->consulta($cons_pagos_mx);
				while ($rowp = $db->fetch_array($pagos_mx))
				{
					$sum_pagos_mx = $rowp['suma'];
				}
				
				//consulto los pagos recibidos en el en dolares
				$cons_pagos_us = "SELECT sum(`monto`) as suma FROM `pago_cliente` WHERE `id_cliente`='$id_cliente' and `id_moneda`='2' AND `fecha` BETWEEN '$inicio' AND '$fin'";
				$pagos_us = $db->consulta($cons_pagos_us);
				while ($rowp = $db->fetch_array($pagos_us))
				{
					$sum_pagos_us = $rowp['suma'];
				}
				
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
				

				$lista = $db->consulta("SELECT * FROM `factura` WHERE id_cliente='$id_cliente' and fecha BETWEEN '$ninicio' AND '$nfin' and `id_status_factura`='2'");
				while ($row = $db->fetch_array($lista))
					{
					$numero = $row['id_factura'];
					
					$fecha = $row['fecha'];
					//voy a separar la fecha de la hora
					$fechas = explode("T", $fecha);
					$solo_fecha = $fechas[0];
					
					$id_mon = $row['id_moneda'];
					//moneda
					$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
					$consulta_mon = $db->consulta($sql_mon);
					$row_mon = mysql_fetch_array($consulta_mon);
					$moneda = $row_mon['moneda'];
					
					$t_cambio = $row['tipo_cambio'];
					$t_cambio = number_format($t_cambio,2);

					$id_iva = $row['id_iva'];
					//voy a buscar la tasa del iva que vamos a usar
					$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
					$consulta_tas = $db->consulta($sql_tas);
					$row_tas = mysql_fetch_array($consulta_tas);
					$tasa_iva = $row_tas['tasa'];
										
					$id_forma = $row['id_forma_pago'];
					//forma de pago
					$sql_condicion = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
					$consulta_condicion = $db->consulta($sql_condicion);
					$row_condicion = mysql_fetch_array($consulta_condicion);
					$condicion = $row_condicion['forma_pago'];
					
					$id_status = $row['id_status_factura'];
					if($id_status==1){
						$glo_act = $glo_act +1;
					}else{
						if($id_status==2){
							$glo_cert = $glo_cert+1;
						}else{
							if($id_status==3){
								$glo_canc = $glo_canc +1;
							}
						}
					}
							
					// estado de la factura
					$sql_status = "SELECT status FROM status_factura WHERE id_status_factura='".$id_status."'";
					$consulta_status = $db->consulta($sql_status);
					$row_status = mysql_fetch_array($consulta_status);
					$status = $row_status['status'];
					
					$id_status_cob = $row['id_status_cobranza'];
					if($id_status_cob==2){
						$glo_pcob = $glo_pcob +1;
					}else{
						if($id_status_cob==3){
							$glo_parc = $glo_parc+1;
						}else{
							if($id_status_cob==4){
								$glo_liq = $glo_liq +1;
							}
						}
					}
					//buscar el status de cobro
					$consulta_sta_cob = $db->consulta("SELECT *  FROM `status_cobranza` WHERE `id_status_cobranza` = '".$id_status_cob."';");
					while ($row8 = $db->fetch_array($consulta_sta_cob)){
						$cobranza = $row8['status_cobranza'];
					}
					//consultare los abonos que se han hecho a la factura
					 $cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_factura` = '".$numero."';");
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
					
					$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$numero."'");
					$existe = $db->num_rows($detalle);
					if($existe<=0){
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
						if($moneda=='MXN'){
							$glo_sub_mx = $glo_sub_mx + $sub_total;
							$glo_iva_mx = $glo_iva_mx + $iva;
							$glo_tot_mx = $glo_tot_mx + $total_factura;
							$cobrar_mx = $cobrar_mx + $saldo;
							
							//acumulo a los totales globales
							$glo_sub_glo = $glo_sub_glo + $sub_total;
							$glo_iva_glo = $glo_iva_glo + $iva;
							$glo_tot_glo = $glo_tot_glo + $total_factura;
						}else{
							if($moneda=='USD'){
								$glo_sub = $glo_sub + $sub_total;
								$glo_iva = $glo_iva + $iva;
								$glo_tot = $glo_tot + $total_factura;
								$cobrar_usd = $cobrar_usd + $saldo;
								
								//convierto a pesos segun el tipo de cambio
								$c1 = $sub_total * $t_cambio;
								$c2 = $iva * $t_cambio;
								$c3 = $total_factura * $t_cambio;
								
								//acumulo a los totales globales
								$glo_sub_glo = $glo_sub_glo + $c1;
								$glo_iva_glo = $glo_iva_glo + $c2;
								$glo_tot_glo = $glo_tot_glo + $c3;
								
							}
						}
						
					}
					
					//ahora a imprimir los datos de cada factura
					echo "<tr><td align=\"center\">".$numero."</td>
					 <td align=\"center\">".$solo_fecha."</td>
					 <td align=\"certer\">".$status."</td>
					 <td align=\"right\">".$condicion."</td>
					 <td align=\"center\">".$moneda."</td>
					 <td align=\"right\">".number_format($t_cambio,2)."</td>
					 <td align=\"center\">".$cobranza."</td>
					 <td align=\"right\">$ ".number_format($sub_total,2)."</td>
					 <td align=\"right\">$ ".number_format($iva,2)."</td>
					 <td align=\"right\">$ ".number_format($total_factura,2)."</td>	
					 <td align=\"right\">$ ".number_format($total_mn,2)."</td>					 
					 </tr>";
					
					}
					
					
					
			
			echo "</table>";
			
            $vtas_prom = $glo_tot_glo/$dias;
            $cobrar_usd_conv = $cobrar_usd*$tc_usd;
            $cobrar_total = $cobrar_mx+$cobrar_usd_conv;
            $pagos_us_conv = $sum_pagos_us * $tc_usd;
            $pagos_total = $sum_pagos_mx + $pagos_us_conv;
			
			echo "<br><table width=\"100%\" border=\"0\">
			<tr>
				<td align=\"left\" colspan=\"6\"><b>RESUMEN:</b></td>
			</tr>
			<tr>
				<td align=\"right\"><b>Subtotal Global: $</b></td><td><b>".number_format($glo_sub_glo,2)."</b></td>
				<td align=\"right\"><b>I.V.A. Global: $</b></td><td><b>".number_format($glo_iva_glo,2)."</b></td>
				<td align=\"right\"><b>Total Global: $</b></td><td><b>".number_format($glo_tot_glo,2)."</b></td>
			</tr>
			<tr>
				<td align=\"right\">Subtotal MXN: $</td><td>".number_format($glo_sub_mx,2)."</td>
				<td align=\"right\">I.V.A. MXN: $</td><td> ".number_format($glo_iva_mx,2)."</td>
				<td align=\"right\">Total MXN: $</td><td> ".number_format($glo_tot_mx,2)."</td>
			</tr>
			<tr>
				<td align=\"right\">Subtotal USD: $</td><td>".number_format($glo_sub,2)."</td>
				<td align=\"right\">I.V.A. USD: $</td><td> ".number_format($glo_iva,2)."</td>
				<td align=\"right\">Total USD: $</td><td> ".number_format($glo_tot,2)."</td>
			</tr>
			<tr>
				<td align=\"right\">Tipo de Cambio: </td><td>".number_format($tc_usd,2)."</td>
				<td align=\"right\">Dias del periodo: </td><td>".$dias."</td>
				<td align=\"right\">Ventas diarias promedio: $</td><td> ".number_format($vtas_prom,2)."</td>
			</tr>
			<tr>
				<td align=\"right\">Cuentas por cobrar en MXN: $</td><td>".number_format($cobrar_mx,2)."</td>
				<td align=\"right\">Cuentas por cobrar en USD: $</td><td> ".number_format($cobrar_usd,2)."</td>
				<td align=\"right\">Total por cobrar: $</td><td> ".number_format($cobrar_total,2)."</td>
			</tr>
			<tr>
				<td align=\"right\">Pagos recibidos en MXN $:</td><td>".number_format($sum_pagos_mx,2)."</td>
				<td align=\"right\">Pagos recibidos en USD $:</td><td>".number_format($sum_pagos_us,2)."</td>
				<td align=\"right\">Total pagos recibidos $:</td><td>".number_format($pagos_total,2)."</td>
			</tr>
			<tr>
				<td align=\"right\">Facturas activas:</td><td> $glo_act</td>
				<td align=\"right\">Facturas certificadas:</td><td> $glo_cert</td>
				<td align=\"right\">Facturas canceladas:</td><td> $glo_canc</td>
			</tr>
			<tr>
				<td align=\"right\">Facturas liquidadas:</td><td> $glo_liq</td>
				<td align=\"right\">Facturas con pago parcial:</td><td> $glo_parc</td>
				<td align=\"right\">Facturas por cobrar:</td><td> $glo_pcob</td>
			</tr>
			<tr>
				<td align=\"left\" colspan=\"6\"><span>*</span>Datos globales de cobranza en base a tipo de cambio actual, datos de ventas en base a tipo de cambio al momento de facturar</td>
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
