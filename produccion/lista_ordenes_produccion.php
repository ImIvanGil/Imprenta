<?php
session_start();
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();

include("../lib/mysql.php");

			header("Pragma: ");
			header('Cache-control: ');
			header("Expires: Mon, 26 Jul 2017 05:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", false);
			header("Content-type: application/vnd.ms-excel");
			header("Content-disposition: attachment; filename=ordenes_de_produccion.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="13"><h4>Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="13"><b>Ordenes de Produccion</b></td></tr>
            <tr><td align="center" colspan="13">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#881212">
			
			<tr valign="middle" align="center" bgcolor="#dfdfdf">
                <td><b>No.</b></td>
                <td><b>Fecha Orden</b></td>
                <td><b>Fecha Entrega</b></td>
                <td><b>Vendedor</b></td>
                <td><b>Cliente</b></td>
                <td><b>Orden de Compra</b></td>
                <td><b>Linea</b></td>
                <td><b>Producto</b></td>
                <td><b>Unidad</b></td>
                <td><b>Cantidad</b></td>
                <td><b>Estado</b></td>
                <td><b>Urgente</b></td>
                <td><b>Observaciones</b></td>
            </tr>
			
			<?php 
				$db = new MySQL();

				$lista_ordenes = $db->consulta("SELECT *  FROM `orden_produccion` ORDER BY id_orden ASC;");
				while ($row = $db->fetch_array($lista_ordenes))
					{
					$numero = $row['id_orden'];
					$fecha = $row['fecha'];
					$fecha_entrega = $row['fecha_entrega'];
					
					$id_estado = $row['id_estado'];
					$observaciones = $row['observaciones'];
					//buscar el estado
					$consulta_est = $db->consulta("SELECT *  FROM `status_orden_produccion` WHERE `id_status` = '".$id_estado."';");
					while ($row2 = $db->fetch_array($consulta_est)){
						$estado = $row2['status'];
					}
					
					$id_cliente = $row['id_cliente'];
					
					$id_cliente = $row['id_cliente'];
					//datos del cliente
					$sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
					$consulta_cliente = $db->consulta($sql_cliente);
					while($row_cliente = mysql_fetch_array($consulta_cliente)){
						$nom_cliente=$row_cliente['nombre'];
					}
					
					$id_vendedor = $row['id_vendedor'];
					//datos del vendedor
						$sql_vendedor = "SELECT * FROM myuser where `ID`='".$id_vendedor."'";
						$consulta_vendedor = $db->consulta($sql_vendedor);
						while($row_vend = mysql_fetch_array($consulta_vendedor)){
							$nom_vendedor=$row_vend['userRemark'];
						}
					
					$urgente = $row['urgente'];
					$orden_compra = $row['orden_compra'];
					
					//detalle de la orden
					$cons = "SELECT * FROM `detalle_orden_produccion` WHERE id_orden='".$numero."'";
					  $productos = $db->consulta($cons);
					  while ($row = $db->fetch_array($productos))
					  {
						  $id_detalle = $row['id_detalle'];
						  $id_producto = $row['id_producto'];
						  $cantidad = $row['cantidad'];
						  
						  //tambien consultare los datos de la tabla producto
							$consulta_prod = $db->consulta("SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';");
							   while ($row2 = $db->fetch_array($consulta_prod)){
								   $nom_producto = $row2['nombre'];
								   $cve_prod = $row2['clave'];
								   $id_unidad = $row2['id_unidad'];
								   
								   //aqui dentro consulto el nombre de la unidad
									  $cons_uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
									   while ($row15 = $db->fetch_array($cons_uni)){
										   $unidad = $row15['unidad'];
									  }
									  
									  
									 $id_linea = $row2['id_linea'];
								   //aqui dentro consulto el nombre de la linea
									  $cons_lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
									   while ($row15 = $db->fetch_array($cons_lin)){
										   $linea = $row15['linea'];
									  }
								}
									
						  
					  }
					
					
					 
					echo "<tr>";
					echo "<td align=\"center\">$numero</td>";
					echo "<td align=\"center\">$fecha</td>";
					echo "<td align=\"center\">$fecha_entrega</td>";
					echo "<td>$nom_vendedor</td>";
					echo "<td>$nom_cliente</td>";
					echo "<td align=\"left\">$orden_compra</td>";
					echo "<td>$linea</td>";
					echo "<td>$nom_producto</td>";
					echo "<td align=\"center\">$unidad</td>";
					echo "<td>$cantidad</td>";
					echo "<td>$estado</td>";
					echo "<td align=\"center\">$urgente</td>";
					echo "<td>$observaciones</td>";
					echo "</tr>";
					}
			?>
			
			
			</table>
			
</body>
</html>
<?php
}
ob_end_flush();
?> 
