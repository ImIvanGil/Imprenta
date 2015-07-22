<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
$db = new MySQL();
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

	<style>
	.ui-insumo {
		position: relative;
		display: inline-block;
	}
	.ui-insumo-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* adjust styles for IE 6/7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.ui-insumo-input {
		margin: 0;
		padding: 0.3em;
		width:500px;
	}
	</style>
	<script>
	(function( $ ) {
		$.widget( "ui.insumo", {
			_create: function() {
				var input,
					self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "",
					wrapper = this.wrapper = $( "<span>" )
						.addClass( "ui-insumo" )
						.insertAfter( select );

				input = $( "<input>" )
					.appendTo( wrapper )
					.val( value )
					.addClass( "ui-state-default ui-insumo-input" )
					.autocomplete({
						delay: 0,
						minLength: 0,
						source: function( request, response ) {
							var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
							response( select.children( "option" ).map(function() {
								var text = $( this ).text();
								if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
										label: text.replace(
											new RegExp(
												"(?![^&;]+;)(?!<[^<>]*)(" +
												$.ui.autocomplete.escapeRegex(request.term) +
												")(?![^<>]*>)(?![^&;]+;)", "gi"
											), "<strong>$1</strong>" ),
										value: text,
										option: this
									};
							}) );
						},
						select: function( event, ui ) {
							ui.item.option.selected = true;
							self._trigger( "selected", event, {
								item: ui.item.option
							});
						},
						change: function( event, ui ) {
							if ( !ui.item ) {
								var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
									valid = false;
								select.children( "option" ).each(function() {
									if ( $( this ).text().match( matcher ) ) {
										this.selected = valid = true;
										return false;
									}
								});
								if ( !valid ) {
									// remove invalid value, as it didn't match anything
									$( this ).val( "" );
									select.val( "" );
									input.data( "autocomplete" ).term = "";
									return false;
								}
							}
						}
					})
					.addClass( "ui-widget ui-widget-content ui-corner-left" );

				input.data( "autocomplete" )._renderItem = function( ul, item ) {
					return $( "<li></li>" )
						.data( "item.autocomplete", item )
						.append( "<a>" + item.label + "</a>" )
						.appendTo( ul );
				};

				$( "<a>" )
					.attr( "tabIndex", -1 )
					.attr( "title", "Show All Items" )
					.appendTo( wrapper )
					.button({
						icons: {
							primary: "ui-icon-triangle-1-s"
						},
						text: false
					})
					.removeClass( "ui-corner-all" )
					.addClass( "ui-corner-right ui-insumo-toggle" )
					.click(function() {
						// close if already visible
						if ( input.autocomplete( "widget" ).is( ":visible" ) ) {
							input.autocomplete( "close" );
							return;
						}

						// work around a bug (likely same cause as #5265)
						$( this ).blur();

						// pass empty string as value to search for, displaying all results
						input.autocomplete( "search", "" );
						input.focus();
					});
			},

			destroy: function() {
				this.wrapper.remove();
				this.element.show();
				$.Widget.prototype.destroy.call( this );
			}
		});
	})( jQuery );

	$(function() {
		$( "#insumo" ).insumo();
		$( "#toggle" ).click(function() {
			$( "#insumo" ).toggle();
		});
	});
	</script>


<!-- script que hace el requisicionamiento de la tabla -->
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
	if (confirm("ALERTA!!!\nEst\u00e1 seguro que desea autorizar la requisicion?\n una vez autorizada no podra hacer cambios al documento")) location.replace(url);
	}
</script>

