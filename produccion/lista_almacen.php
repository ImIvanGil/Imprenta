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
			header("Content-disposition: attachment; filename=existencias_producto_terminado.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="7"><h4>ERP Imprenta</h4></td></tr>
			<tr><td align="center" colspan="7"><b>Almac&eacute;n de Producto Terminado</b></td></tr>
            <tr><td align="center" colspan="7">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#881212">
			
			<tr valign="middle" align="center" bgcolor="#dfdfdf">
                <td><b>Clave</b></td>
                <td><b>No. de Parte</b></td>
                <td><b>Nombre</b></td>
                <td><b>Descripcion</b></td>
                <td><b>Existencia</b></td>
                <td><b>Unitario</b></td>
                <td><b>Total</b></td>
                <td><b>Inventario Fisico</b></td>
                <td><b>Diferencia</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();
				$i =1;
				$lista_productos = $db->consulta("SELECT *  FROM `producto`;");
				while ($row2 = $db->fetch_array($lista_productos))
					{
							
							
							$clave = $row2['id_producto'];
							$noparte = $row2['clave'];
							$nombre = utf8_encode($row2['nombre']);
							$descripcion = utf8_encode($row2['descripcion']);
							
									
							//consulto la existencia por producto
							$existencia =0;
							$saldo = 0;
							$cons8 ="SELECT * FROM `producto_almacen` where `id_producto`=$clave;";
							$cons4= $db->consulta($cons8);
							while ($row2_f = $db->fetch_array($cons4))
							{
								$tipo_mov_f  = $row2_f['id_tipo_movimiento']; 
								$cantidad_f = $row2_f['cantidad'];
								$unitario = $row2_f['unitario'];
								$total_mov = $cantidad_f * $unitario;
								
								 if($tipo_mov_f==1 || $tipo_mov_f==3){
									  $existencia = $existencia + $cantidad_f;
									  $saldo = $saldo + $total_mov;
								 }else{
									  $existencia = $existencia-$cantidad_f;
									  $saldo = $saldo - $total_mov;
								 }								
							}
							
							//calculo el promedio
							if($existencia==0){
								$promedio=0;
							}else{
								$promedio = $saldo/$existencia;
							}
						
						$promedio = number_format($promedio,2);
						$saldo = number_format($saldo,2);
						
					
						//ahora a imprimir los datos de cada producto
						 echo "<tr>
							 <td align=\"center\" valign=\"top\">".$i."</td>
							 <td valign=\"top\" align=\"left\" >".$noparte."</td>
							 <td valign=\"top\" align=\"left\" >".$nombre."</td>
							 <td valign=\"top\" align=\"left\" >".$descripcion."</td>	
							 <td align=\"right\" valign=\"top\">".$existencia."</td>
							 <td align=\"right\" valign=\"top\">$ ".$promedio."</td>
							 <td align=\"right\" valign=\"top\">$ ".$saldo."</td>
							 <td>&nbsp;</td>
							 <td>&nbsp;</td>
						 </tr>";
					 
						$i++;
					}
			?>
			
			
			</table>
			
</body>
</html>
<?php
}

ob_end_flush();
?> 
