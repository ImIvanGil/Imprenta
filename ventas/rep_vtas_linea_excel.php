<?php
ob_start();
set_time_limit(0);
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
	  header("Content-disposition: attachment; filename=ventas_linea.xls");
	  
	 function cambiarFInicio($fecha){
		list($anio,$mes,$dia)=explode("-",$fecha);
		return $anio."-".$mes."-".$dia."T00:00:00";
	} 
	
	function cambiarFFin($fecha){
		list($anio,$mes,$dia)=explode("-",$fecha);
		return $anio."-".$mes."-".$dia."T24:59:59";
	} 
	  
	  include("../lib/mysql.php");
				$db = new MySQL();

//recibo los datos de fechas
$inicio = $_GET['inicio'];
$ninicio = $inicio."T00:00:00";	
$fin = $_GET['fin'];	
$nfin = $fin."T24:59:59";
//obtengo cuantos dias hay entre las dos fechas
$consDias="SELECT DATEDIFF ('$fin','$inicio') AS dias";
$lista_d = $db->consulta($consDias);
while($rowDias= $db->fetch_array($lista_d)){
	$dias=$rowDias['dias']+1;
}

?>

            <h2>Reporte de facturacion por linea de producto</h2>
            
			<?php
				$finicio = cambiarFormatoFecha($inicio);
				$ffin = cambiarFormatoFecha($fin);
				echo "<p>Del <b>$finicio</b> al <b>$ffin</b></p>";
				
			
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
				
				//consulto las lineas de producto que hay
				$lista = $db->consulta("SELECT * FROM `linea_producto` order by `id_linea` ASC");
				
				$existe = $db->num_rows($lista);
                if($existe<=0){
                    echo "<p>No hay informaci&oacute;n de operaciones entre las fechas especificadas, verifique sus datos.</p>";
                }else{
				?>
                
					<table border="1px" border-color="#889b08">
						<tr valign="middle" align="center" bgcolor="#e3ef77">
							<td><b>Linea.</b></td>
							<td><b>Ventas MXN</b></td>
                            <td><b>Ventas USD</b></td>
                            <td><b>T.C.</b></td>
                            <td><b>Total MXN</b></td>
						</tr>
				<?php 
				//variables para totales
				$final_mxn = 0;
				$final_usd = 0;
				$final_total =0;
					
					while ($rowf = $db->fetch_array($lista))
                        {
							$clave = $rowf['id_linea'];
							$linea = $rowf['linea'];
							
							//consulto las ventas de la linea en MXN
							$cons_mxn = ("SELECT * FROM `detalle_factura` WHERE `id_producto` in(SELECT `id_producto` FROM `producto` WHERE `id_linea`='$clave') and `id_factura` in(SELECT `id_factura` FROM `factura` WHERE `id_moneda`='1' and `fecha` BETWEEN '$finicio' AND '$ffin' and `id_status_factura`='2')");
							$cons  = $db->consulta($cons_mxn);
							$suma_mxn = 0;
							while ($row_mxn = $db->fetch_array($cons)){
								$unitario = $row_mxn['unitario'];
								$cantidad = $row_mxn['cantidad'];
								$tot_mxn = $unitario * $cantidad;
								$suma_mxn = $suma_mxn + $tot_mxn;
							}
							
							
							//consulto las ventas de la linea en USD
							$cons_usd = ("SELECT * FROM `detalle_factura` WHERE `id_producto` in(SELECT `id_producto` FROM `producto` WHERE `id_linea`='$clave') and `id_factura` in(SELECT `id_factura` FROM `factura` WHERE `id_moneda`='2' and `fecha` BETWEEN '$finicio' AND '$ffin' and `id_status_factura`='2')");
							$cons  = $db->consulta($cons_usd);
							$suma_usd = 0;
							while ($row_usd = $db->fetch_array($cons)){
								$unitario = $row_usd['unitario'];
								$cantidad = $row_usd['cantidad'];
								$tot_usd = $unitario * $cantidad;
								$suma_usd = $suma_usd + $tot_usd;
							}
							
							//convierto los dolares a pesos
							$conversion = $suma_usd * $tc_usd;
							$total_mxn = $suma_mxn + $conversion;
							
							//acumulo las variables totales
							$final_mxn = $final_mxn+$suma_mxn;
							$final_usd = $final_usd + $suma_usd;
							$final_total = $final_total + $total_mxn;
							
							 echo "<tr>
							 <td>".$linea."</td>
							 <td align=\"right\">$ ".number_format($suma_mxn,2)."</td>
							 <td align=\"right\">$ ".number_format($suma_usd,2)."</td>
							 <td align=\"right\">$ ".number_format($tc_usd,2)."</td>
							 <td align=\"right\">$ ".number_format($total_mxn,2)."</td>
							 </tr>
							 ";
							 
							 
                        }
						
               		 echo "<tr>
							 <td><b>TOTAL</b></td>
							 <td align=\"right\"><b>$ ".number_format($final_mxn,2)."</b></td>
							 <td align=\"right\"><b>$ ".number_format($final_usd,2)."</b></td>
							 <td align=\"right\"><b>$ ".number_format($tc_usd,2)."</b></td>
							 <td align=\"right\"><b>$ ".number_format($final_total,2)."</b></td>
							 </tr>";
							 
					
                	echo "</table>";
					
                
				}
	
}
?>