<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
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
//recibo los datos
$id_producto = $_GET['id_prod'];
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
            <a href="<?php echo "rep_vtas_prod_excel.php?inicio=$inicio&fin=$fin&id_prod=$id_producto";?>">Exportar a MS Excel</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Reporte de ventas por Producto</h2>
            
			<?php
				//consulto los datos del producto
				$producto = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$id_producto."'");
				while ($row = $db->fetch_array($producto))
                {
					$nombre_prod = utf8_decode($row['nombre']);
				}
				
				//consulto el tipo de cambio de pesos contra dolares registrados
				$cons_tc = "SELECT `precio_compra` FROM `moneda` WHERE `moneda`='USD'";
				$tc = $db->consulta($cons_tc);
				while ($rowt = $db->fetch_array($tc))
				{
					$tc_usd = $rowt['precio_compra'];
				}
				
				$finicio = cambiarFormatoFecha($inicio);
				$ffin = cambiarFormatoFecha($fin);
				echo "<p>Para el producto <b>$nombre_prod</b> del <b>$finicio</b> al <b>$ffin</b></p>";
				
				
				//consulto los detalles de factura que contienen el producto en el periodo
				$lista = $db->consulta("SELECT * FROM `detalle_factura` WHERE `id_producto`='$id_producto' AND `id_factura` IN (SELECT `id_factura` FROM `factura` WHERE `fecha` BETWEEN '$ninicio' AND '$nfin' and `id_status_factura`='2')");
				
				$existe = $db->num_rows($lista);
                if($existe<=0){
                    echo "<p>No hay informaci&oacute;n de operaciones entre las fechas especificadas, verifique sus datos.</p>";
                }else{
				?>
                
					<table id="tablesorter-test" class="tablesorter" cellspacing="0" width="100%">
					<thead>
						<tr align="center">
							<th class="header">Factura</th>
                            <th class="header">Fecha</th>
							<th class="header">Moneda</th>
							<th class="header">T.C.</th>
							<th class="header">Unitario</th>
                            <th class="header">Cantidad</th>
							<th class="header">Tipo IVA</th>
							<th class="header">Precio</th>
							<th class="header">Precio M.N.</th>
						</tr>
					</thead>
					<tbody>
				<?php 
					//variables globales de cantidades
					$glo_usd =0;
					$glo_mxn =0;
					$glo_final =0;
					$glo_cant =0;
					
				
					while ($rowf = $db->fetch_array($lista))
                        {
							$id_det = $rowf['id_detalle_fact'];
							$clave = $rowf['id_factura'];
							$unitario = $rowf['unitario'];
							$cantidad = $rowf['cantidad'];
							$clase_iva = $rowf['id_clase_iva'];
							
							//consultare los datos de moneda y TC de cada factura
							$consulta_fac = $db->consulta("SELECT *  FROM `factura` WHERE `id_factura` = '".$clave."';");
							while ($row2 = $db->fetch_array($consulta_fac)){
								$id_mon = $row2['id_moneda'];
								//moneda
								$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
								$consulta_mon = $db->consulta($sql_mon);
								$row_mon = mysql_fetch_array($consulta_mon);
								$moneda = $row_mon['moneda'];
								
								$t_cambio = $row2['tipo_cambio'];
								$t_cambio = number_format($t_cambio,2);
								
								//voy a separar la fecha de la hora
								$fecha = $row2['fecha'];
								$fechas = explode("T", $fecha);
								$solo_fecha = $fechas[0];
								
								$id_iva = $row2['id_iva'];
								//voy a buscar la tasa del iva que vamos a usar
								$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
								$consulta_tas = $db->consulta($sql_tas);
								$row_tas = mysql_fetch_array($consulta_tas);
								$tasa_iva = $row_tas['tasa'];
							}
							
							
							//consultare el nombre del tipo de iva
							$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
							 while ($row2 = $db->fetch_array($consulta_cl)){
								 $nom_clase = $row2['clase_iva'];
							 }
									 
							
							$precio = $unitario * $cantidad;
							$precio_mn = $precio * $t_cambio;
									
							
							//acumulo a los globales
							if($moneda=='MXN'){
								$glo_mxn = $glo_mxn + $precio;
							}else{
								if($moneda=='USD'){
									$glo_usd = $glo_usd + $precio;
								}
							}
							
							$glo_cant = $glo_cant+$cantidad;		
							
							
							 echo "<tr align=\"center\">
							 <td class=\"listado\">".$clave."</td>
							 <td class=\"listado\">".$solo_fecha."</td>
							 <td class=\"listado\">".$moneda."</td>
							 <td class=\"listado\">".$t_cambio."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($unitario,2)."</td>
							 <td class=\"listado\" align=\"right\">".number_format($cantidad,2)."</td>
							 <td class=\"listado\">".$nom_clase."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($precio,2)."</td>
							 <td class=\"listado\" align=\"right\">$ ".number_format($precio_mn,2)."</td>
							 ";
							 
							 
                        }
						
               
                	echo "</tbody></table>";
					
					$glo_usd_conv = $glo_usd * $tc_usd;
					$glo_final = $glo_mxn + $glo_usd_conv;
					
					$venta_diaria_p = $glo_final/$dias;
					$precio_promedio = $glo_final/$glo_cant;
					
					echo "<table width=\"100%\" border=\"0\">
					
					<tr>
						<td align=\"right\"><b>Unidades vendidas: </b></td><td><b>".number_format($glo_cant,2)."</b></td>
						<td align=\"right\"><b>Precio promedio de venta: $</b></td><td><b>".number_format($precio_promedio,2)."</b></td>
						<td align=\"right\"><b>Venta diaria promedio: $</b></td><td><b>".number_format($venta_diaria_p,2)."</b></td>
					</tr>
					
					<tr>
						<td align=\"right\">Ventas en MXN: $</td><td>".number_format($glo_mxn,2)."</td>
						<td align=\"right\">Ventas en USD: $</td><td> ".number_format($glo_usd,2)."</td>
						<td align=\"right\">Ventas totales en MXN: $</td><td> ".number_format($glo_final,2)."</td>
					</tr>
					<tr>
						<td align=\"right\">Tipo de Cambio: </td><td>".number_format($tc_usd,2)."</td>
						<td align=\"right\">Dias del periodo: </td><td>".$dias."</td>
						<td align=\"right\" colspan=\"2\">&nbsp;</td>
					</tr>
					<tr>
						<td align=\"left\" colspan=\"6\"><span>*</span>Montos de venta sin IVA</td>
					</tr>
					<tr>
						<td align=\"left\" colspan=\"6\"><span>*</span>Datos globales en base a tipo de cambio actual</td>
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