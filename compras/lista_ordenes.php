<?php
session_start();
ob_start();
?>
<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
?> 

<?php
			header("Pragma: ");
			header('Cache-control: ');
			header("Expires: Mon, 26 Jul 2017 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Content-type: application/vnd.ms-excel");
			header("Content-disposition: attachment; filename=ordenes_compra.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="11"><h4>Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="11"><b>Lista de Ordenes de Compra</b></td></tr>
            <tr><td align="center" colspan="11">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Proveedor</b></td>
                <td><b>Fecha</b></td>
                <td><b>Moneda</b></td>
                <td><b>Tipo Cambio</b></td>
                <td><b>Estado</b></td>
                <td><b>Ordena</b></td>
                <td><b>Autoriza</b></td>
                <td><b>Plazo</b></td>
                <td><b>Subtotal</b></td>
                <td><b>IVA</b></td>
                <td><b>Total</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();
				//variables globales de cantidades
				$glo_sub = 0;
				$glo_iva = 0;
				$glo_tot = 0;

				$lista_ordenes = $db->consulta("SELECT *  FROM `orden_compra`;");
				while ($row = $db->fetch_array($lista_ordenes))
					{
					$numero = $row['id_orden'];
					$id_proveedor = $row['id_proveedor'];
					//datos del proveedor
					$sql_proveedor = "SELECT * FROM proveedor where `id_proveedor`='".$id_proveedor."'";
					$consulta_proveedor = $db->consulta($sql_proveedor);
					while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
						$proveedor=$row_proveedor['nombre'];
						
					}
					
					$id_status = $row['id_status'];
					// estado de la orden
					$sql_status = "SELECT status FROM status_orden WHERE id_status='".$id_status."'";
					$consulta_status = $db->consulta($sql_status);
					$row_status = mysql_fetch_array($consulta_status);
					$status = $row_status['status'];
					
					
					$plazo = $row['plazo'];
					$id_ordena = $row['id_ordena'];
						//voy a buscar el nombre del usuario que genera la orden
						$sql_us = "SELECT * FROM myuser WHERE ID='".$id_ordena."'";
						$consulta_us = $db->consulta($sql_us);
						$row_us = mysql_fetch_array($consulta_us);
						$ordena = $row_us['userRemark'];
						
						if($id_status!=1){
							$id_autoriza = $row['id_autoriza'];
							//voy a buscar el nombre del usuario que genera la orden
							$sql_us = "SELECT * FROM myuser WHERE ID='".$id_autoriza."'";
							$consulta_us = $db->consulta($sql_us);
							$row_us = mysql_fetch_array($consulta_us);
							$autoriza = $row_us['userRemark'];
						}
					
					$solo_fecha = $row['fecha'];
					$observaciones = $row['observaciones'];
					
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
					
					
					
					//datos de cantidades
					$sub_total = 0;
					$graba_normal = 0;
					$graba_cero = 0;
					$exento = 0;
					$iva = 0;
					$total_factura = 0;
					
					$detalle = $db->consulta("SELECT * FROM `detalle_orden` WHERE id_orden='".$numero."'");
					$existe = $db->num_rows($detalle);
					if($existe<=0){
						//no pasa nada
					}else{
						
						while ($row = $db->fetch_array($detalle))
						{
							$id_detalle = $row['id_detalle'];
							$cantidad = $row['cantidad'];
							$desc = $row['descuento'];
							$unitario = $row['precio'];
							$clase_iva = $row['id_clase_iva'];
							
							
							//consultare el nombre del tipo de iva
							$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
							 while ($row2 = $db->fetch_array($consulta_cl)){
								 $nom_clase = $row2['clase_iva'];
							 }

							//$precio = $unitario * $cantidad;
							
							$pre = $unitario * $cantidad;
							$monto_desc = $pre*$desc/100;
							$precio = $pre-$monto_desc;
							
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
						//Sumo a los globales
							$glo_sub = $glo_sub + $sub_total;
							$glo_iva = $glo_iva + $iva;
							$glo_tot = $glo_tot + $total_factura;
						
					}
					
					
					//ahora a imprimir los datos de cada orden
					echo "<tr><td align=\"left\">".$numero."</td>
					 <td>".$proveedor."</td>
					 <td align=\"center\">".$solo_fecha."</td>
					 <td align=\"center\">".$moneda."</td>
					 <td align=\"right\">".$t_cambio."</td>
					 <td align=\"right\">".$status."</td>
					 <td align=\"right\">".$ordena."</td>
					 <td align=\"right\">".$autoriza."</td>
					 <td align=\"right\">".$plazo."</td>
					 <td align=\"right\">$ ".number_format($sub_total,2)."</td>
					 <td align=\"right\">$ ".number_format($iva,2)."</td>
					 <td align=\"right\">$ ".number_format($total_factura,2)."</td>	
					 <td align=\"right\">".$observaciones."</td>				 
					 </tr>";
					
					}
					
					//finalmente imprimo los totales del reporte
					echo "<tr><td align=\"left\">&nbsp;</td>
					 <td><b>TOTALES</b></td>
					 <td colspan=\"6\">&nbsp;</td>
					 <td align=\"right\"><b>$ ".number_format($glo_sub,2)."</b></td>
					 <td align=\"right\"><b>$ ".number_format($glo_iva,2)."</b></td>
					 <td align=\"right\"><b>$ ".number_format($glo_tot,2)."</b></td>					 
					 </tr>";
					
			?>
			
			
			</table>
			
</body>
</html>
<?php
}
?> 
<?php
ob_end_flush();
?> 
