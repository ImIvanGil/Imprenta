<?php
ob_start();
include("../adminuser/adminpro_class.php");
include("../lib/mysql.php");
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
header("Content-disposition: attachment; filename=existencias.xls");
	
$clave = $_GET['numero'];
$id_pres = $_GET['presentacion'];

$db = new MySQL();

?> 

         <h2>Ficha de Datos de Insumo</h2>

			<?php
                
				//DATOS DE LA PRESENTACION
				$datos_pres = $db->consulta("SELECT * FROM `presentacion` WHERE `id_presentacion`=$id_pres;");
				while ($row3 = $db->fetch_array($datos_pres))
				{
					$nombre_pres = utf8_encode($row3['nombre']);
					
				}
				//DATOS DEL INSUMO
                $insumo = $db->consulta("SELECT * FROM `insumo` WHERE id_insumo='".$clave."'");
                $existe = $db->num_rows($insumo);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del insumo con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($insumo))
                {
					$nombre = utf8_encode($row['nombre']);
					$descripcion = utf8_encode($row['descripcion']);
			?>
            
            <table align="center" border="0" width="100%">
            	
                <tr><td align="center">
                  <!-- Recuadro con los datos generales -->
                     <h3>Datos Generales </h3>
                    <table border="1" width="700px" align="center">	
                        <tr>
                            <td align="left"><b>Nombre:</b>
							<?php echo $nombre; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Descripci&oacute;n:</b>
							<?php echo $descripcion; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Presentaci&oacute;n:</b>
							<?php echo $nombre_pres; ?></td>
                        </tr>
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="center">
                  <!-- Recuadro con los datos de presentaciones del insumo -->
                  <h3>Ficha de Inventario</h3>
                    <table border="1" width="100%" align="center">	
                       
                        <tr>
                            <td align="left">
								<?php 
                                    $movimientos = $db->consulta("SELECT * FROM `insumo_inventario` WHERE id_insumo='".$clave."' AND id_presentacion='".$id_pres."' ORDER BY `fecha`,`id_movimiento` ASC");
                                    $existe = $db->num_rows($movimientos);
                                    if($existe<=0){
                                        echo "No se han registrado movimientos de inventario para &eacute;ste insumo";
									
                                    
                                    }else{
									?>
										<table id="tablesorter-pres" class="tablesorter" cellspacing="0" border="1">
                                        <thead>
                                            <tr align="center">
                                                <th class="header" colspan="3">&nbsp;</th>
                                                <th class="header" colspan="3">U N I D A D E S</th>
                                                <th class="header" colspan="2">C O S T O</th>
                                                <th class="header" colspan="3">V A L O R E S</th>
                                            </tr>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Fecha</th>
                                                <th class="header">Descripcion</th>
                                                <th class="header">Entrada</th>
                                                <th class="header">Salida</th>
                                                <th class="header">Existencia</th>
                                                <th class="header">Unitario</th>
                                                <th class="header">Promedio</th>
                                                <th class="header">Debe</th>
                                                <th class="header">Haber</th>
                                                <th class="header">Saldo</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$i = 1;
										$existencia = 0;
										$saldo = 0;
										while ($row3 = $db->fetch_array($movimientos))
										{
											$key = $row3['id_movimiento'];
											$fecha = $row3['fecha'];
											$descripcion = $row3['descripcion'];
											$tipo_mov = $row3['id_tipo_movimiento'];
											$unidades = $row3['unidades'];
											$unitario = $row3['unitario'];
											$total_mov = $unidades * $unitario;
											
											//ahora imprimire la tabla
											
												//si es entrada
												 echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
												 <td align=\"center\" align=\"center\">".$fecha."</td>
												 <td align=\"left\">".$descripcion."</td>";
												 //reviso si es entrada o salida
												 if($tipo_mov==1 || $tipo_mov==3){
													  echo "<td align=\"center\">".$unidades."</td><td>&nbsp;</td>";
													  $existencia = $existencia + $unidades;
													  $saldo = $saldo + $total_mov;
												 }else{
													  echo "<td>&nbsp;</td><td align=\"center\">".$unidades."</td>";
													  $existencia = $existencia - $unidades;
													  $saldo = $saldo - $total_mov;
												 }
												 
												 echo "<td align=\"center\">".$existencia."</td>";
												 echo "<td class=\"listado\" align=\"right\">$ ".number_format($unitario,2)."</td>";
												 //calculo el promedio
												$promedio = $saldo/$existencia;
												echo "<td class=\"listado\" align=\"right\">$ ".number_format($promedio,2)."</td>";
												
												//vuelvo a hacer la revision para imprimir los valores
												 if($tipo_mov==1 || $tipo_mov==3){
													  echo "<td align=\"center\">".number_format($total_mov,2)."</td><td>&nbsp;</td>";
												 }else{
													  echo "<td>&nbsp;</td><td align=\"center\">".number_format($total_mov,2)."</td>";
												 }
												 
												 echo "<td class=\"listado\" align=\"right\">$ ".number_format($saldo,2)."</td>";
											
											$i++;
										}
										echo"</tbody></table>";
									}
                                ?>
                            		
                            
                            </td>
                        </tr>
                    </table>
                </td></tr>
                
                
            </table>  
<?php
		}
            
	}

}
?>