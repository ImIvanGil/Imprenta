<?php
session_start();
ob_start();
include("../adminuser/adminpro_class.php");
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
			header("Content-disposition: attachment; filename=cargos.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="11"><h4>Sistema erp</h4></td></tr>
			<tr><td align="center" colspan="11"><b>Lista de Cargos</b></td></tr>
            <tr><td align="center" colspan="11">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Proveedor</b></td>
                <td><b>Fecha</b></td>
                <td><b>Plazo</b></td>
                <td><b>Referencia</b></td>
                <td><b>Tipo de Pago</b></td>
                <td><b>Moneda</b></td>
                <td><b>Tipo Cambio</b></td>
                <td><b>Estado</b></td>
                <td><b>Subtotal</b></td>
                <td><b>Impuesto</b></td>
                <td><b>Total</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();
				
				//variables para los totales
				$glo_sub = 0;
				$glo_imp = 0;
				$glo_tot = 0;

				$lista_cargos = $db->consulta("SELECT *  FROM `cargo`;");
				while ($row = $db->fetch_array($lista_cargos))
					{
					$numero = $row['id_cargo'];
					$id_proveedor = $row['id_proveedor'];
					//datos del proveedor
					$sql_proveedor = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
					$consulta_proveedor = $db->consulta($sql_proveedor);
					while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
						$proveedor=$row_proveedor['nombre'];
					}
					
					$fecha = $row['fecha'];
					$plazo = $row['plazo_pago'];
					
					$id_mon = $row['id_moneda'];
					//moneda
					$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
					$consulta_mon = $db->consulta($sql_mon);
					$row_mon = mysql_fetch_array($consulta_mon);
					$moneda = $row_mon['moneda'];
					
					$t_cambio = $row['tipo_cambio'];
					$t_cambio = number_format($t_cambio,2);
				
					$id_forma = $row['id_forma_pago'];
					//forma de pago
					$sql_condicion = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
					$consulta_condicion = $db->consulta($sql_condicion);
					$row_condicion = mysql_fetch_array($consulta_condicion);
					$condicion = $row_condicion['forma_pago'];
					
					$id_status = $row['id_status_cargo'];
					// estado de la cotizacion
					$sql_status = "SELECT status FROM status_cargo WHERE id_status_cargo='".$id_status."'";
					$consulta_status = $db->consulta($sql_status);
					$row_status = mysql_fetch_array($consulta_status);
					$status = $row_status['status'];
					
					
					$referencia = $row['referencia'];
					$subtotal = $row['sub_total'];
					$impuesto = $row['impuestos'];
					$total = $subtotal+$impuesto;
					
					//sumar a los globales
					$glo_sub = $glo_sub + $subtotal;
					$glo_imp = $glo_imp + $impuesto;
					$glo_tot = $glo_tot + $total;
					
					
					
					//ahora a imprimir los datos de cada cargo
					echo "<tr><td align=\"left\">".$numero."</td>
					 <td>".$proveedor."</td>
					 <td align=\"center\">".$fecha."</td>
					 <td align=\"center\">".$plazo."</td>
					 <td align=\"center\">".$referencia."</td>
					 <td align=\"right\">".$condicion."</td>
					 <td align=\"center\">".$moneda."</td>
					 <td align=\"right\">".$t_cambio."</td>
					 <td align=\"right\">".$status."</td>
					 <td align=\"right\">$ ".number_format($subtotal,2)."</td>
					 <td align=\"right\">$ ".number_format($impuesto,2)."</td>
					 <td align=\"right\">$ ".number_format($total,2)."</td>					 
					 </tr>";
					
					}
					
					//finalmente imprimo los totales del reporte
					echo "<tr><td align=\"left\">&nbsp;</td>
					 <td><b>TOTALES</b></td>
					 <td colspan=\"7\">&nbsp;</td>
					 <td align=\"right\"><b>$ ".number_format($glo_sub,2)."</b></td>
					 <td align=\"right\"><b>$ ".number_format($glo_imp,2)."</b></td>
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
