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
			header("Content-disposition: attachment; filename=pagos_clientes.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="13"><h4>Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="13"><b>Lista de Pagos de Clientes</b></td></tr>
            <tr><td align="center" colspan="13">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Cliente</b></td>
                <td><b>RFC</b></td>
                <td><b>Fecha</b></td>
                <td><b>Tipo de Pago</b></td>
                <td><b>Motivo nota crédito</b></td>
                <td><b>Referencia</b></td>
                <td><b>Moneda</b></td>
                <td><b>Tipo Cambio</b></td>
                <td><b>Monto</b></td>
                <td><b>Modifico por ultima vez:</b></td>
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

				$lista_facturas = $db->consulta("SELECT *  FROM `pago_cliente`;");
				while ($row = $db->fetch_array($lista_facturas))
					{
					$numero = $row['id_pago'];
					$id_cliente = $row['id_cliente'];
					//datos del cliente
					$sql_cliente = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
					$consulta_cliente = $db->consulta($sql_cliente);
					while($row_cliente = mysql_fetch_array($consulta_cliente)){
						$cliente=$row_cliente['nombre'];
					}
					
					$fecha = $row['fecha'];
					$referencia = $row['referencia'];
					
					$id_mon = $row['id_moneda'];
					//moneda
					$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
					$consulta_mon = $db->consulta($sql_mon);
					$row_mon = mysql_fetch_array($consulta_mon);
					$moneda = $row_mon['moneda'];
					
					$id_motivo = $row['motivo_nota'];
					//moneda
					$sql_mot = "SELECT * FROM motivo_nota_credito WHERE id_motivo='".$id_motivo."'";
					$consulta_mot = $db->consulta($sql_mot);
					$row_mot = mysql_fetch_array($consulta_mot);
					$motivo = $row_mot['motivo'];
					
					$t_cambio = $row['tipo_cambio'];
					$usuario = $row['usuario'];
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
					
					$sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
					$consulta_cliente = $db->consulta($sql_cliente);
					while($row_cliente = mysql_fetch_array($consulta_cliente)){
						$cliente=$row_cliente['nombre'];
						$rfc=$row_cliente['rfc'];
					}
					
					//ahora a imprimir los datos de cada pago
					echo "<tr><td align=\"left\">".$numero."</td>
					 <td>".$cliente."</td>
					 <td>".$rfc."</td>
					 <td align=\"center\">".$fecha."</td>
					 <td align=\"right\">".$condicion."</td>
					 <td align=\"center\">".$motivo."</td>
					 <td align=\"right\">".$referencia."</td>
					 <td align=\"right\">".$moneda."</td>
					 <td align=\"right\">".$t_cambio."</td>
					 <td align=\"right\">".$monto."</td>
					 <td align=\"right\">".$usuario."</td>
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
