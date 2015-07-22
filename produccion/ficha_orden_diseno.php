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
	function confirma2 (url,numero) {
	if (confirm("ALERTA!!!\nEst\u00e1 seguro que desea autorizar la orden?\n una vez autorizada no podra hacer cambios al documento")) location.replace(url);
	}
</script>

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
            <li><a href="ordenes_diseno.php" class="current">Ordenes de Dise&ntilde;o</a></li>
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
                
            <img src="../images/iconos/onebit_21.png" alt="image 3" />
            <a href="ordenes_diseno.php">Ordenes de Dise&ntilde;o</a>
            
        </div>
         
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            

			<?php
                $clave_orden = $_GET['numero'];			
	
                $orden = $db->consulta("SELECT * FROM `orden_diseno` WHERE id_orden='".$clave_orden."'");
                $existe = $db->num_rows($orden);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la &oacute;rden de dise&ntilde;o con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($orden))
                {
					
					$observaciones = $row['especificaciones'];
					$fecha = $row['fecha'];
					$id_est_orden = $row['id_estado'];
					
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
							
							
						}
						
					$id_vendedor = $row['ordena'];
					//datos del vendedor
						$sql_vendedor = "SELECT * FROM myuser where `ID`='".$id_vendedor."'";
						$consulta_vendedor = $db->consulta($sql_vendedor);
						while($row_vend = mysql_fetch_array($consulta_vendedor)){
							$nom_vendedor=$row_vend['userRemark'];
						}
					
					$id_autoriza = $row['id_autoriza'];
					//datos de quien autorizo
						$sql_aut = "SELECT * FROM myuser where `ID`='".$id_autoriza."'";
						$consulta_aut= $db->consulta($sql_aut);
						while($row_aut = mysql_fetch_array($consulta_aut)){
							$nom_autoriza=$row_aut['userRemark'];
						}
					$fecha_autoriza = $row['fecha_autoriza'];
					
					
					//buscar el status
					$consulta_est = $db->consulta("SELECT *  FROM `status_orden_diseno` WHERE `id_status` = '".$id_est_orden."';");
					while ($row2 = $db->fetch_array($consulta_est)){$estado = $row2['status'];}
					
					
			?>
            
            <table align="center" border="0" width="100%">
            	<tr><td><h4>Orden de Dise&ntilde;o</h4></td><td align="right"><h4>No. <?php echo $clave_orden;?></h4></td></tr>
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
                        
                        
                        <?php 
						
						
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
					  $cons = "SELECT `id_producto` FROM `orden_diseno` WHERE id_orden='".$clave_orden."'";
					  $producto_cons = $db->consulta($cons);
					  $existe = $db->num_rows($producto_cons);
					  if($existe<=0){
						  echo "<tr><td><h6>No se han agregado productos a la orden, seleccione uno y complete la informaci&oacute;n:</h4></td></tr>";
						  //***este caso ya nunca va a suceder*****
					   } else {
                        	$i = 1;
							while ($row = $db->fetch_array($producto_cons))
							{
								
								$id_producto = $row['id_producto'];
								
								//tambien consultare los datos de la tabla producto
								$consulta_prod = $db->consulta("SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';");
							   while ($row2 = $db->fetch_array($consulta_prod)){
								   $nom_producto = $row2['nombre'];
								   $cve_prod = $row2['clave'];
								   $block = $row2['cant'];
								   $core = $row2['core'];
								   $dientes = $row2['dientes'];
								   $repeticiones = $row2['repeticiones'];
								   $desc_producto = $row2['descripcion'];
								   $id_unidad = $row2['id_unidad'];
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
										$consulta_pap = $db->consulta("SELECT *  FROM `tipo_papel` WHERE `id_tipo` = '".$id_papel."';");
										while ($row3 = $db->fetch_array($consulta_pap)){$papel = $row3['tipo'];}
										
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
														$cons_p = "SELECT * FROM `tipo_papel` WHERE `id_tipo` =$id_papel;";
														$lista_p = $db->consulta($cons_p);
														while ($row9 = $db->fetch_array($lista_p)){$nombre_papel = $row9['tipo'];}
														$txt = "<i>-".$i." ".$nombre_papel."</i><br>";
														echo $txt;
														$i++;
													}
											  }
											
										}
									}	 
										 
									echo "</td>";	 
										
										 
									echo "<tr></tbody></table>";
									
									
									
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
									echo "<td align=\"right\"><b>Ancho:</b></td><td>$tamano</td>";
									echo "<td align=\"right\"><b>Largo: </b></td><td>$tinta</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Cantidad por unidad:</b></td><td>$block</td>";
									echo "<td align=\"right\"><b>Tintas: </b></td><td>$tinta</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>No. de dado:</b></td><td>$dado</td>";
									echo "<td align=\"right\"><b>Tama&ntilde;o del core: </b></td><td>$core</td>";
									echo "</tr>";
									
									echo "<tr>";
									echo "<td align=\"right\"><b>Dientes del plate roll:</b></td><td>$dientes</td>";
									echo "<td align=\"right\"><b>No de repeticiones: </b></td><td>$repeticiones</td>";
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
											echo "<table align=\"center\" border=\"1\" width=\"40%\" cellspacing=\"0\" cellpadding=\"-10\" bordercolor=\"#000000\">";
											echo "<tr align=\"center\" bgcolor=\"#AAB563\"><td><b>No.</td><td><b>Papel</td><td><b>Cantidad + Desperdicio</td></tr>";
											$i = 1;
											  while ($row = $db->fetch_array($salidas))
											  {
												  $id_salida = $row['id_salida'];
												  $id_papel = $row['id_papel'];
												  $cant_salida = $row['cantidad'];
												  //consulto el nombre del papel
												  $cons_p = "SELECT * FROM `tipo_papel` WHERE `id_tipo` =$id_papel;";
												  $lista_p = $db->consulta($cons_p);
												  while ($row9 = $db->fetch_array($lista_p)){$nom_salida = $row9['tipo'];}
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
										echo "</tr>";
										echo "</table>";
									
									}
									
																
									
									
									
							}
							
							if($id_est_orden==1){
							//si la orden esta pendiente damos la opcion de activarla
							echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_d.php?numero=$clave_orden&estado=2\",\"$clave_orden\");>Activar Orden <img src=\"../images/iconos/onebit_48.png\" width=\"24px\" align=\"center\"></a><br>";
							echo "<small><i>Una vez que se autorice la orden de dise&ntilde; no podr&aacute; realizar cambios, solicite la autorización con su supervisor</i></small><br>";
							echo "</td></tr>";
							
						}else{
							if($id_est_orden==2){
								if($grupo==1){
									echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_d.php?numero=$clave_orden&estado=3\",\"$clave_orden\");>Terminar Orden <img src=\"../images/iconos/onebit_34.png\" width=\"24px\" align=\"center\"></a></td></tr>";
									echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_d.php?numero=$clave_orden&estado=4\",\"$clave_orden\");>Suspender Orden <img src=\"../images/iconos/onebit_49.png\" width=\"24px\" align=\"center\"></a></td></tr>";
								}
								echo "<tr><td align=\"right\" colspan=\"2\"><a href=\"ficha_orden_d_pdf.php?clave=$clave_orden\"><i>Descargar formato de orden de dise&ntilde;o</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></td></tr>";
							}else{
								if($id_est_orden==4){
									if($grupo==1){
									echo "<tr><td align=\"right\" colspan=\"2\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/produccion/actualizar_orden_d.php?numero=$clave_orden&estado=2\",\"$clave_orden\");>Reactivar Orden <img src=\"../images/iconos/onebit_34.png\" width=\"24px\" align=\"center\"></a></td></tr>";
									}
									echo "<tr><td align=\"right\" colspan=\"2\"><a href=\"ficha_orden_d_pdf.php?clave=$clave_orden\"><i>Descargar formato de orden de dise&ntilde;o</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></td></tr>";
								}else{
								echo "<tr><td align=\"right\" colspan=\"2\"><a href=\"ficha_orden_d_pdf.php?clave=$clave_orden\"><i>Descargar formato de orden de dise&ntilde;o</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /></td></tr>";
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