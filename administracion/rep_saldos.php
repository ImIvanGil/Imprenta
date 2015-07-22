<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
include("../lib/pChart/pChart/pData.class");
include("../lib/pChart/pChart/pChart.class");
$db = new MySQL();

set_time_limit(0);

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
            <li><a href="admin.php">Administraci&oacute;n</a></li>
            <li><a href="reportes.php" class="current">Reportes</a></li>
           	<li><a href="#" onclick="javascript:document.forms['salir'].submit();">Salir</a></li>
         <?php
			echo '<form action="logout.php" method="POST" name="salir" id="salir">
			<input type="hidden" name="action" value="logout">';
			echo'</form>';
		?>    	
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
            <a href="<?php echo "rep_saldos_excel.php?inicio=$inicio&fin=$fin";?>">Exportar a MS Excel</a>
            
        </div>
       
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Reporte de cuentas por pagar</h2>
            
			<?php
				$finicio = cambiarFormatoFecha($inicio);
				$ffin = cambiarFormatoFecha($fin);
				
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
			?>
            <table cellspacing="1" width="100%" border="0px">
            
            <?php 
			//consulto a los proveedors con cargos en estado de cobro no liquidado 
			$cons_cli= "SELECT * FROM `proveedor` WHERE `id_proveedor` IN(select distinct(id_proveedor) from cargo where id_status_cobranza!=4 and `id_status_cargo`='1')";
			$cli = $db->consulta($cons_cli);
			while ($rowc = $db->fetch_array($cli))
			//se abre el while principal
			{
				 $id_proveedor = $rowc['id_proveedor'];
				 $nombre = $rowc['nombre'];
				 echo "<tr><td><h3>Proveedor: $nombre</h3>";
				 echo "<h5>Detalle</h5>";
				 ?>
                <table cellspacing="0" width="100%" border="1" cellpadding="3px">
                <thead>
                    <tr align="center" bordercolor="#dfdfdf" bgcolor="#E4EBA9">
                        <th class="header">Folio</th>
                        <th class="header">Fecha</th>
                        <th class="header">Vence</th>
                        <th class="header">Dias Vencidos</th>
                        <th class="header">Moneda</th>
                        <th class="header">Cobranza</th>
                        <th class="header">Subtotal</th>
                        <th class="header">IVA</th>
                        <th class="header">Total</th>
                        <th class="header">Abonos</th>
                        <th class="header">Adeudo</th>
                    </tr>
                </thead>
                <tbody>
				 <?php 
				 
				 //variables de cantidades
						$v1_30_mx = 0;
						$v1_30_us = 0;
						
						$v31_60_mx = 0;
						$v31_60_us = 0;
						
						$v61_mas_mx = 0;
						$v61_mas_us = 0;
						
						$ant_mx = 0;
						$ant_us = 0;
						$saldo_mx = 0;
						$saldo_us = 0;
				 //consulto las cargos del periodo para el proveedor
				$lista = $db->consulta("SELECT * FROM `cargo` WHERE id_proveedor = $id_proveedor and `id_status_cargo`='1' and id_status_cobranza!=4");
				while ($rowf = $db->fetch_array($lista))
							{
								$id_cargo = $rowf['id_cargo'];
								
								//fecha del cargo
								$solo_fecha = $rowf['fecha'];
								
								//plazo
								$plazo = $rowf['plazo_pago'];
								
								//obtengo la fecha de vencimiento
								$consVenc="SELECT DATE_ADD('".$solo_fecha."',INTERVAL ".$plazo." DAY) as vence";
								$lista_v = $db->consulta($consVenc);
								while($rowVence= $db->fetch_array($lista_v)){
									$vence=$rowVence['vence'];
								}
								
								//obtengo los dias para vencer
								$hoy = date("Y-m-d");
								$consD="SELECT DATEDIFF ('$vence','$hoy') AS dias";
								$lista_dias = $db->consulta($consD);
								while($rowD= $db->fetch_array($lista_dias)){
									$dias_vencer=$rowD['dias']+1;
								}
								
								
								$id_status_cob = $rowf['id_status_cobranza'];
								//buscar el status de cobro
								$consulta_sta_cob = $db->consulta("SELECT *  FROM `status_cobranza` WHERE `id_status_cobranza` = '".$id_status_cob."';");
								while ($row8 = $db->fetch_array($consulta_sta_cob)){
									$cobranza = $row8['status_cobranza'];
								}
								
								//consultare los abonos que se han hecho a la cargo
								  $cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_proveedor` WHERE `id_cargo` = '".$id_cargo."';");
								 while ($row2 = $db->fetch_array($cons_abonos)){
									 $abonos = $row2['suma'];
								 }
								
								
								
								$id_mon = $rowf['id_moneda'];
								//moneda
								$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
								$consulta_mon = $db->consulta($sql_mon);
								$row_mon = mysql_fetch_array($consulta_mon);
								$moneda = $row_mon['moneda'];
								
								$t_cambio = $rowf['tipo_cambio'];
								$t_cambio = number_format($t_cambio,2);
								
								//datos de cantidades
								$sub_total = $rowf['sub_total'];
								$iva = $rowf['impuestos'];
								$total_cargo = $sub_total+$iva;
								$adeudo = $total_cargo-$abonos;
								$saldo = $total_cargo-$abonos;
								
								
									//Sumo a los globales segun la moneda
									if($moneda=='MXN'){
										//acumulo los anticipos
										$ant_mx = $ant_mx+$abonos;
										//acumulo el saldo 
										$saldo_mx = $saldo_mx + $saldo;
										//ahora segun el tiempo para vencer acumulo los saldos por plazo
										if($dias_vencer<=30){
											$v1_30_mx = $v1_30_mx + $saldo;
										}else{
											if($dias_vencer<=60){
												$v31_60_mx = $v31_60_mx + $saldo;
											}else{
												$v61_mas_mx = $v61_mas_mx + $saldo; 
											}
										}
									}else{
										if($moneda=='USD'){
											//acumulo los anticipos
											$ant_us = $ant_us+$abonos;
											//acumulo el saldo 
											$saldo_us = $saldo_us + $saldo;
											//ahora segun el tiempo para vencer acumulo los saldos por plazo
											if($dias_vencer<=30){
												$v1_30_us = $v1_30_us + $saldo;
											}else{
												if($dias_vencer<=60){
													$v31_60_us = $v31_60_us + $saldo;
												}else{
													$v61_mas_us = $v61_mas_us + $saldo; 
												}
											}
											
										}
									}
										
									
								
								  echo "<tr align=\"center\" bgcolor=\"#ffffff\">
								 <td class=\"listado\">".$id_cargo."</td>
								 <td align=\"left\">".$solo_fecha."</td>
								 <td class=\"listado\">".$vence."</td>
								 <td class=\"listado\">".$dias_vencer."</td>
								 <td class=\"listado\">".$moneda."</td>
								 <td class=\"listado\">".$cobranza."</td>
								 <td class=\"listado\">$ ".number_format($sub_total,2)."</td>
								 <td class=\"listado\">$ ".number_format($iva,2)."</td>
								 <td class=\"listado\">$ ".number_format($total_cargo,2)."</td>
								 <td class=\"listado\">$ ".number_format($abonos,2)."</td>
								 <td class=\"listado\">$ ".number_format($adeudo,2)."</td>
								 ";
							}
							
						
						
					
					
				
				 echo "</tbody></table>";
				 echo "<br>";
				 
				 echo "<table cellspacing=\"1\" width=\"100%\" border=\"1px\" bgcolor=\"#FFFFFF\">";
				 echo "<tr><td colspan=\"3\"><b>ADEUDO MXN:</b> $ ".number_format($saldo_mx,2)."</td></tr>";
				 echo "<tr><td><b>30 d&iacute;as</b> $ ".number_format($v1_30_mx,2)."</td><td><b>60 d&iacute;as</b> $ ".number_format($v31_60_mx,2)."</td><td><b>m&aacute;s de 60 d&iacute;as</b> $ ".number_format($v61_mas_mx,2)."</td></tr>";
				 
				 echo "<tr><td colspan=\"3\"><b>ADEUDO USD:</b> $ ".number_format($saldo_us,2)."</td></tr>";
				 echo "<tr><td><b>30 d&iacute;as</b> $ ".number_format($v1_30_us,2)."</td><td><b>60 d&iacute;as</b> $ ".number_format($v31_60_us,2)."</td><td><b>m&aacute;s de 60 d&iacute;as</b> $ ".number_format($v61_mas_us,2)."</td></tr>";
				 
				 echo "</table>";
				 
				 echo "</tr></td>";
				 echo "<tr><td> <hr></tr></td>";
			}
			?>
            
            </table>
            
            

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