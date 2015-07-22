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
header("Content-disposition: attachment; filename=pagos_proveedores.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="11"><h4>Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="11"><b>Lista de Pagos de Proveedores</b></td></tr>
            <tr><td align="center" colspan="11">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Proveedor</b></td>
                <td><b>RFC</b></td>
                <td><b>Fecha</b></td>
                <td><b>Tipo de Pago</b></td>
                <td><b>Referencia</b></td>
                <td><b>Moneda</b></td>
                <td><b>Tipo Cambio</b></td>
                <td><b>Monto</b></td>
                <td><b>Estado</b></td>
                <td><b>Observaciones</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();
				//variables globales de cantidades
				$glo_sub = 0;
				$glo_iva = 0;
				$glo_tot = 0;

				$lista_cargos = $db->consulta("SELECT *  FROM `pago_proveedor`;");
				while ($row = $db->fetch_array($lista_cargos))
					{
					$numero = $row['id_pago'];
					$id_proveedor = $row['id_proveedor'];
					//datos del proveedor
					$sql_proveedor = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
					$consulta_proveedor = $db->consulta($sql_proveedor);
					while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
						$proveedor=$row_proveedor['nombre'];
					}
					
					$fecha = $row['fecha'];
					$referencia = $row['referencia'];
					
					$id_mon = $row['id_moneda'];
					//moneda
					$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
					$consulta_mon = $db->consulta($sql_mon);
					$row_mon = mysql_fetch_array($consulta_mon);
					$moneda = $row_mon['moneda'];
					
					
					$t_cambio = $row['tipo_cambio'];
					$t_cambio = number_format($t_cambio,2);
					$observaciones = $row['observaciones'];

					$id_forma = $row['id_tipo_pago'];
					//forma de pago
					$sql_condicion = "SELECT * FROM tipo_pago_cliente WHERE id_tipo_pago='".$id_forma."'";
					$consulta_condicion = $db->consulta($sql_condicion);
					$row_condicion = mysql_fetch_array($consulta_condicion);
					$condicion = $row_condicion['tipo_pago'];
					
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
					
					$monto = $row['monto'];
					$monto = number_format($monto,2);
					
					$sql_proveedor = "SELECT * FROM proveedor where `id_proveedor`='".$id_proveedor."'";
					$consulta_proveedor = $db->consulta($sql_proveedor);
					while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
						$proveedor=$row_proveedor['nombre'];
						$rfc=$row_proveedor['rfc'];
					}
					
					//ahora a imprimir los datos de cada pago
					echo "<tr><td align=\"left\">".$numero."</td>
					 <td>".$proveedor."</td>
					 <td>".$rfc."</td>
					 <td align=\"center\">".$fecha."</td>
					 <td align=\"right\">".$condicion."</td>
					 <td align=\"right\">".$referencia."</td>
					 <td align=\"right\">".$moneda."</td>
					 <td align=\"right\">".$t_cambio."</td>
					 <td align=\"right\">".$monto."</td>
					 <td align=\"right\">".$status."</td>
					 <td align=\"right\">".$observaciones."</td>				 
					 </tr>";
					 
					 
					
					}
					
					
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
