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
			header("Content-disposition: attachment; filename=existencias.xls");
		
			
?>
<html>
<head>
<title>
</title>
</head>
<body>			
			<table>
			<tr><td align="center" colspan="7"><h4>ERP Imprenta</h4></td></tr>
			<tr><td align="center" colspan="7"><b>Lista de Existencias</b></td></tr>
            <tr><td align="center" colspan="7">&nbsp;</td></tr>
			</table>
			
			<table border="1px" border-color="#881212">
			
			<tr valign="middle" align="center" bgcolor="#dfdfdf">
                <td><b>Clave</b></td>
                <td><b>No. de Parte</b></td>
                <td><b>Nombre</b></td>
                <td><b>Descripcion</b></td>
                <td><b>Clase</b></td>
                <td><b>Existencia</b></td>
                <td><b>Max</b></td>
                <td><b>Min</b></td>
                <td><b>Cant. a Ordenar</b></td>
                <td><b>Relaci&oacute;n</b></td>
                <td><b>Unitario</b></td>
                <td><b>Total</b></td>
                <td><b>Moneda</b></td>
                <td><b>Inventario Fisico</b></td>
                <td><b>Diferencia</b></td>
            </tr>
			
			<?php 
				include("../lib/mysql.php");
				$db = new MySQL();
				$i =1;
				$lista_insumos = $db->consulta("SELECT *  FROM `insumo` ORDER BY id_clase;");
				while ($row2 = $db->fetch_array($lista_insumos))
					{
							
							
							$clave = $row2['id_insumo'];
							$noparte = $row2['num_parte'];
							$nombre = utf8_encode($row2['nombre']);
							$descripcion = utf8_encode($row2['descripcion']);
							$max = $row2['maximo'];
							$min = $row2['minimo'];
							
							$id_moneda = $row2['id_moneda'];
							
							if($id_moneda==''){
								$moneda = 'Sin Informacion';
							}else{
								
								//consulto la moneda
								$mon = $db->consulta("SELECT *  FROM `moneda` WHERE `id_moneda` = '".$id_moneda."';");
								while ($row3 = $db->fetch_array($mon))
								{
									$moneda = $row3['moneda'];
								}
							
							}
							
							
							$id_clase = $row2['id_clase'];
							
							if($id_clase==''){
								$clase = 'Sin Informacion';
							}else{
								
								//consulto la clase
								$cla = $db->consulta("SELECT *  FROM `clase_insumo` WHERE `id_clase` = '".$id_clase."';");
								while ($row3 = $db->fetch_array($cla))
								{
									$clase = $row3['clase'];
								}
							
							}
									
							//consulto la existencia por insumo
							$existencia =0;
							$saldo = 0;
							$cons8 ="SELECT * FROM `insumo_inventario` where `id_insumo`=$clave;";
							$cons4= $db->consulta($cons8);
							while ($row2_f = $db->fetch_array($cons4))
							{
								$tipo_mov_f  = $row2_f['id_tipo_movimiento']; 
								$cantidad_f = $row2_f['unidades'];
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
						$ordenar = $max - $existencia;
						$ordenar = number_format($ordenar);
					
						//ahora a imprimir los datos de cada insumo
						 echo "<tr>
							 <td align=\"center\" valign=\"top\">".$i."</td>
							 <td valign=\"top\" align=\"left\" >".$noparte."</td>
							 <td valign=\"top\" align=\"left\" >".$nombre."</td>
							 <td valign=\"top\" align=\"center\" >".$descripcion."</td>	
							 <td valign=\"top\" align=\"center\" >".$clase."</td>	
							 <td align=\"right\" valign=\"top\">".$existencia."</td>
							 <td align=\"right\" valign=\"top\">".$max."</td>
							 <td align=\"right\" valign=\"top\">".$min."</td>
							 <td align=\"right\" valign=\"top\">".$ordenar."</td>";
							 //<td align=\"right\" valign=\"top\">".$existencia."</td>
							 
							 //voy a imprimir un indicador de la existencia respecto al maximo y minimo
								 if($min==0||$max==0){
									 //si estan en blanco los maximos o minimos, entonces estara en blanco
									 echo "<td align=\"center\" bgcolor=\"#999999\">Sin Información</td>";
								}else{
									 if($existencia-$min<=3&&$existencia-$min>0){
										 //aqui va amarillo se acerca al punto de reorden
										 echo "<td align=\"center\" bgcolor=\"#FFFF99\">Nivel Bajo</td>";
									}else{
										if($existencia-$min<=0){
											//aqui va rojo estamos por debajo del minimo o ya no hay
											echo "<td align=\"center\" bgcolor=\"#FF3300\">Ordenar</td>";
										}else{
											if($max-$existencia<=3&&$max-$existencia>0){
												//aqui va azul por que se acerca al maximo
												echo "<td align=\"center\" bgcolor=\"#6699CC\">Cerca del M&aacute;ximo</td>";
											}else{
												if($max-$existencia<=0){
													//aqui va morado, estamos sobrepasando el inventario
													echo "<td align=\"center\" bgcolor=\"#6633CC\">Sobreinventario</td>";
												}else{
													//por ultimo si no entra en ninguno de los anteriores aqui va verde
													echo "<td align=\"center\" bgcolor=\"#66CC66\">Nivel normal</td>";
												}
											}
										}
									}
								}
							 echo " <td align=\"right\" valign=\"top\">$ ".$promedio."</td>
							 <td align=\"right\" valign=\"top\">$ ".$saldo."</td>
							 <td align=\"center\" valign=\"top\">".$moneda."</td>
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
