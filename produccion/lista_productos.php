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
			header("Content-disposition: attachment; filename=productos.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="13"><h4>Sistema ERP</h4></td></tr>
			<tr><td align="center" colspan="13"><b>Cat&aacute;logo de Productos</b></td></tr>
            <tr><td align="center" colspan="13">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#889b08">
			
			<tr valign="middle" align="center" bgcolor="#e3ef77">
                <td><b>No.</b></td>
                <td><b>Clave</b></td>
                <td><b>Nombre</b></td>
                <td><b>Precio</b></td>
                <td><b>Descripci&oacute;n</b></td>
                <td><b>Tama&ntilde;o</b></td>
                <td><b>Tinta</b></td>
                <td><b>Unidad</b></td>
                <td><b>Prensa</b></td>
                <td><b>Papel</b></td>
                <td><b>Laminado</b></td>
                <td><b>Barnizado</b></td>
                <td><b>Etiquetadora</b></td>
                
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();

				$lista_productos = $db->consulta("SELECT *  FROM `producto` ORDER BY id_producto;");
				while ($row = $db->fetch_array($lista_productos))
					{
					$numero = $row['id_producto'];
					$cve = $row['clave'];
					$nombre = $row['nombre'];
					$precio = $row['precio'];
					$f_precio = number_format($precio,4);
					$descripcion = $row['descripcion'];
					$id_cliente = $row['id_cliente'];
					$id_tamano = $row['tamano'];
					$id_tintas = $row['id_tintas'];
					$id_status = $row['id_status_prod'];
					$id_linea = $row['id_linea'];
					$id_unidad = $row['id_unidad'];
					$id_papel = $row['id_tipo_papel'];
					
					$id_laminado = $row['laminado'];
					switch ($id_laminado){
						case -1:
							$laminado = "No";
						break;
						case 1:
							$laminado = "Si";
						break;
					}
					
					$id_barnizado = $row['barnizado'];
					switch ($id_barnizado){
						case -1:
							$barnizado = "Ninguno";
						break;
						case 1:
							$barnizado = "UV";
						break;
						case 2:
							$barnizado = "Antiest&aacute;tico";
						break;
						case 3:
							$barnizado = "Base Agua";
						break;
					}
					
					$id_prensa = $row['prensa'];
					switch ($id_prensa){
						case -1:
							$prensa ="No hay información";
						break;
						case 1:
							$prensa ="Abdick";
						break;
						case 2:
							$prensa ="Forma Continua";
						break;
						case 3:
							$prensa ="Full Color";
						break;
					}
					
					$id_etiquetadora = $row['etiquetadora'];
					switch ($id_etiquetadora){
						case -1:
							$etiquetadora = "No";
						break;
						case 1:
							$etiquetadora = "Izquierdo";
						break;
						case 2:
							$etiquetadora = "Derecho";
						break;
					}
					
					//buscar la unidad
					$consulta_uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
					while ($row2 = $db->fetch_array($consulta_uni)){$unidad = $row2['unidad'];}
					
					//buscar el tamaño
					$consulta_tam = $db->consulta("SELECT *  FROM `tamano` WHERE `id_tamano` = '".$id_tamano."';");
					while ($row2 = $db->fetch_array($consulta_tam)){$tamano = $row2['tamano'];}
					
					//buscar el cliente
					$consulta_cli = $db->consulta("SELECT *  FROM `cliente` WHERE `id_cliente` = '".$id_cliente."';");
					while ($row2 = $db->fetch_array($consulta_cli)){$cliente = $row2['nombre'];}
					
					//buscar el status
					$consulta_sta = $db->consulta("SELECT *  FROM `status_producto` WHERE `id_status_producto` = '".$id_status."';");
					while ($row2 = $db->fetch_array($consulta_sta)){$status = $row2['status_producto'];}
					
					//buscar la linea
					$consulta_lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
					while ($row2 = $db->fetch_array($consulta_lin)){$linea = $row2['linea'];}
					
					//buscar el tipo de papel
					$consulta_pap = $db->consulta("SELECT *  FROM `tipo_papel` WHERE `id_tipo` = '".$id_papel."';");
					while ($row2 = $db->fetch_array($consulta_pap)){$papel = $row2['tipo'];}
					
					//buscar las tintas
					$consulta_tin = $db->consulta("SELECT *  FROM `tinta` WHERE `id_tinta` = '".$id_tintas."';");
					while ($row2 = $db->fetch_array($consulta_tin)){$tinta = $row2['tinta'];}
					//ahora a imprimir los datos de cada insumo
					 echo "<tr>
					 <td align=\"center\" valign=\"top\">".$numero."</td>
					 <td align=\"center\" valign=\"top\">".$cve."</td>
					 <td valign=\"top\">".$nombre."</td>
					 <td align=\"right\" valign=\"top\">$ ".$precio."</td>
					 <td valign=\"top\">".$descripcion."</td>	
					 <td align=\"left\" valign=\"top\">".$tamano."</td>
					 <td align=\"left\" valign=\"top\">".$tinta."</td>
					 <td align=\"left\" valign=\"top\">".$unidad."</td>
					 <td align=\"left\" valign=\"top\">".$prensa."</td>
					 <td align=\"left\" valign=\"top\">".$papel."</td>
					 <td align=\"left\" valign=\"top\">".$laminado."</td>
					 <td align=\"left\" valign=\"top\">".$barnizado."</td>
					 <td align=\"left\" valign=\"top\">".$etiquetadora."</td>
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
