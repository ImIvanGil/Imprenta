<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
set_time_limit(0);
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
$db = new MySQL();

function cambiarFInicio($fecha){
    list($anio,$mes,$dia)=explode("-",$fecha);
    return $anio."-".$mes."-".$dia."T00:00:00";
} 

function cambiarFFin($fecha){
    list($anio,$mes,$dia)=explode("-",$fecha);
    return $anio."-".$mes."-".$dia."T24:59:59";
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
		$("#tablesorter-test").tablesorter({ widthFixed: true, widgets: ['zebra']});
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
            <a href="<?php echo "rep_vtas_linea_excel.php?inicio=$inicio&fin=$fin";?>">Exportar a MS Excel</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Reporte de facturacion por linea de producto</h2>
            
			<?php
				$finicio = cambiarFInicio($inicio);
				$ffin = cambiarFFin($fin);
				echo "<p>Del <b>$inicio</b> al <b>$fin</b></p>";
			
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
                
					<table id="tablesorter-test" class="tablesorter" cellspacing="0" width="100%">
					<thead>
						<tr align="center">
							<th class="header">Linea.</th>
							<th class="header">Ventas MXN</th>
                            <th class="header">Ventas USD</th>
                            <th class="header">T.C.</th>
                            <th class="header">Total MXN</th>
						</tr>
					</thead>
					<tbody>
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
							$texto_mx = "SELECT * FROM `detalle_factura` WHERE `id_producto` in(SELECT `id_producto` FROM `producto` WHERE `id_linea`='$clave') and `id_factura` in(SELECT `id_factura` FROM `factura` WHERE `fecha` BETWEEN '$finicio' AND '$ffin' and `id_status_factura`='2' and `id_moneda`='1')";
							//echo "el texto de MXN es: <br> $texto_mx<br>";
							$cons_mxn = ($texto_mx);
							$cons  = $db->consulta($cons_mxn);
							$suma_mxn = 0;
							while ($row_mxn = $db->fetch_array($cons)){
								$unitario = $row_mxn['unitario'];
								$cantidad = $row_mxn['cantidad'];
								$tot_mxn = $unitario * $cantidad;
								$suma_mxn = $suma_mxn + $tot_mxn;
							}
							
							
							//consulto las ventas de la linea en USD
							$texto_us = "SELECT * FROM `detalle_factura` WHERE `id_producto` in(SELECT `id_producto` FROM `producto` WHERE `id_linea`='$clave') and `id_factura` in(SELECT `id_factura` FROM `factura` WHERE `fecha` BETWEEN '$finicio' AND '$ffin' and `id_status_factura`='2' and `id_moneda`='2')";
							//echo "el texto de US es: <br> $texto_us<br>";
							$cons_usd = ($texto_us);
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
							 <td class=\"listado\">".$linea."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($suma_mxn,2)."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($suma_usd,2)."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($tc_usd,2)."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($total_mxn,2)."</td>
							 </tr>
							 ";
							 
							 
                        }
						
               		 echo "<tr>
							 <td class=\"listado\"><b>TOTAL</b></td>
							 <td class=\"listado\" align=\"right\"><b>$ ".number_format($final_mxn,2)."</b></td>
							 <td class=\"listado\" align=\"right\"><b>$ ".number_format($final_usd,2)."</b></td>
							 <td class=\"listado\" align=\"right\"><b>$ ".number_format($tc_usd,2)."</b></td>
							 <td class=\"listado\" align=\"right\"><b>$ ".number_format($final_total,2)."</b></td>
							 </tr>";
							 
					
                	echo "</tbody></table>";
					
                
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