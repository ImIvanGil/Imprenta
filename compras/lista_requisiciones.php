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
			header("Content-disposition: attachment; filename=requisiciones_compra.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="7"><h4>Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="7"><b>Lista de Requisiciones de Compra</b></td></tr>
            <tr><td align="center" colspan="7">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>Clave</b></td>
                <td><b>Proveedor</b></td>
                <td><b>Fecha</b></td>
                <td><b>Estado</b></td>
                <td><b>Ordena</b></td>
                <td><b>Autoriza</b></td>
                <td><b>Propósito</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();

				$lista_requisiciones = $db->consulta("SELECT *  FROM `requisicion`;");
				while ($row = $db->fetch_array($lista_requisiciones))
					{
					$numero = $row['id_requisicion'];
					$id_proveedor = $row['id_proveedor'];
					//datos del proveedor
					$sql_proveedor = "SELECT * FROM proveedor where `id_proveedor`='".$id_proveedor."'";
					$consulta_proveedor = $db->consulta($sql_proveedor);
					while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
						$proveedor=$row_proveedor['nombre'];
						
					}
					
					$id_status = $row['id_status'];
					// estado de la requisicion
					$sql_status = "SELECT status FROM status_orden WHERE id_status='".$id_status."'";
					$consulta_status = $db->consulta($sql_status);
					$row_status = mysql_fetch_array($consulta_status);
					$status = $row_status['status'];
					
					$solo_fecha = $row['fecha'];
					$observaciones = $row['observaciones'];
					
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
					
					
					//ahora a imprimir los datos de cada requisicion
					echo "<tr><td align=\"left\">".$numero."</td>
					 <td>".$proveedor."</td>
					 <td align=\"center\">".$solo_fecha."</td>
					 <td align=\"right\">".$status."</td>
					 <td align=\"right\">".$ordena."</td>
					 <td align=\"right\">".$autoriza."</td>
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
