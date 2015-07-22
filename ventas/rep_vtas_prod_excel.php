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
	  header("Content-disposition: attachment; filename=ventas_producto.xls");
	  
	  function cambiarFormatoFecha($fecha){
			list($anio,$mes,$dia)=explode("-",$fecha);
			return $dia."-".$mes."-".$anio;
	  }

		include("../lib/mysql.php");
		$db = new MySQL();
	  //recibo los datos
		$id_producto = $_GET['id_prod'];
		$inicio = $_GET['inicio'];
		$ninicio = $inicio."T00:00:00";	
		$fin = $_GET['fin'];	
		$nfin = $fin."T24:59:59";
		$finicio = cambiarFormatoFecha($inicio);
		$ffin = cambiarFormatoFecha($fin);
		//obtengo cuantos dias hay entre las dos fechas
		$consDias="SELECT DATEDIFF ('$fin','$inicio') AS dias";
		$lista_d = $db->consulta($consDias);
		while($rowDias= $db->fetch_array($lista_d)){
			$dias=$rowDias['dias']+1;
		}
		
		//consulto los datos del producto
		$producto = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$id_producto."'");
		while ($row = $db->fetch_array($producto))
		{
			$nombre_prod = $row['nombre'];
		}
		
		
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="9"><h4>Impresos Gráficos - Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="9"><b>Reporte de Ventas Por Producto</b></td></tr>
            <tr><td align="center" colspan="9"><?php echo "Para el producto $nombre_prod de $finicio al $ffin"?></td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Factura</td>
                <td><b>Fecha</td>
                <td><b>Moneda</td>
                <td><b>T.C.</td>
                <td><b>Unitario</td>
                <td><b>Cantidad</td>
                <td><b>Tipo IVA</td>
                <td><b>Precio</td>
                <td><b>Precio M.N.</td>
            </tr>
			
			<?php 
			//variables globales de cantidades
			
			//consulto los datos del producto
			$producto = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$id_producto."'");
			while ($row = $db->fetch_array($producto))
			{
				$nombre_prod = utf8_decode($row['nombre']);
			}
			
			//consulto el tipo de cambio de pesos contra dolares registrados
			$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
			$tc = $db->consulta($cons_tc);
			while ($rowt = $db->fetch_array($tc))
			{
				$tc_usd = $rowt['precio_compra'];
			}
			
					$glo_usd =0;
					$glo_mxn =0;
					$glo_final =0;
					$glo_cant =0;
				//consulto los detalles de factura que contienen el producto en el periodo
				$lista = $db->consulta("SELECT * FROM `detalle_factura` WHERE `id_producto`='$id_producto' AND `id_factura` IN (SELECT `id_factura` FROM `factura` WHERE `fecha` BETWEEN '$ninicio' AND '$nfin' and `id_status_factura`='2')");
				
				$existe = $db->num_rows($lista);
                if($existe<=0){
                    echo "<p>No hay informaci&oacute;n de operaciones entre las fechas especificadas, verifique sus datos.</p>";
                }else{
				
					
				
					while ($rowf = $db->fetch_array($lista))
                        {
							$id_det = $rowf['id_detalle_fact'];
							$clave = $rowf['id_factura'];
							$unitario = $rowf['unitario'];
							$cantidad = $rowf['cantidad'];
							$clase_iva = $rowf['id_clase_iva'];
							
							//consultare los datos de moneda y TC de cada factura
							$consulta_fac = $db->consulta("SELECT *  FROM `factura` WHERE `id_factura` = '".$clave."';");
							while ($row2 = $db->fetch_array($consulta_fac)){
								$id_mon = $row2['id_moneda'];
								//moneda
								$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
								$consulta_mon = $db->consulta($sql_mon);
								$row_mon = mysql_fetch_array($consulta_mon);
								$moneda = $row_mon['moneda'];
								
								$t_cambio = $row2['tipo_cambio'];
								$t_cambio = number_format($t_cambio,2);
								
								//voy a separar la fecha de la hora
								$fecha = $row2['fecha'];
								$fechas = explode("T", $fecha);
								$solo_fecha = $fechas[0];
								
								$id_iva = $row2['id_iva'];
								//voy a buscar la tasa del iva que vamos a usar
								$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
								$consulta_tas = $db->consulta($sql_tas);
								$row_tas = mysql_fetch_array($consulta_tas);
								$tasa_iva = $row_tas['tasa'];
							}
							
							
							//consultare el nombre del tipo de iva
							$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
							 while ($row2 = $db->fetch_array($consulta_cl)){
								 $nom_clase = $row2['clase_iva'];
							 }
									 
							
							$precio = $unitario * $cantidad;
							$precio_mn = $precio * $t_cambio;
									
							
							//acumulo a los globales
							if($moneda=='MXN'){
								$glo_mxn = $glo_mxn + $precio;
							}else{
								if($moneda=='USD'){
									$glo_usd = $glo_usd + $precio;
								}
							}
							
							$glo_cant = $glo_cant+$cantidad;		
							
							
							 echo "<tr align=\"center\">
							 <td>".$clave."</td>
							 <td>".$solo_fecha."</td>
							 <td>".$moneda."</td>
							 <td>".$t_cambio."</td>
							 <td align=\"right\">$ ".number_format($unitario,2)."</td>
							 <td align=\"right\">".number_format($cantidad,2)."</td>
							 <td>".$nom_clase."</td>
							 <td align=\"right\">$ ".number_format($precio,2)."</td>
							 <td align=\"right\">$ ".number_format($precio_mn,2)."</td>
							 ";
							 
							 
                        }
					}
               
                	echo "</tbody></table>";
					
					
					$glo_usd_conv = $glo_usd * $tc_usd;
					$glo_final = $glo_mxn + $glo_usd_conv;
					
					$venta_diaria_p = $glo_final/$dias;
					$precio_promedio = $glo_final/$glo_cant;
					
					echo "<table width=\"100%\" border=\"0\">
					
					<tr>
						<td align=\"right\"><b>Unidades vendidas: </b></td><td><b>".number_format($glo_cant,2)."</b></td>
						<td align=\"right\"><b>Precio promedio de venta: $</b></td><td><b>".number_format($precio_promedio,2)."</b></td>
						<td align=\"right\"><b>Venta diaria promedio: $</b></td><td><b>".number_format($venta_diaria_p,2)."</b></td>
					</tr>
					
					<tr>
						<td align=\"right\">Ventas en MXN: $</td><td>".number_format($glo_mxn,2)."</td>
						<td align=\"right\">Ventas en USD: $</td><td> ".number_format($glo_usd,2)."</td>
						<td align=\"right\">Ventas totales en MXN: $</td><td> ".number_format($glo_final,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Tipo de Cambio: </td><td>".number_format($tc_usd,2)."</td>
						<td align=\"right\">Dias del periodo: </td><td>".$dias."</td>
						<td align=\"right\" colspan=\"2\">&nbsp;</td>
					</tr>
					<tr>
						<td align=\"left\" colspan=\"6\"><span>*</span>Montos de venta sin IVA</td>
					</tr>
					<tr>
						<td align=\"left\" colspan=\"6\"><span>*</span>Datos globales en base a tipo de cambio actual</td>
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
