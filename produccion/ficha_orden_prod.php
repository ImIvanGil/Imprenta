<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
$db = new MySQL();
$sqlUser = "SELECT userGroup FROM `myuser` WHERE `userName`='$curUser'";
$consultaUser = $db->consulta($sqlUser);
$rowUser = mysql_fetch_array($consultaUser);
$grupo=$rowUser['userGroup'];
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../styles/ui-lightness/jquery.ui.all.css">
 
<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>

<script src="../js/jquery-1.8.0.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.autocomplete.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>


<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-ins").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: {6:{sorter: false},7:{sorter: false}}});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el elemento n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
	
	function confirma2 (url,numero) {
	if (confirm("ALERTA!!!\nEst\u00e1 seguro que desea autorizar la orden?\n una vez autorizada no podra hacer cambios al documento")) location.replace(url);
	}
</script>

<!--VALIDAR EL FORMULARIO DE PRODUCTO -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarProd() {
			if (document.regProd.producto.value == "-1") {
				alert ('Debe seleccionar un producto');
				document.getElementById('producto').focus();
				return false;
			}
			if (document.regProd.cant.value == "") {
				alert ('Debe escribir la cantidad');
				document.getElementById('cant').focus();
				return false;
			}
			
			return true;
	}
</SCRIPT> 



<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_POST['agregar1'])) {
	$cve = $_POST['numero'];
	$id_prod = $_POST['producto'];
	$id_detalle = $_POST['cve_detalle'];
	$cant = $_POST['cant'];
	$stock = $_POST['stock'];
	$fini = $_POST['fini'];
	$ffin = $_POST['ffin'];
	$completo =0;
	$id_completo =1;
	
//consulto las copias y el tipo de papel para poner las cantidades
$cons = "SELECT * FROM `copias_producto` WHERE id_producto='$id_prod'";
$copias = $db->consulta($cons);
$existe_copias = $db->num_rows($copias);
if($existe_copias>0){
	echo "<tr><td colspan=\"5\"><b>Detalle de salidas de almac&eacute;n:<b></td></tr>";
	  while ($row56 = $db->fetch_array($copias))
	  {
		  $id_registro = $row56['id_registro'];
		  $id_papel = $row56['id_papel'];
		  $name = "pap_$id_registro";
		  //veo si se enviaron los campos con ese dato
		  if (isset($_POST[$name])){$cant_papel = $_POST[$name];}
		  echo $name." - ".$cant_papel."<br>";
		  //hago el insert en la tambla salida_produccion
		  $txt_cons = "INSERT INTO `imprenta`.`salida_produccion` (`id_orden` ,`id_papel` ,`cantidad`)VALUES ('$cve', '$id_papel', '$cant_papel');";
		  $consulta = $db->consulta($txt_cons);
	  }
}
	
	//recibi las variables y ahora hare la consulta con el insert
	$cons = "UPDATE `imprenta`.`detalle_orden_produccion` SET `cantidad` = '$cant',`completo` = '$completo',`stock` = '$stock',`folio_inicio` = '$fini',`folio_final` = '$ffin', `id_completo` = '$id_completo' WHERE `detalle_orden_produccion`.`id_detalle` =$id_detalle;";
	$consulta = $db->consulta($cons);
	$link = "Location: ficha_orden_prod.php?numero=$cve";
	header($link);
}

