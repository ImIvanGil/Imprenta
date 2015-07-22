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
			header("Content-disposition: attachment; filename=insumos.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="7"><h4>Sistema de Costos</h4></td></tr>
			<tr><td align="center" colspan="7"><b>Cat&aacute;logo de Insumos</b></td></tr>
            <tr><td align="center" colspan="7">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#881212">
			
			<tr valign="middle" align="center" bgcolor="#dfdfdf">
                <td><b>Clave</b></td>
                <td><b>No. de Parte</b></td>
                <td><b>Nombre</b></td>
                <td><b>Linea</b></td>
                <td><b>Descripcion</b></td>
                <td><b>Proveedores</b></td>
                <td><b>Presentaciones</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();

				$lista_insumos = $db->consulta("SELECT *  FROM `insumo` ORDER BY id_insumo;");
				while ($row = $db->fetch_array($lista_insumos))
					{
					$numero = $row['id_insumo'];
					$numparte = $row['num_parte'];
					$nombre = utf8_encode($row['nombre']);
					$descripcion = utf8_encode($row['descripcion']);
					
					$id_linea = $row['id_linea'];
						//consulto la linea del insumo
						$lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
						while ($row3 = $db->fetch_array($lin))
						{
							$linea = $row3['linea'];
						}
					
					
					//buscar a los proveedores
					$proveedores= "";
					$consulta_prov = $db->consulta("SELECT *  FROM `insumo_proveedor` WHERE `id_insumo` = '".$numero."';");
					while ($row2 = $db->fetch_array($consulta_prov))
					{
						$prov = $row2['id_proveedor'];
						//consultare para cada proveedor el nombre
						$lista_prov = $db->consulta("SELECT `nombre` FROM `proveedor` WHERE `id_proveedor`='".$prov."';");
						while ($row6 = $db->fetch_array($lista_prov))
						{
						  $nomProv = $row6['nombre'];
						  $nomProv = utf8_encode($nomProv);
						  $proveedores = $proveedores.$nomProv."<br>";
						}
					}
					
					
					//buscar las presentaciones
					$presentaciones= "";
					$consulta_pres = $db->consulta("SELECT *  FROM `insumo_presentacion` WHERE `id_insumo` = '".$numero."';");
					while ($row2 = $db->fetch_array($consulta_pres))
					{
						$pres = $row2['id_presentacion'];
						//consultare para cada presentacion el nombre
						$lista_pres = $db->consulta("SELECT `nombre` FROM `presentacion` WHERE `id_presentacion`='".$pres."';");
						while ($row6 = $db->fetch_array($lista_pres))
						{
						  $nomPres = $row6['nombre'];
						  $presentaciones = $presentaciones.$nomPres."<br>";
						}
					}
					
				
					
					//ahora a imprimir los datos de cada insumo
					 echo "<tr><td align=\"center\" valign=\"top\">".$numero."</td>
					 <td valign=\"top\">".$numparte."</td>
					 <td valign=\"top\">".$nombre."</td>
					 <td valign=\"top\">".$linea."</td>
					 <td valign=\"top\">".$descripcion."</td>	
					 <td align=\"left\" valign=\"top\">".$proveedores."</td>
					 <td align=\"left\" valign=\"top\">".$presentaciones."</td>
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
