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
	  header("Content-disposition: attachment; filename=cotizaciones.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="5"><h4>Sistema Cotizador</h4></td></tr>
			<tr><td align="center" colspan="5"><b>Lista de Cotizaciones</b></td></tr>
            <tr><td align="center" colspan="5">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Cliente</b></td>
                <td><b>Fecha</b></td>
                <td><b>Tipo de Pago</b></td>
                <td><b>Vigencia</b></td>
                <td><b>Estado</b></td>
                <td><b>Monto</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();
				
				$lista_cotizaciones = $db->consulta("SELECT *  FROM `cotizacion`;");
				while ($row = $db->fetch_array($lista_cotizaciones))
					{
					$numero = $row['id_cotizacion'];
					$id_cliente = $row['id_cliente'];
					//datos del cliente
					$sql_cliente = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
					$consulta_cliente = $db->consulta($sql_cliente);
					while($row_cliente = mysql_fetch_array($consulta_cliente)){
						$cliente=$row_cliente['nombre'];
					}
					
					$fecha = $row['fecha'];
					
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
					
				
					//obtener el monto de la cotizacion
					$precio_total = 0;
					$detalle = $db->consulta("SELECT * FROM `detalle_cotizacion` WHERE id_cotizacion='".$numero."'");
					while ($row = $db->fetch_array($detalle))
					{
					
						$cantidad = $row['cantidad'];
						
						$margen = $row['margen'];
						$factor = 1+($margen/100);
						
						$costo = $row['costo'];
						
						$precio = $costo * $cantidad * $factor;
						$precio_total = $precio_total + $precio;
					}
					
					//ahora a imprimir los datos de cada cotizacion
					echo "<tr><td>".$numero."</td>
					 <td>".$cliente."</td>
					 <td>".$fecha."</td>
					 <td align=\"right\">".$condicion."</td>
					 <td align=\"center\">".$dias." dias</td>
					 <td align=\"right\">".$status."</td>
					 <td align=\"right\">$ ".number_format($precio_total,2)."</td>					 
					 </tr>";
					
					}
			?>
			
			
			</table>
			
</body>
</html>
<?php
}
ob_end_flush();
?> 