?>

  <SCRIPT LANGUAGE="JavaScript">
    //validar el formulario
    function validarDetalle() {
        if (document.regProd.cant.value == "") {
            alert ('Debe llenar la cantidad a producir');
            document.getElementById('cant').focus();
            return false;
        }
        if (document.regProd.stock.value == "") {
            alert ('Debe seleccionar escribir la cantidad que se quedara en stock');
            document.getElementById('stock').focus();
            return false;
        }
        
        return true;
    }
    </SCRIPT>


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
            <li><a href="produccion.php">Producci&oacute;n</a></li>
            <li><a href="ordenes_produccion.php" class="current">Ordenes</a></li>
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
                
            <img src="../images/iconos/onebit_59.png" alt="image 3" />
            <a href="nueva_orden_produccion.php">Nueva Orden</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="#">Buscar</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            

			<?php
                $clave_orden = $_GET['numero'];			
	//variable que acumulara el costo total
				$costo_total = 0;
				$costo_mp = 0;
				$costo_maq= 0;
				$costo_mo= 0;
                $orden = $db->consulta("SELECT * FROM `orden_produccion` WHERE id_orden='".$clave_orden."'");
                $existe = $db->num_rows($orden);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la &oacute;rden de producci&oacute;n con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($orden))
                {
					
					$observaciones = utf8_encode($row['observaciones']);
					$fecha = $row['fecha'];
					$id_est_orden = $row['id_estado'];
					$query="SELECT * FROM detalle_orden_produccion WHERE id_orden=".$clave_orden;
					$query=$db->consulta($query);
					$row2=$db->fetch_array($query);
					$aj= $row2['ajuste'];
					$id_detalle1= $row['id_detalle'];
					$id_cliente = $row['id_cliente'];
						//datos del cliente
						$sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
						$consulta_cliente = $db->consulta($sql_cliente);
						while($row_cliente = mysql_fetch_array($consulta_cliente)){
							$nom_cliente=$row_cliente['nombre'];
							$cve_cte=$row_cliente['clave'];
							$rfc_cliente=utf8_decode($row_cliente['rfc']);
							$calle_cliente=utf8_decode($row_cliente['calle']);
							$numero_cliente=$row_cliente['numero'];
							$numInt_cliente=$row_cliente['no_interior'];
							$colonia_cliente=utf8_decode($row_cliente['colonia']);
							$ciudad_cliente=utf8_decode($row_cliente['ciudad']);
							$estado_cliente=$row_cliente['estado'];
							$correo=$row_cliente['correo'];
							$limite_credito=$row_cliente['limite_credito'];
							
							if($correo==""){
								$correo = "Sin dato";
							}
							
							
							
							if($numInt_cliente!=""){
								$dir_cliente= $calle_cliente." No.".$numero_cliente."Int.".$numInt_cliente." Col. ".$colonia_cliente;
							}else{
								$dir_cliente= $calle_cliente." No.".$numero_cliente." Col. ".$colonia_cliente;
							}
							
						
							$cp_cliente=$row_cliente['codigo_postal'];
							$tel_cliente=$row_cliente['telefono'];
							
							//voy a revisar si el cliente tiene saldo pendiente
							
							//variables de cantidades
								$global_mn =0;
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
							
							//consulto las facturas del periodo para el cliente
							$lista = $db->consulta("SELECT * FROM `factura` WHERE `id_cliente`=$id_cliente and `id_status_factura`='2'");
							while ($rowf = $db->fetch_array($lista))
							{
								$clave = $rowf['id_factura'];
								
								//status de la factura
								$id_status = $rowf['id_status_factura'];
								
								//fecha de la factura
								$fecha_fac = $rowf['fecha'];
								$fechas = explode("T", $fecha_fac);
								$solo_fecha = $fechas[0];
								
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
								
								//buscar el status
								$consulta_sta = $db->consulta("SELECT *  FROM `status_factura` WHERE `id_status_factura` = '".$id_status."';");
								while ($row3 = $db->fetch_array($consulta_sta)){
									$status = $row3['status'];
								}
								
								$id_status_cob = $rowf['id_status_cobranza'];
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
									
									while ($row9 = $db->fetch_array($detalle))
									{
										$id_detalle = $row9['id_detalle_fact'];
										$cantidad = $row9['cantidad'];
										$unitario = $row9['unitario'];
										$clase_iva = $row9['id_clase_iva'];
										$orden=
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
											$total_mn = $saldo * $t_cambio;
										}
										
										
										
									}
									$global_mn = $global_mn + $total_mn;
									//Sumo a los globales
									/*if($moneda=='MXN'){
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
										
									*/
								} 
						 
							}
							
						}
						
						
					
					$id_vendedor = $row['id_vendedor'];
					//datos del vendedor
						$sql_vendedor = "SELECT * FROM myuser where `ID`='".$id_vendedor."'";
						$consulta_vendedor = $db->consulta($sql_vendedor);
						while($row_vend = mysql_fetch_array($consulta_vendedor)){
							$nom_vendedor=$row_vend['userRemark'];
							//$nom_vendedor=$id_vendedor;
						}
					
					$id_autoriza = $row['id_autoriza'];
					//datos de quien autorizo
						$sql_aut = "SELECT * FROM myuser where `ID`='".$id_autoriza."'";
						$consulta_aut= $db->consulta($sql_aut);
						while($row_aut = mysql_fetch_array($consulta_aut)){
							$nom_autoriza=$row_aut['userRemark'];
						}
					$fecha_autoriza = $row['fecha_autoriza'];
					$fecha_entrega = $row['fecha_entrega'];
					$urgente = $row['urgente'];
					$orden_compra = $row['orden_compra'];
					$nuevo_dis = $row['nuevo_dis'];
					
					//buscar el status
					$consulta_est = $db->consulta("SELECT *  FROM `status_orden_produccion` WHERE `id_status` = '".$id_est_orden."';");
					while ($row2 = $db->fetch_array($consulta_est)){$estado = $row2['status'];}
					
					
			?>
            
            <table align="center" border="0" width="100%">
            	<tr><td><h4>Orden de Producci&oacute;n</h4></td><td align="right"><h4>No. <?php echo $clave_orden;?></h4></td></tr>
                <tr align="center"><td align="center" colspan="2">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <h5>Datos Generales</h5></legend>
                    <table border="0" width="765px" align="center">
                    	<?php 
						if($urgente=="si"){
							echo "<tr><td align=\"right\" colspan=\"4\"><p style=\"color:#C00\"><b>U R G E N T E</b></p></td></tr>";
						}
						
						
						?>	
                        
                        <tr>
                            <td align="right" colspan="3" align="right"><b>Estado:</b></td>
							<td><b><?php echo $estado; ?></b></td>
                        </tr>
                        <tr>
                            <td align="right"><b>Fecha creaci&oacute;n:</b></td>
							<td><?php echo $fecha; ?></td>
                            <td align="right"><b>Promesa entrega:</b></td>
							<td><?php echo $fecha_entrega; ?></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top"><b>Cliente:</b></td>
							<td colspan="3">
                            	<?php echo $cve_cte; ?><br />
								<?php echo $nom_cliente; ?><br />
                                <?php echo $rfc_cliente; ?><br />
                                <?php echo $dir_cliente; ?><br />
                                <?php echo $ciudad_cliente.", ".$estado_cliente; ?><br />
                                <?php echo "C.P. ".$cp_cliente; ?><br />
                                <?php echo "Tel. ".$tel_cliente; ?><br />
                                <?php echo "E-mail. ".$correo; ?><br />
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><b>Vendedor:</b></td>
							<td colspan="3"><?php echo $nom_vendedor; ?></td>
                        </tr>
                         <tr>
                            <td align="right"><b>Tipo de Dise&ntilde;o:</b></td>
							<td colspan="3"><?php if($nuevo_dis=="si"){echo "Nuevo diseño";}else{echo "Diseño existente";} ?></td>
                        </tr>
                         <tr>
                            <td align="right"><b>Observaciones:</b></td>
							<td colspan="3"><?php echo $observaciones; ?></td>
                        </tr>
                        
                         <tr>
                            <td align="right"><b>Limite de Cr&eacute;dito:</b></td>
							<td colspan="3">$ <?php echo number_format($limite_credito,2); ?></td>
                        </tr>
                         <tr>
                            <td align="right" valign="top"><b>Saldo en cuentas por cobrar:</b></td>
							<td colspan="3"><?php echo "$ ".number_format($global_mn,2)." MXN"; ?></td>
                        </tr>
                        <?php 
							$diferencia = $limite_credito-$global_mn;
							if($diferencia<=0){
								// si ya rebaso su credito dispnible una nota de no surtirle
								echo "<tr><td colspan=\"4\" align=\"center\"><p style=\"color:#C00\"><b>El cliente ha rebasado su limite de cr&eacute;dito y se recomienda no surtirle la orden hasta que liquide su saldo.</b></p></td></tr>";
								
							}else{
								// si no ha rebasado el limite de credito se recomienda surtirle
								echo "<tr><td colspan=\"4\" align=\"center\"><p style=\"color:#090\"><b>El cliente se encuentra dentro del limite de cr&eacute;dito.</b></p></td></tr>";
							}
						
						
						if($id_autoriza!=0){?>
                    	<tr>
                            <td align="right"><b>Ultimo cambio:</b></td>
							<td><?php echo $nom_autoriza; ?></td>
                            <td align="right"><b>Fecha autorizaci&oacute;n:</b></td>
							<td><?php echo $fecha_autoriza; ?></td>
                        </tr>
						<?php
						}
						
						?>
                        
                        
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="center" colspan="2">
                  <!-- Recuadro con los productos adjuntos a la orden -->
                 
                  <div id="data_form">
                  <fieldset><legend><h5>Detalle</h5></legend>								  
                    <table border="0" width="760px" align="center">	
                    
                     <?php
					  $cons = "SELECT * FROM `detalle_orden_produccion` WHERE id_orden='".$clave_orden."'";
					  $productos = $db->consulta($cons);
					  $existe = $db->num_rows($productos);
					  if($existe<=0){
						  echo "<tr><td><h6>No se han agregado productos a la orden, seleccione uno y complete la informaci&oacute;n:</h4></td></tr>";
						  //***este caso ya nunca va a suceder*****
					   } else {
                        	$i = 1;
							while ($row = $db->fetch_array($productos))
							{
								$id_detalle = $row['id_detalle'];
								$id_producto = $row['id_producto'];
								$cantidad = $row['cantidad'];
								$completo = $row['completo'];
								$stock = $row['stock'];
								$cortado = $row['cortado'];
								$folio_inicio = $row['folio_inicio'];
								$folio_final = $row['folio_final'];
								$id_completo = $row['id_completo'];
								$aj= $row['ajuste'];
								
								//tambien consultare los datos de la tabla producto
								$consulta_prod = $db->consulta("SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';");
							   while ($row2 = $db->fetch_array($consulta_prod)){
								   $nom_producto = $row2['nombre'];
								   $cve_prod = $row2['clave'];
								   $block = $row2['cant'];
								   $cored = $row2['core_diam'];
								   $corea = $row2['core_ancho'];
								   $dientes = $row2['dientes'];
								   $repeticionese = $row2['repeticiones_eje'];
								   $repeticionesd = $row2['repeticiones_des'];
								   $desc_producto = $row2['descripcion'];
								   $id_unidad = $row2['id_unidad'];
								   $ajuste= $row2['ajuste'];
								   //aqui dentro consulto el nombre de la unidad
									  $cons_uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
									   while ($row15 = $db->fetch_array($cons_uni)){
										   $unidad = $row15['unidad'];
									  }
									  
									$id_linea = $row2['id_linea'];
								   //aqui dentro consulto el nombre de la linea
									  $cons_lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
									   while ($row15 = $db->fetch_array($cons_lin)){
										   $linea = $row15['linea'];
									  }
									 
									 $ancho = $row2['ancho'];
									 $largo = $row2['largo'];
									 $dado = $row2['dado'];
									 
									 $prec = $row2['precorte'];
									 if($prec==0){
										$precorte = "No";
									}else{
										$precorte= "Si";
									}
									 
									 $id_tintas = $row2['id_tintas'];
									 if($id_tintas=='-1'){
										 $tinta ="Sin Informaci&oacute;n";
										}
									 //buscar las tintas
									$consulta_tin = $db->consulta("SELECT *  FROM `tinta` WHERE `id_tinta` = '".$id_tintas."';");
									while ($row4 = $db->fetch_array($consulta_tin)){$tinta = $row4['tinta'];}
									 
									//consulto los datos del producto de acuerdo a la linea
									if($id_linea==1){
										//si la linea es flexo
										
										//Tipo de papel
										$id_papel = $row2['id_tipo_papel'];
										if($id_papel=="0"){$papel = "Sin Informaci&oacute;n";}
										$consulta_pap = $db->consulta("SELECT *  FROM `insumo` WHERE `id_insumo` = '".$id_papel."';");
										while ($row3 = $db->fetch_array($consulta_pap)){$papel = $row3['nombre'];}
										
										//laminado
										$id_laminado = $row2['laminado'];
										switch ($id_laminado){
											case 0:
												$laminado = "Sin Informaci&oacute;n";
											break;
											case -1:
												$laminado = "No";
											break;
											case 1:
												$laminado = "Si";
											break;
										}
										
										//barnizado
										$id_barnizado = $row2['barnizado'];
										switch ($id_barnizado){
											case 0:
												$barnizado = "Sin Informaci&oacute;n";
											break;
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
										
										//etiquetadora
										$id_etiquetadora = $row2['etiquetadora'];
										switch ($id_etiquetadora){
											case -1:
												$etiquetadora = "Sin Informaci&oacute;n";
											break;
											case 1:
												$etiquetadora = "Si";
											break;
											case 2:
												$etiquetadora = "No";
											break;
										}
										
										//rebobinado
										$id_rebobinado = $row2['rebobinado'];
										switch ($id_rebobinado){
											case -1:
												$rebobinado = "Sin Informaci&oacute;n";
											break;
											case 1:
												$rebobinado = "Izquierdo";
											break;
											case 2:
												$rebobinado = "Derecho";
											break;
										}
										
									}else{
										//si la linea es offset
										//consulto la prensa
										 $id_prensa = $row2['prensa'];
										 switch ($id_prensa){
											case 0:
												$prensa = "Sin Informaci&oacute;n de tipo de prensa";
											break;
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
										
										 $pantone = $row2['color'];
										 $ext_ancho = $row2['ext_ancho'];
										 $ext_largo = $row2['ext_largo'];
										 
										 $grap = $row2['grapado'];
										 if($grap==1){
											 $grapado = "Si";
										 }else{$grapado = "No";}
										 
										 $peg = $row2['pegado'];
										 if($peg==1){
											 $pegado = "Quimico";
										 }else{
											 if($peg==2){
												 $pegado = "Bond";
											}else{$pegado = "Sin informacion";}
										}
										 
										 $marginal = $row2['marginal'];
										 $prefijo = $row2['prefijo'];
										 $sufijo = $row2['sufijo'];
										
										$perf = $row2['perforacion'];
										 if($perf==1){
											 $perforacion = "Si";
										 }else{$perforacion = "No";}
										 
										 $eng = $row2['engargolado'];
										 if($eng==1){
											 $engargolado = "Si";
										 }else{$engargolado = "No";}
										 
										 $enc = $row2['encuadernado'];
										 if($enc==1){
											 $encuadernado = "Si";
										 }else{$encuadernado = "No";}
												
										$imp = $row2['impresion'];
										 if($imp==1){
											 $impresion = "Frente";
										 }else{
											 if($imp==2){
												 $impresion = "Reverso";
											}else{
												if($imp==3){
													$impresion = "Ambos lados";
												}else{
													$impresion = "Sin informacion";
												}}
										}
												
															
									}
									
									
								   
								   }
								   ?>
								   <table id="tablesorter-prod" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">Clave</th>
                                                <th class="header">Producto</th>
                                                <th class="header">Cantidad</th>
                                                
                                                <?php 
												if($id_est_orden<2){
												//si el estatus es pendiente
													echo "<th>Editar</th>
                                                	<th>Eliminar</th>";
												}
												if($id_est_orden==2){
													echo "<th>Recibir</th>";
													echo "<th>Recibido</th>";
													echo "<th>Ajustar</th>";
													echo "<th>Ajustado</th>";
												}
												?>
											</tr>
									</thead>
									<tbody>
							<?php 
                                        
								   echo "<tr><td class=\"listado\" align=\"center\" valign=\"top\">".$cve_prod."</td>
										 <td align=\"left\"><b>".$nom_producto."</b></br>";
									if($id_linea=="1"){
										//si es Flexo
										echo "<b>Papel:</b> $papel<br>";
										echo "<b>Laminado:</b> $laminado<br>";
										echo "<b>Barnizado:</b> $barnizado<br>";
										echo "<b>Etiquetadora:</b> $etiquetadora<br>";
										echo "<b>Rebobinado:</b> $rebobinado<br>";
										
									}else{
										if($id_linea=="2"){
											//si es offset
											echo "<b>Prensa:</b> $prensa<br>";
											echo "<b>Pantone:</b> $pantone<br>";
											//consulto las copias y el tipo de papel
											  $cons = "SELECT * FROM `copias_producto` WHERE id_producto='$id_producto'";
											  $copias = $db->consulta($cons);
											  $existe_copias = $db->num_rows($copias);
											  if($existe_copias<=0){
												  echo "No hay registros de copias para este producto<br>";
											  }else{
												  echo "<b>Detalle de copias:</b><br>";
												  $i = 1;
													while ($row = $db->fetch_array($copias))
													{
														$id_registro = $row['id_registro'];
														$id_papel = $row['id_papel'];
														//consulto el nombre del papel
														$cons_p = "SELECT * FROM `insumo` WHERE `id_insumo` =$id_papel;";
														$lista_p = $db->consulta($cons_p);
														while ($row9 = $db->fetch_array($lista_p)){$nombre_papel = $row9['nombre'];}
														$txt = "<i>-".$i." ".$nombre_papel."</i><br>";
														echo $txt;
														$i++;
													}
											  }
											
										}
									}	 
										 
									echo "</td>";	 
									echo "<td class=\"listado\" align=\"center\">".$cantidad."</td>";
									if($id_est_orden==2){
										echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"recibir_producto.php?numero=$id_producto&key=$clave_orden&cantidad=$cantidad\"><img src=\"../images/iconos/onebit_82.png\" width=\"24px\" align=\"center\"></a></td>";
										$query="SELECT * from producto_almacen where id_orden_produccion=".$clave_orden.";";
										//echo $query;
										$query=$db->consulta($query);
										$suma_cantidades=0;
										while($row3=$db->fetch_array($query)){
											$aux=$row3['cantidad'];
											$suma_cantidades=$suma_cantidades+$aux;
										}
										//echo $suma_cantidades;
										//$suma_cantidades = $suma_recibido+$ajuste;
										
										echo "<td><center>".$suma_cantidades."</center></td>";
										echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"http://localhost/imprenta/produccion/ajuste_producto.php?numero=$clave_orden&key=$id_detalle\"><img src=\"../images/iconos/onebit_59.png\" width=\"24px\" align=\"center\"></a></td>";
										echo "<td>".$aj."</td>";
									}
									if($id_est_orden<2){		 
										 echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_orden_prod.php?numero=$clave_orden&key=$id_detalle\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
											   <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/produccion/borrar_prod_orden.php?numero=$clave_orden&key=$id_detalle\",\"$id_detalle\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
											   
											   
									  }	 
										 
									echo "<tr></tbody></table>";
									
									
									//MOSTRARE EL FORMULARIO PARA COMPLETAR LOS DATOS
									if($id_completo==0){
									?>
                                    
                                    <form method="post" action="ficha_orden_prod.php" name="regProd" onsubmit="return validarDetalle()">
                                    <input type="hidden" name="numero" id="numero" value="<?php echo $clave_orden;?>">	
                                    <input type="hidden" name="producto" id="producto" value="<?php echo $id_producto;?>">	
                                    <input type="hidden" name="cve_detalle" id="cve_detalle" value="<?php echo $id_detalle;?>">	
                                        <table border="0" width="760px" align="center">	
                                            
                                            <tr><td colspan="5"><h6>Complete los datos de la orden:</h6></td></tr>
                                            <tr>
                                            <td align="right"><b>Cantidad: </b></td>
                                            <td><input type="text" class="texto" id="cant" name="cant" size="10"></td>
                                            <td width="25%">&nbsp;</td>
                                            <td align="right"><b>Stock: </b></td>
                                            <td><input type="text" class="texto" id="stock" name="stock" size="10" /></td>
                                            </tr>
                                            
                                            
                                            
                                            <?php 
											if($id_linea=="2"){
											?>
                                                <tr>
                                                <td align="right"><b>Folio inicial: </b></td>
                                                <td><input type="text" class="texto" id="fini" name="fini" size="10" /></td>
                                                <td width="25%">&nbsp;</td>
                                                <td align="right"><b>Folio final: </b></td>
                                                <td><input type="text" class="texto" id="ffin" name="ffin" size="10" /></td>
                                                </tr>
                                            <?php
												//consulto las copias y el tipo de papel para poner las cantidades
												  $cons = "SELECT * FROM `copias_producto` WHERE id_producto='$id_producto'";
												  $copias = $db->consulta($cons);
												  $existe_copias = $db->num_rows($copias);
												  if($existe_copias>0){
													  echo "<tr><td colspan=\"5\"><b>Detalle de salidas de almac&eacute;n:<b></td></tr>";
													  $i = 1;
														while ($row = $db->fetch_array($copias))
														{
															$id_registro = $row['id_registro'];
															$id_papel = $row['id_papel'];
															//consulto el nombre del papel
															$cons_p = "SELECT * FROM `insumo` WHERE `id_insumo` =$id_papel;";
															$lista_p = $db->consulta($cons_p);
															while ($row9 = $db->fetch_array($lista_p)){$nombre_papel = $row9['nombre'];}
															
															$txt = "<i>".$i." - ".$nombre_papel.", cantidad+ desperdicio:</i><br>";
															$name = "pap_$id_registro";
															echo "<tr><td align=\"right\">$txt</td>";
															echo"<td align=\"left\" colspan=\"4\"><input type=\"text\" class=\"texto\" id=\"".$name."\" name=\"".$name."\" size=\"10\" /></td>";
															echo "</tr>";
															$i++;
														}
												  }
											 
											}
											?>
                                            
                                            
                                            <tr><td colspan="5" align="right"><input class="submit_btn reset" type="submit" name="agregar1" id="agregar1" value="Guardar >>"/></td></tr>
                                        </table>
                                    </form>
                                    
                                    
                                    <?php
									}
									
									
									echo "<table border=\"0\" width=\"100%\" align=\"center\">";
									
									echo "<tr>";									
									echo "<td align=\"right\"><b>Cantidad:</b></td><td>$cantidad</td>";
									echo "<td align=\"right\"><b>Stock:</b></td><td>$stock</td>";
									echo "</tr>";
									
									
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Linea:</b></td><td>$linea</td>";
									echo "<td align=\"right\"><b>Unidad de Medida:</b></td><td>$unidad</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Ancho:</b></td><td>$ancho</td>";
									echo "<td align=\"right\"><b>Largo: </b></td><td>$largo</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Cantidad por unidad:</b></td><td>$block</td>";
									echo "<td align=\"right\"><b>Tintas: </b></td><td>$tinta</td>";
									echo "</tr>";
									
									
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Precorte:</b></td><td colspan=\"3\">$precorte</td>";
									echo "</tr>";
									
									if($id_linea==2){
										//si la linea es offset ponemos el dato de prensa 
										echo "<tr>";
										echo "<td align=\"right\"><b>Folio inicial:</b></td><td>$folio_inicio</td>";
										echo "<td align=\"right\"><b>Folio final:</b></td><td>$folio_final</td>";
										echo "</tr>";
									
										echo "<tr>";
										echo "<td align=\"right\"><b>Prensa:</b></td><td>$prensa</td>";
										echo "<td align=\"right\"><b>Impresion:</b></td><td>$impresion</td>";
										echo "</tr>";
										
										echo "<tr>";
										echo "<td align=\"right\"><b>Extendido Ancho:</b></td><td>$ext_ancho</td>";
										echo "<td align=\"right\"><b>Extendido Largo:</b></td><td>$ext_largo</td>";
										echo "</tr>";
										
										
										
										echo "<tr>";
										echo "<td align=\"right\"><b>Grapado:</b></td><td>$grapado</td>";
										echo "<td align=\"right\"><b>Pegado:</b></td><td>$pegado</td>";
										echo "</tr>";
										
										echo "<tr>";
										echo "<td align=\"right\"><b>Prefijo:</b></td><td>$prefijo</td>";
										echo "<td align=\"right\"><b>Sufijo:</b></td><td>$sufijo</td>";
										echo "</tr>";
										
										echo "<tr>";
										echo "<td align=\"right\"><b>Marginal:</b></td><td>$marginal</td>";
										echo "<td align=\"right\"><b>Perforacion:</b></td><td>$perforacion</td>";
										echo "</tr>";
										
										echo "<tr>";
										echo "<td align=\"right\"><b>Engargolado:</b></td><td>$engargolado</td>";
										echo "<td align=\"right\"><b>Encuadernado:</b></td><td>$encuadernado</td>";
										echo "</tr>";
										
										echo "<tr><td align=\"right\" colspan=\"4\"><hr></td></tr>";
										echo "<tr><td align=\"center\" colspan=\"4\"><b>Detalle de Salidas de Almacen</b></td></tr>";
										echo "<tr><td align=\"center\" colspan=\"4\">";
										//voy a mostrar las cantidades en las salidas de almacen
										$cons = "SELECT * FROM `salida_produccion` WHERE id_orden='$clave_orden'";
										$salidas = $db->consulta($cons);
										$existe_salidas = $db->num_rows($salidas);
										if($existe_salidas>0){
											echo "<table align=\"center\" border=\"1\" width=\"60%\" cellspacing=\"0\" cellpadding=\"-10\" bordercolor=\"#000000\">";
											echo "<tr align=\"center\" bgcolor=\"#AAB563\"><td><b>No.</td><td><b>Papel</td><td><b>Cantidad + Desperdicio</td></tr>";
											$i = 1;
											  while ($row = $db->fetch_array($salidas))
											  {
												  $id_salida = $row['id_salida'];
												  $id_papel = $row['id_papel'];
												  $cant_salida = $row['cantidad'];
												  //consulto el nombre del papel
												  $cons_p = "SELECT * FROM `insumo` WHERE `id_insumo` =$id_papel;";
												  $lista_p = $db->consulta($cons_p);
												  while ($row9 = $db->fetch_array($lista_p)){$nom_salida = $row9['nombre'];}
												  echo "<tr><td align=\"center\">$i</td><td align=\"left\">$nom_salida</td><td align=\"right\">$cant_salida</td></tr>";
												  $i++;
											  }
											  echo "</table>";
										}
										
										
										echo "</td></tr>";
										echo "</table>";
										
										
									}else{
										//si es flexo muestro los datos
										echo "<tr>";
										echo "<td align=\"right\"><b>Tipo de papel:</b></td><td>$papel</td>";
										echo "<td align=\"right\"><b>Etiquetadora:</b></td><td>$etiquetadora</td>";
										echo "</tr>";
										
										echo "<tr>";
										echo "<td align=\"right\"><b>Laminado:</b></td><td>$laminado</td>";
										echo "<td align=\"right\"><b>Barnizado:</b></td><td>$barnizado</td>";
										echo "<tr>";
									echo "<td align=\"right\"><b>Diametro del core:</b></td><td>$cored</td>";
									echo "<td align=\"right\"><b>Ancho del core: </b></td><td>$corea</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>No. de dado:</b></td><td>$dado</td>";
									echo "<td align=\"right\"><b>Dientes del plate roll:</b></td><td>$dientes</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Repeticiones al eje:</b></td><td>$repeticionese</td>";
									echo "<td align=\"right\"><b>Repeticiones al desarrollo: </b></td><td>$repeticionesd</td>";
									echo "</tr>";
									
									
										echo "</tr>";
										echo "</table>";
									
									}
									
																
									
									
									
							}
							
							if($id_est_orden==1){
							//si la orden esta pendiente damos la opcion de activarla
							echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_p.php?numero=$clave_orden&estado=2\",\"$clave_orden\");>Activar Orden <img src=\"../images/iconos/onebit_48.png\" width=\"24px\" align=\"center\"></a><br>";
							echo "<small><i>Una vez que se autorice la orden de producci&oacute;n no podr&aacute; realizar cambios, solicite la autorización con su supervisor</i></small><br>";
							echo "</td></tr>";
							
						}else{
							if($id_est_orden==2){
								if($grupo==1){
									echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_p.php?numero=$clave_orden&estado=4\",\"$clave_orden\");>Suspender Orden <img src=\"../images/iconos/onebit_49.png\" width=\"24px\" align=\"center\"></a></td></tr>";
								}
								echo "<tr><td align=\"right\" colspan=\"2\"><a href=\"ficha_orden_p_pdf.php?clave=$clave_orden\"><i>Descargar formato de orden de producci&oacute;n</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></td></tr>";
							}else{
								if($id_est_orden==4){
									if($grupo==1){
									echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_p.php?numero=$clave_orden&estado=2\",\"$clave_orden\");>Reactivar Orden <img src=\"../images/iconos/onebit_34.png\" width=\"24px\" align=\"center\"></a></td></tr>";
									}
									echo "<tr><td align=\"right\" colspan=\"2\"><a href=\"ficha_orden_p_pdf.php?clave=$clave_orden\"><i>Descargar formato de orden de producci&oacute;n</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></td></tr>";
								}else{
								echo "<tr><td align=\"right\" colspan=\"2\"><a href=\"ficha_orden_p_pdf.php?clave=$clave_orden\"><i>Descargar formato de orden de producci&oacute;n</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></td></tr>";
								}
								
							}
							
							
						}
									
						}
						
						
						?>
                      
                        </table>
                        
                        </td>
                        </tr>
                    </table>
                 
                  </fieldset>
                  </div>
                </td></tr>
           
                
            </table>  
            </form>
            <?php
            }
            
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
ob_end_flush();
?>