<!--VALIDAR EL FORMULARIO DE PRODUCTO -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarIns() {
			if (document.regIns.insumo.value == "-1") {
				alert ('Debe seleccionar un insumo');
				document.getElementById('insumo').focus();
				return false;
			}
			if (document.regIns.cant.value == "") {
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
	$cve_requisicion = $_POST['numero'];
	$num_parte = $_POST['nparte'];
	$cant = $_POST['cant'];
	$cve_ins = $_POST['insumo'];	
	$observaciones = $_POST['observaciones'];
	//recibi las variables y ahora hare la consulta con el insert

	$consulta = $db->consulta("INSERT INTO `imprenta`.`detalle_requisicion` ( `id_requisicion` ,`id_insumo` ,`num_parte` ,`descripcion` ,`cantidad`) VALUES ('$cve_requisicion', '$cve_ins', '$num_parte', '$observaciones', '$cant');");
	$link = "Location: ficha_requisicion.php?numero=$cve_requisicion";
	header($link);
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
            <li><a href="compras.php">Compras</a></li>
            <li><a href="requisiciones.php" class="current">Requisiciones</a></li>
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
                
            <img src="../images/iconos/onebit_39.png" alt="image 3" />
            <a href="nueva_req.php">Nueva Requisicion</a>
            
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
    
            <h2>Requisici&oacute;n de Compra</h2>

			<?php
                $clave = $_GET['numero'];			
				//variables que acumularan los totales
				$sub_total = 0;
				$graba_normal = 0;
				$graba_cero = 0;
				$exento = 0;
				$iva = 0;
				$total_requisicion = 0;
				
				
                $requisicion = $db->consulta("SELECT * FROM `requisicion` WHERE id_requisicion='".$clave."'");
                $existe = $db->num_rows($requisicion);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la requisici&oacute;n con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($requisicion))
                {
					
						$id_empresa = $row['id_empresa'];
						//datos de la empresa
						$sql_empresa = "SELECT * FROM empresa where `id_empresa`='".$id_empresa."'";
						$consulta_empresa = $db->consulta($sql_empresa);
						while($row_emp = mysql_fetch_array($consulta_empresa)){
							$nom_emp=$row_emp['nombre'];
							$rfc_emp=$row_emp['rfc'];
						}
					  	$id_proveedor = $row['id_proveedor'];
						//datos del proveedor
						$sql_proveedor = "SELECT * FROM proveedor where `id_proveedor`='".$id_proveedor."'";
						$consulta_proveedor = $db->consulta($sql_proveedor);
						while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
							$nom_proveedor=$row_proveedor['nombre'];
							$rfc_proveedor=utf8_decode($row_proveedor['rfc']);
							$calle_proveedor=utf8_decode($row_proveedor['calle']);
							$numero_proveedor=$row_proveedor['numero'];
							$colonia_proveedor=utf8_decode($row_proveedor['colonia']);
							$ciudad_proveedor=$row_proveedor['ciudad'];
							$estado_proveedor=$row_proveedor['estado'];
							
							$dir_proveedor= $calle_proveedor." No.".$numero_proveedor." Col. ".$colonia_proveedor;
							
							
						
							$cp_proveedor=$row_proveedor['codigo_postal'];
							$tel_proveedor=$row_proveedor['telefono'];
						}
						
						$fecha = $row['fecha'];
						
						
						$id_status = $row['id_status'];
						// estado de la requisicion
						$sql_status = "SELECT status FROM status_orden WHERE id_status='".$id_status."'";
						$consulta_status = $db->consulta($sql_status);
						$row_status = mysql_fetch_array($consulta_status);
						$status = $row_status['status'];
						
						$observaciones = $row['observaciones'];
						
						
						
						$id_ordena = $row['id_ordena'];
						//voy a buscar el nombre del usuario que genera la requisicion
						$sql_us = "SELECT * FROM myuser WHERE ID='".$id_ordena."'";
						$consulta_us = $db->consulta($sql_us);
						$row_us = mysql_fetch_array($consulta_us);
						$requisiciona = $row_us['userRemark'];
						
						if($id_status!=1){
							$id_autoriza = $row['id_autoriza'];
							//voy a buscar el nombre del usuario que autoriza la requisicion
							$sql_us = "SELECT * FROM myuser WHERE ID='".$id_autoriza."'";
							$consulta_us = $db->consulta($sql_us);
							$row_us = mysql_fetch_array($consulta_us);
							$autoriza = $row_us['userRemark'];
						}
						
						

			?>
            
            <table align="center" border="0" width="100%" cellspacing="0" cellpadding="0">
            	
                <tr><td align="left">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos Generales</B></legend>
                    <table border="0" width="750px" align="center">	
                       
                        <tr>
                            <td align="right"><b>Estado:</b></td>
							<td align="left"><?php echo $status; ?></td>
                            
                            <td align="right"><b>Fecha:</b></td>
							<td align="left"><?php echo $fecha; ?></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" width="20%"><b>Proveedor recomendado:</b></td>
							<td colspan="3" align="left" valign="top">
								<?php echo $nom_proveedor; ?><br />
                                <?php echo $dir_proveedor; ?><br />
                                <?php echo $ciudad_proveedor.", ".$estado_proveedor; ?><br />
                                <?php echo "C.P. ".$cp_proveedor; ?><br />
                                <?php echo "Tel. ".$tel_proveedor; ?><br />
                            </td>
                        </tr>
                      
                     
                        
                        <?php 
						if($id_status!=1){
						?>	
                             <tr>
                                <td align="right"><b>Ordenado por:</b></td>
                                <td align="left"><?php echo $requisiciona; ?></td>
                                <td align="right"><b>Autorizado por:</b></td>
                                <td align="left"><?php echo $autoriza; ?></td>
                            </tr>
                        
						<?php
						}else{
						?>
                           <tr>
                                <td align="right"><b>Ordenado por:</b></td>
                                <td align="left" colspan="3"><?php echo $requisiciona; ?></td>
                            </tr>
						<?php 
						}
                            
							if($observaciones!=""){
                                echo "<tr>";
                                echo "<td align=\"right\"><b>Prop&oacute;sito de la compra:</b></td>";
                                echo "<td colspan=\"3\" align=\"left\">$observaciones</td>";
                                echo "</tr>";
                            }
                        ?>
                        
                        
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="left">
                  <!-- Recuadro con los datos de insumos-->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Detalle</B></legend>
                    <table border="0" width="700px" align="center">	
               <?php 
				if($id_status==1){
					//si el estatus es activo podemos seguir agregando insumos a la requisicion
				?>
                    
                        <tr>
                            <td align="center">
                            <form method="post" action="ficha_requisicion.php" name="regIns" onsubmit="return validarIns()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">	
                            	<table border="0" width="100%">	
                                    <tr>
                                    	<td align="right"><b>Insumo: </b></td>
                                    	<td align="left	" valign="middle" colspan="4">
                                        <select id="insumo" name="insumo">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_ins = $db->consulta("SELECT * FROM `insumo` ORDER BY id_insumo ASC;");
													while ($row3 = $db->fetch_array($lista_ins))
													{
													  $id_insumo = $row3['id_insumo'];
													  $nombre = $row3['nombre'];
													  $descripcion = $row3['descripcion'];
													  $dato = $nombre." - ".$descripcion;
													  echo "<option value=\"".$id_insumo."\">".$dato."</option>";
													}
											?>
                                        </select></td>
                                        </tr>
                                        
                                        <tr>
                                        <td align="right"><b>Num. Parte: </b></td>
                                    	<td><input class="texto" type="text" id="nparte" name="nparte" size="10" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Cantidad: </b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="10" /><br /></td>
                                        </tr>
                                        
                                         <tr>
                                        <td align="right"><b>Observaciones: </b></td>
                                    	<td colspan="4"><textarea id="observaciones" name="observaciones" rows="2" cols="20"></textarea><br /></td>
                                        </tr>
                                        
                                        <tr>
                                        <td colspan="5" align="right"><input class="submit_btn reset" type="submit" name="agregar1" id="agregar1" value="Agregar"/></td>
                                        </tr>
                                        
                                </table>
                            </form>
                            </td>
                        </tr>
                        
                 <?php 
				 	//fin del if de estatus activo
				 	} 
				 
				 ?>
                        
                        <tr>
                            <td align="left">
								<?php 
									$suma_completos = 0;
                                    $detalle = $db->consulta("SELECT * FROM `detalle_requisicion` WHERE id_requisicion='".$clave."'");
                                    $existe = $db->num_rows($detalle);
                                    if($existe<=0){
                                        echo "<p align=\"center\">No se han agregado insumos a la requisicion</p>";
                                    
                                    }else{
									?>
										<table id="tablesorter-ins" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No. de Parte</th>
                                                <th class="header">Cantidad</th>
                                                <th class="header">Insumo</th>
                                                <th class="header">Observaciones</th>
                                               
                                                
                                                <?php 
												if($id_status==1){
												//si el estatus es activo podemos modificar los conceptos
													echo "<th>Editar</th>
                                                	<th>Eliminar</th>";
												}
												
												?>
                                                
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$i = 1;
										while ($row = $db->fetch_array($detalle))
										{
											$id_detalle = $row['id_detalle'];
											$id_insumo = $row['id_insumo'];
											$num_parte = $row['num_parte'];
											$desc = $row['descripcion'];
											$cantidad = $row['cantidad'];
											
											
											
											//consultare el nombre del insumo
											$consulta_ins = $db->consulta("SELECT *  FROM `insumo` WHERE `id_insumo` = '".$id_insumo."';");
											 while ($row2 = $db->fetch_array($consulta_ins)){
												 $nom_insumo = $row2['nombre'];
												 $desc_insumo = $row2['descripcion'];
												 $id_unidad = $row2['id_unidad'];
												 //aqui dentro consulto el nombre de la unidad
													$cons_uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
													 while ($row15 = $db->fetch_array($cons_uni)){
														 $unidad = $row15['unidad'];
													}
												 }
											
											
											//ahora imprimire la tabla
											
                                        
                                                       echo "<tr>
															 <td align=\"center\">".$num_parte."</td>
															 <td align=\"center\">".$cantidad."</td>
															 <td align=\"center\">".$nom_insumo."</td>
															 <td align=\"center\">".$desc."</td>";
															 
														if($id_status==1){		 
														   echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_requisicion_ins.php?numero=$clave&key=$id_detalle\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
																 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_requisicion_ins.php?numero=$clave&key=$id_detalle\",\"$id_detalle\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
																 
														}
														
														echo "</tr>";
                                                            
                                                            
                                                    ?>
                                                
											
											<?php	
											$i++;
										}
									}
                                ?>
                            		</tbody>
                                </table>
                              
                                
                               
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="right">
                            <?php 
                            if($id_status==1){
                                // si la requisicion esta activa damos la opcion de autorizarla
                                echo "<a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/compras/autorizar_requisicion.php?numero=$clave\",\"$clave\");>Autorizar Requisicion <img src=\"../images/iconos/onebit_48.png\" width=\"24px\" align=\"center\"></a><br>";
								
                                echo "<small><i>Una vez que se autorice la requisicion de compra no podr&aacute; realizar cambios</i></small><br>";
                                
                            }else{
                              	if ($suma_completos==$existe){
									//si todos los  insumos han entrado al almacen entonces cambio la requisicion al estado de terminada
									$query = "UPDATE requisicion_compra SET id_status='3' WHERE id_requisicion ='$clave';";
									$consulta = $db->consulta($query);
									echo "<br><b>La requisicion se ha completado y esta cerrada</b><br>";
								}
								//si el estado de la requisicion es autorizada damos la opcion de imprimir
								echo "<a href=\"ficha_requisicion_pdf.php?clave=$clave\"><i>Descargar formato de requisicion de compra</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
                                 
                            }
                                
                            ?>                 
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