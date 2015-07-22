<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
set_time_limit(0);
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
$db = new MySQL();

function cambiarFormatoFecha($fecha){
    list($anio,$mes,$dia)=explode("-",$fecha);
    return $dia."-".$mes."-".$anio;
} 
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra']});
		} 
	);
</script>

<?php
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



</head>
<body>

<div id="templatemo_wrapper">

	<div id="templatemo_header">

    	<div id="site_title">
            <h1><a href="#">Sistema de ERP<span><?php echo 'Bienvenido, <b> '.$curUser.' </b>'; ?></span></a></h1>
        </div> <!-- end of site_title -->
        
        <div class="cleaner"></div>
    </div> <!-- end of templatemo_header -->
    
    <div id="templatemo_menu">
        <ul>
            <li><a href="../inicio.php">Inicio</a></li>
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="reportes.php" class="current">Reportes</a></li>
           	<li><a href="#" onclick="javascript:document.forms['salir'].submit();">Salir</a></li>
         <?php
			echo '<form action="logout.php" method="POST" name="salir" id="salir">
			<input type="hidden" name="action" value="logout">';
			echo'</form>';
		?>
        </ul>     	
    </div> <!-- end of templatemo_menu -->

    <div id="templatemo_banner_wrapper">
    
    <div id="templatemo_banner_thin"> 
    
    	
    
    	<div class="cleaner"></div>
        
    </div> <!-- end of banner -->
    
    </div>	<!-- end of banner_wrapper -->
    
    <div id="templatemo_service_bar_wrapper">
    
    <div id="templatemo_service_bar">
    
    	<div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_29.png" alt="image 3" />
            <a href="reportes.php">Regresar</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/logo_excel.png" alt="image 1" />
            <a href="<?php echo "rep_gen_vtas_excel.php?inicio=$inicio&fin=$fin";?>">Exportar a MS Excel</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Reporte de general de ventas</h2>
            
			<?php
				$finicio = cambiarFormatoFecha($inicio);
				$ffin = cambiarFormatoFecha($fin);
				//echo "<p>Del <b>$finicio</b> al <b>$ffin</b></p>";
				//consulto los pagos recibidos en el periodo en pesos mexicanos
				$cons_pagos_mx = "SELECT sum(`monto`) as suma FROM `pago_cliente` WHERE `id_moneda`='1' AND `fecha` BETWEEN '$inicio' AND '$fin'";
				$pagos_mx = $db->consulta($cons_pagos_mx);
				while ($rowp = $db->fetch_array($pagos_mx))
				{
					$sum_pagos_mx = $rowp['suma'];
				}
				
				//consulto los pagos recibidos en el en dolares
				$cons_pagos_us = "SELECT sum(`monto`) as suma FROM `pago_cliente` WHERE `id_moneda`='2' AND `fecha` BETWEEN '$inicio' AND '$fin'";
				$pagos_us = $db->consulta($cons_pagos_us);
				while ($rowp = $db->fetch_array($pagos_us))
				{
					$sum_pagos_us = $rowp['suma'];
				}
				
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
				
				//consulto las facturas del periodo
				$texto = "SELECT * FROM `factura` WHERE fecha BETWEEN '$ninicio' AND '$nfin' and `id_status_factura`='2'";
				$lista = $db->consulta($texto);
				//$lista = $db->consulta("SELECT * FROM `factura` WHERE fecha BETWEEN '$ninicio' AND '$nfin' and `id_status_factura`!='1'");
				echo "la cosnulta es $texto <br>";
				$existe = $db->num_rows($lista);
                if($existe<=0){
                    echo "<p>No hay informaci&oacute;n de operaciones entre las fechas especificadas, verifique sus datos.</p>";
                }else{
				?>
                
					<table id="tablesorter-test" class="tablesorter" cellspacing="0" width="100%">
					<thead>
						<tr align="center">
							<th class="header">No.</th>
							<th class="header">Cliente</th>
                            <th class="header">RFC</th>
                            <th class="header">Vendedor</th>
							<th class="header">Fecha</th>
                            <th class="header">Plazo</th>
                            <th class="header">Vence</th>
							<th class="header">Estado</th>
							<th class="header">Tipo de Pago</th>
                            <th class="header">Moneda</th>
                            <th class="header">T.C.</th>
                            <th class="header">Cobranza</th>
                            <th class="header" width="12%">Subtotal</th>
                            <th class="header" width="12%">IVA</th>
                            <th class="header" width="12%">Total</th>
                            <th class="header" width="12%">Total M.N.</th>
						</tr>
					</thead>
					<tbody>
				<?php 
					//variables globales de cantidades
					$glo_sub = 0;
					$glo_iva = 0;
					$glo_tot = 0;
					
					$glo_sub_mx = 0;
					$glo_iva_mx = 0;
					$glo_tot_mx = 0;
					
					$glo_sub_glo = 0;
					$glo_iva_glo = 0;
					$glo_tot_glo = 0;
					
					$glo_act = 0;
					$glo_canc = 0;
					$glo_cert = 0;
					
					$glo_liq = 0;
					$glo_pcob =0;
					$glo_parc = 0;
					
					$cobrar_mx = 0;
					$cobrar_usd = 0;
					
				
					while ($rowf = $db->fetch_array($lista))
                        {
							$clave = $rowf['id_factura'];
							$folio = $rowf['folio'];
							$id_serie = $rowf['id_serie'];
							
							//consultare si la serie tiene alguna letra
							$consulta_ser = $db->consulta("SELECT letra  FROM `series` WHERE `id_serie` = '".$id_serie."';");
							while ($row2 = $db->fetch_array($consulta_ser)){
								$serie = $row2['letra'];
							}
							$interno = $folio." ".$serie;
							
							//voy a separar la fecha de la hora
							$fecha = $rowf['fecha'];
							$fechas = explode("T", $fecha);
							$solo_fecha = $fechas[0];
							
							//plazo de cobro
							$plazo = $rowf['plazo_pago'];
							
							//fecha de vencimiento
							$cons_vence ="SELECT DATE_ADD('$solo_fecha',INTERVAL $plazo DAY) AS vence";
							$consulta_venc = $db->consulta($cons_vence);
							$row_venc = mysql_fetch_array($consulta_venc);
							$vencimiento = $row_venc['vence'];
							
								
							
							$id_cli= $rowf['id_cliente'];
							
							$sql_cli = "SELECT * FROM cliente where `id_cliente`='".$id_cli."'";
							$consulta_cli = $db->consulta($sql_cli);
							while($row_cli = mysql_fetch_array($consulta_cli)){
								$nom_cliente=$row_cli['nombre'];
								$rfc=$row_cli['rfc'];
								
								$id_vendedor = $row_cli['id_vendedor'];
								$consulta_vende = $db->consulta("SELECT nombre  FROM `empleado` WHERE `id_empleado` = '".$id_vendedor."';");
								while ($row2 = $db->fetch_array($consulta_vende))
								{
									$vendedor = $row2['nombre'];
								}
							}
							
							
							
							$id_status = $rowf['id_status_factura'];
							if($id_status==1){
								$glo_act = $glo_act +1;
							}else{
								if($id_status==2){
									$glo_cert = $glo_cert+1;
								}else{
									if($id_status==3){
										$glo_canc = $glo_canc +1;
									}
								}
							}
							//buscar el status
							$consulta_sta = $db->consulta("SELECT *  FROM `status_factura` WHERE `id_status_factura` = '".$id_status."';");
							while ($row3 = $db->fetch_array($consulta_sta)){
								$status = $row3['status'];
							}
							
							$id_status_cob = $rowf['id_status_cobranza'];
							if($id_status_cob==2){
								$glo_pcob = $glo_pcob +1;
							}else{
								if($id_status_cob==3){
									$glo_parc = $glo_parc+1;
								}else{
									if($id_status_cob==4){
										$glo_liq = $glo_liq +1;
									}
								}
							}
							//buscar el status de cobro
							$consulta_sta_cob = $db->consulta("SELECT *  FROM `status_cobranza` WHERE `id_status_cobranza` = '".$id_status_cob."';");
							while ($row8 = $db->fetch_array($consulta_sta_cob)){
								$cobranza = $row8['status_cobranza'];
							}
							
							//consultare los abonos que se han hecho a la factura
							 $cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_factura` = '".$clave."';");
						   while ($row2 = $db->fetch_array($cons_abonos)){
							   $abonos = $row2['suma'];
						   }
					  
							
							
							$id_forma = $rowf['id_forma_pago'];
							//forma de pago
							$sql_condicion = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
							$consulta_condicion = $db->consulta($sql_condicion);
							$row_condicion = mysql_fetch_array($consulta_condicion);
							$condicion = $row_condicion['forma_pago'];
							
							$id_mon = $rowf['id_moneda'];
							//moneda
							$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
							$consulta_mon = $db->consulta($sql_mon);
							$row_mon = mysql_fetch_array($consulta_mon);
							$moneda = $row_mon['moneda'];
							
							$t_cambio = $rowf['tipo_cambio'];
							$t_cambio = number_format($t_cambio,2);
							
							$id_iva = $rowf['id_iva'];
							//voy a buscar la tasa del iva que vamos a usar
							$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
							$consulta_tas = $db->consulta($sql_tas);
							$row_tas = mysql_fetch_array($consulta_tas);
							$tasa_iva = $row_tas['tasa'];
							
							//datos de cantidades
							$sub_total = 0;
							$graba_normal = 0;
							$graba_cero = 0;
							$exento = 0;
							$iva = 0;
							$total_factura = 0;
							
							$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$clave."'");
							$existe2 = $db->num_rows($detalle);
							if($existe2<=0){
								//no pasa nada
								$total_mn = 0;
							}else{
								
								while ($row = $db->fetch_array($detalle))
								{
									$id_detalle = $row['id_detalle_fact'];
									$cantidad = $row['cantidad'];
									$unitario = $row['unitario'];
									$clase_iva = $row['id_clase_iva'];
									
									//consultare el nombre del tipo de iva
									$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
									 while ($row2 = $db->fetch_array($consulta_cl)){
										 $nom_clase = $row2['clase_iva'];
									 }
		
									$precio = $unitario * $cantidad;
									$sub_total = $sub_total + $precio;
									
									//voy a acumular las cantidades para calcular el iva segun como sea la clase
									switch($clase_iva){
										case 1:
											$graba_normal = $graba_normal+$precio;
										break;
										case 2:
											$graba_cero = $graba_cero+$precio;
										break;
										case 3:
											$exento = $exento + $precio;
										break;
									}
									
									//calculo de los valores totalizados
									$iva = $graba_normal * ($tasa_iva/100);
									$total_factura = $sub_total + $iva;
									$saldo = $total_factura - $abonos;
									if($total_factura <=0){
										$total_mn = 0;
									}else{
										$total_mn = $total_factura * $t_cambio;
									}
									
									
									
								}
								//Sumo a los globales segun la moneda
								if($moneda=='MXN'){
									$glo_sub_mx = $glo_sub_mx + $sub_total;
									$glo_iva_mx = $glo_iva_mx + $iva;
									$glo_tot_mx = $glo_tot_mx + $total_factura;
									$cobrar_mx = $cobrar_mx + $saldo;
									
									//acumulo a los totales globales
									$glo_sub_glo = $glo_sub_glo + $sub_total;
									$glo_iva_glo = $glo_iva_glo + $iva;
									$glo_tot_glo = $glo_tot_glo + $total_factura;
								}else{
									if($moneda=='USD'){
										$glo_sub = $glo_sub + $sub_total;
										$glo_iva = $glo_iva + $iva;
										$glo_tot = $glo_tot + $total_factura;
										$cobrar_usd = $cobrar_usd + $saldo;
										
										//convierto a pesos segun el tipo de cambio
										$c1 = $sub_total * $t_cambio;
										$c2 = $iva * $t_cambio;
										$c3 = $total_factura * $t_cambio;
										
										//acumulo a los totales globales
										$glo_sub_glo = $glo_sub_glo + $c1;
										$glo_iva_glo = $glo_iva_glo + $c2;
										$glo_tot_glo = $glo_tot_glo + $c3;
										
									}
								}
									
								
							}
					
					
							
							 echo "<tr align=\"center\">
							 <td class=\"listado\">".$clave."</td>
							 <td align=\"left\">".utf8_decode($nom_cliente)."</td>
							 <td align=\"left\">".utf8_decode($rfc)."</td>
							 <td align=\"left\">".utf8_decode($vendedor)."</td>
							 <td class=\"listado\">".$solo_fecha."</td>
							 <td class=\"listado\">".$plazo." d&iacute;as</td>
							 <td class=\"listado\">".$vencimiento."</td>
							 <td class=\"listado\">".$status."</td>
							 <td class=\"listado\">".$condicion."</td>
							 <td class=\"listado\">".$moneda."</td>
							 <td class=\"listado\">".$t_cambio."</td>
							 <td class=\"listado\">".$cobranza."</td>
							 <td class=\"listado\">$ ".number_format($sub_total,2)."</td>
							 <td class=\"listado\">$ ".number_format($iva,2)."</td>
							 <td class=\"listado\">$ ".number_format($total_factura,2)."</td>
							 <td class=\"listado\">$ ".number_format($total_mn,2)."</td>
							 ";
							 
							 
                        }
						
               
                	echo "</tbody></table>";
					
					$vtas_prom = $glo_tot_glo/$dias;
					$cobrar_usd_conv = $cobrar_usd*$tc_usd;
					$cobrar_total = $cobrar_mx+$cobrar_usd_conv;
					$pagos_us_conv = $sum_pagos_us * $tc_usd;
					$pagos_total = $sum_pagos_mx + $pagos_us_conv;
					echo "<table width=\"100%\" border=\"0\">
					
					<tr>
						<td align=\"right\"><b>Subtotal Global: $</b></td><td><b>".number_format($glo_sub_glo,2)."</b></td>
						<td align=\"right\"><b>I.V.A. Global: $</b></td><td><b>".number_format($glo_iva_glo,2)."</b></td>
						<td align=\"right\"><b>Total Global: $</b></td><td><b>".number_format($glo_tot_glo,2)."</b></td>
					</tr>
					<tr>
						<td align=\"right\">Subtotal MXN: $</td><td>".number_format($glo_sub_mx,2)."</td>
						<td align=\"right\">I.V.A. MXN: $</td><td> ".number_format($glo_iva_mx,2)."</td>
						<td align=\"right\">Total MXN: $</td><td> ".number_format($glo_tot_mx,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Subtotal USD: $</td><td>".number_format($glo_sub,2)."</td>
						<td align=\"right\">I.V.A. USD: $</td><td> ".number_format($glo_iva,2)."</td>
						<td align=\"right\">Total USD: $</td><td> ".number_format($glo_tot,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Tipo de Cambio: </td><td>".number_format($tc_usd,2)."</td>
						<td align=\"right\">Dias del periodo: </td><td>".$dias."</td>
						<td align=\"right\">Ventas diarias promedio: $</td><td> ".number_format($vtas_prom,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Cuentas por cobrar en MXN: $</td><td>".number_format($cobrar_mx,2)."</td>
						<td align=\"right\">Cuentas por cobrar en USD: $</td><td> ".number_format($cobrar_usd,2)."</td>
						<td align=\"right\">Total por cobrar: $</td><td> ".number_format($cobrar_total,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Pagos recibidos en MXN $:</td><td>".number_format($sum_pagos_mx,2)."</td>
						<td align=\"right\">Pagos recibidos en USD $:</td><td>".number_format($sum_pagos_us,2)."</td>
						<td align=\"right\">Total pagos recibidos $:</td><td>".number_format($pagos_total,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Facturas activas:</td><td> $glo_act</td>
						<td align=\"right\">Facturas certificadas:</td><td> $glo_cert</td>
						<td align=\"right\">Facturas canceladas:</td><td> $glo_canc</td>
					</tr>
					<tr>
						<td align=\"right\">Facturas liquidadas:</td><td> $glo_liq</td>
						<td align=\"right\">Facturas con pago parcial:</td><td> $glo_parc</td>
						<td align=\"right\">Facturas por cobrar:</td><td> $glo_pcob</td>
					</tr>
					<tr>
						<td align=\"left\" colspan=\"6\"><span>*</span>Datos globales de cobranza en base a tipo de cambio actual, datos de ventas en base a tipo de cambio al momento de facturar</td>
					</tr>
					</table>";
                
				}
	
			
                
			?>
            

       	  <div class="cleaner_h20"></div>
            
            
		</div>
    	<div class="cleaner"></div>
    </div>
    </div>

</div> <!-- end of templatemo_wrapper -->

<div id="templatemo_footer_wrapper">
	<div id="templatemo_footer">

       	<a href="#">C&eacute;nit Consultores</a>
    
    </div> <!-- end of templatemo_footer -->
</div> <!-- end of templatemo_footer_wrapper -->

</body>
</html>
<?php
}
?>