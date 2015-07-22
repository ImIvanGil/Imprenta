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
			if (document.regIns.unitario.value == "") {
				alert ('Debe escribir el precio unitario del insumo');
				document.getElementById('unitario').focus();
				return false;
			}
			if (document.regIns.tiva.value == "-1") {
				alert ('Debe seleccionar la clase de iva que se va a calcular');
				document.getElementById('tiva').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 



<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_POST['agregar1'])) {
	$cve_orden = $_POST['numero'];
	$num_parte = $_POST['nparte'];
	$cant = $_POST['cant'];
	$cve_ins = $_POST['insumo'];	
	$largo = $_POST['largo'];
	$ancho = $_POST['ancho'];
	$observaciones = $_POST['observaciones'];
	$unitario = $_POST['unitario'];
	$descuento = $_POST['desc'];
	$tiva = $_POST['tiva'];
	
	//recibi las variables y ahora hare la consulta con el insert

	$consulta = $db->consulta("INSERT INTO `imprenta`.`detalle_orden` ( `id_orden` ,`id_insumo` ,`num_parte` ,`descripcion` ,`cantidad` , `ancho` , `largo` , `precio` , `descuento` , `id_clase_iva`) VALUES ('$cve_orden', '$cve_ins', '$num_parte', '$observaciones', '$cant', '$ancho', '$largo', '$unitario', '$descuento', '$tiva');");
	$link = "Location: ficha_orden.php?numero=$cve_orden";
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
            <li><a href="ordenes_compra.php" class="current">Ordenes</a></li>
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
                
            <img src="../images/iconos/onebit_77.png" alt="image 3" />
            <a href="nueva_orden.php">Nueva Orden</a>
            
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
    
            <h2>Orden de Compra</h2>

			<?php
                $clave = $_GET['numero'];			
				//variables que acumularan los totales
				$sub_total = 0;
				$graba_normal = 0;
				$graba_cero = 0;
				$exento = 0;
				$iva = 0;
				$total_orden = 0;
				
				
                $orden = $db->consulta("SELECT * FROM `orden_compra` WHERE id_orden='".$clave."'");
                $existe = $db->num_rows($orden);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la orden con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($orden))
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
						// estado de la orden
						$sql_status = "SELECT status FROM status_orden WHERE id_status='".$id_status."'";
						$consulta_status = $db->consulta($sql_status);
						$row_status = mysql_fetch_array($consulta_status);
						$status = $row_status['status'];
						
						$id_mon = $row['id_moneda'];
						//moneda
						$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
						$consulta_mon = $db->consulta($sql_mon);
						$row_mon = mysql_fetch_array($consulta_mon);
						$moneda = $row_mon['moneda'];
						
						$plazo = $row['plazo'];
						$observaciones = $row['observaciones'];
						
						$tipo_cambio = number_format($row['tipo_cambio'],2);
						
						$id_iva = $row['id_iva'];
						//voy a buscar la tasa del iva que vamos a usar
						$sql_tas = "SELECT * FROM iva WHERE id_iva='".$id_iva."'";
						$consulta_tas = $db->consulta($sql_tas);
						$row_tas = mysql_fetch_array($consulta_tas);
						$tasa_iva = $row_tas['tasa'];
						$tipo_iva = $row_tas['tipo'];
						
						
						$id_ordena = $row['id_ordena'];
						//voy a buscar el nombre del usuario que genera la orden
						$sql_us = "SELECT * FROM myuser WHERE ID='".$id_ordena."'";
						$consulta_us = $db->consulta($sql_us);
						$row_us = mysql_fetch_array($consulta_us);
						$ordena = $row_us['userRemark'];
						
						if($id_status!=1){
							$id_autoriza = $row['id_autoriza'];
							//voy a buscar el nombre del usuario que genera la orden
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
                            <td align="right" colspan="3"><b>Fecha:</b></td>
							<td align="left"><?php echo $fecha; ?></td>
                        </tr>
                        <tr>
                            <td align="right"><b>Estado:</b></td>
							<td align="left"><?php echo $status; ?></td>
                            
                            <td align="right"><b>Plazo:</b></td>
							<td align="left"><?php echo $plazo; ?> d&iacute;as</td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" width="20%"><b>Proveedor:</b></td>
							<td colspan="3" align="left" valign="top">
								<?php echo $nom_proveedor; ?><br />
                                <?php echo $dir_proveedor; ?><br />
                                <?php echo $ciudad_proveedor.", ".$estado_proveedor; ?><br />
                                <?php echo "C.P. ".$cp_proveedor; ?><br />
                                <?php echo "Tel. ".$tel_proveedor; ?><br />
                            </td>
                        </tr>
                      
                        <tr>
                            <td align="right"><b>Tipo de I.V.A.:</b></td>
							<td align="left"><?php echo $tipo_iva; ?></td>
                            <td align="right"><b>Moneda:</b></td>
							<td align="left"><?php echo $moneda; ?></td>
                        </tr>
                        
                        <tr>
                            <td align="right" colspan="2">&nbsp;</td>
                            <td align="right"><b>Tipo de Cambio:</b></td>
							<td align="left"><?php echo $tipo_cambio; ?></td>
                        </tr>
                        
                        <?php 
						if($id_status!=1){
						?>	
                             <tr>
                                <td align="right"><b>Ordenado por:</b></td>
                                <td align="left"><?php echo $ordena; ?></td>
                                <td align="right"><b>Autorizado por:</b></td>
                                <td align="left"><?php echo $autoriza; ?></td>
                            </tr>
                        
						<?php
						}else{
						?>
                           <tr>
                                <td align="right"><b>Ordenado por:</b></td>
                                <td align="left" colspan="3"><?php echo $ordena; ?></td>
                            </tr>
						<?php 
						}
                            
							if($observaciones!=""){
                                echo "<tr>";
                                echo "<td align=\"right\"><b>Observaciones:</b></td>";
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
					//si el estatus es activo podemos seguir agregando insumos a la orden
				?>
                    
                        <tr>
                            <td align="center">
                            <form method="post" action="ficha_orden.php" name="regIns" onsubmit="return validarIns()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">	
                            	<table border="0" width="100%">	
                                    <tr>
                                    	<td align="right"><b>Insumo: </b></td>
                                    	<td align="left	" valign="middle" colspan="10">
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
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="5" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Largo: </b></td>
                                    	<td><input class="texto" type="text" id="largo" name="largo" size="5" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Ancho: </b></td>
                                    	<td><input class="texto" type="text" id="ancho" name="ancho" size="5" /><br /></td>
                                        
                                        </tr>
                                        
                                         <tr>
                                        <td align="right"><b>Observaciones: </b></td>
                                    	<td colspan="10"><textarea id="observaciones" name="observaciones" rows="2" cols="20"></textarea><br /></td>
                                        </tr>
                                        
                                        <tr>
                                        <td align="right"><b>Precio unitario: </b></td>
                                    	<td>$ <input class="texto" type="text" id="unitario" name="unitario" size="7" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Desc.: </b></td>
                                    	<td><input class="texto" type="text" id="desc" name="desc" size="2" value="0" /> %<br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Tipo I.V.A.: </b></td>
                                    	<td align="left" valign="middle">
                                        <select id="tiva" name="tiva">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_iva = $db->consulta("SELECT * FROM `clase_iva` ORDER BY id_clase ASC;");
													while ($row3 = $db->fetch_array($lista_iva))
													{
													  $id_clase = $row3['id_clase'];
													  $clase_iva = $row3['clase_iva'];
													  echo "<option value=\"".$id_clase."\">".$clase_iva."</option>";
													}
											?>
                                        </select></td>
                                        <td colspan="2">&nbsp;</td>
                                        <td><input class="submit_btn reset" type="submit" name="agregar1" id="agregar1" value="Agregar"/></td>
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
                                    $detalle = $db->consulta("SELECT * FROM `detalle_orden` WHERE id_orden='".$clave."'");
                                    $existe = $db->num_rows($detalle);
                                    if($existe<=0){
                                        echo "<p align=\"center\">No se han agregado insumos a la orden</p>";
                                    
                                    }else{
									?>
										<table id="tablesorter-ins" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No. de Parte</th>
                                                <th class="header">Cantidad</th>
                                                <th class="header">Insumo</th>
                                                <th class="header">Observaciones</th>
                                                <th class="header">Ancho</th>
                                                <th class="header">Largo</th>
                                                <th class="header">Tipo I.V.A.</th>
                                                <th class="header">Precio</th>
                                                <th class="header">Descuento</th>
                                                <th class="header">Total</th>
                                                
                                                <?php 
												if($id_status==1){
												//si el estatus es activo podemos modificar los conceptos
													echo "<th>Editar</th>
                                                	<th>Eliminar</th>";
												}
												if($id_status==2||$id_status==3){
												//si el estatus es autorizada podemos modificar los conceptos
													echo "<th>Recibido</th>
                                                	<th>Ajustado</th>";
													echo "<th>Recibir</th>
                                                	<th>Ajuste</th>";
													
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
											$recibido = $row['recibido'];
											$ajuste = $row['ajuste'];
											$ancho = $row['ancho'];
											$largo = $row['largo'];
											$unitario = $row['precio'];
											
											$descuento = $row['descuento'];
											
											$clase_iva = $row['id_clase_iva'];
											
											//consultare el nombre del tipo de iva
											$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
											 while ($row2 = $db->fetch_array($consulta_cl)){
												 $nom_clase = $row2['clase_iva'];
											}
											
											//consultare las entradas que hay en inventario del insumo
											$texto_rec = "SELECT sum( `unidades` ) as suma FROM `insumo_inventario` WHERE `id_orden` =$clave AND `id_insumo` =$id_insumo";
											$consulta_rec = $db->consulta($texto_rec);
											while ($row3 = $db->fetch_array($consulta_rec)){
												 $suma_recibido = $row3['suma'];
											}
											$suma_cantidades = $suma_recibido+$ajuste;
											
											
											$precio = $unitario * $cantidad;
											$monto_desc = $precio*$desc/100;
											$precio_desc = $precio-$monto_desc;
											$unitario_desc = $precio_desc/$cantidad;
											
											
											$sub_total = $sub_total + $precio_desc;
											
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
											//voy a acumular las cantidades para calcular el iva segun como sea la clase
											switch($clase_iva){
												case 1:
													$graba_normal = $graba_normal+$precio_desc;
												break;
												case 2:
													$graba_cero = $graba_cero+$precio_desc;
												break;
												case 3:
													$exento = $exento + $precio_desc;
												break;
											}
											
											//ahora imprimire la tabla
											
                                        
                                                       echo "<tr>
															 <td align=\"center\">".$num_parte."</td>
															 <td align=\"center\">".$cantidad."</td>
															 <td align=\"center\">".$nom_insumo."</td>
															 <td align=\"center\">".$desc."</td>
                                                             <td align=\"center\">".$ancho."</td>
															 <td align=\"center\">".$largo."</td>
															 <td align=\"center\">".$nom_clase."</td>
															 <td align=\"right\">$".number_format($unitario,2)."</td>
															 <td align=\"right\">".number_format($descuento,2)."%</td>
															 <td align=\"right\">$".number_format($precio_desc,2)."</td>";
														if($id_status==1){		 
														   echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_orden_ins.php?numero=$clave&key=$id_detalle\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
																 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_orden_ins.php?numero=$clave&key=$id_detalle\",\"$id_detalle\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
																 
														}
														if($id_status==2||$id_status==3){
															if($suma_cantidades != $cantidad){		 
															   echo "
																 <td align=\"center\">".$suma_recibido."</td>
																 <td align=\"center\">".$ajuste."</td>";
															   echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"recibe_insumo.php?numero=$clave&key=$id_detalle&id_insumo=$id_insumo&cantidad=$cantidad&precio=$unitario_desc\"><img src=\"../images/iconos/onebit_82.png\" width=\"24px\" align=\"center\"></a></td>
																	 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"http://localhost/imprenta/compras/ajustar_orden_ins.php?numero=$clave&key=$id_detalle\"><img src=\"../images/iconos/onebit_59.png\" width=\"24px\" align=\"center\"></a></td>";
															}else{
																$suma_completos = $suma_completos+1;
															   	echo "
																 <td align=\"center\">".$suma_recibido."</td>
																 <td align=\"center\">".$ajuste."</td>";
															   echo "<td class=\"listado\" align=\"center\">-</td>
															   <td class=\"listado\" align=\"center\">-</td>
																	 ";
															   
															   }
																 
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
                                
                                <?php 
								//calculo de los valores totalizados
								$iva = $graba_normal * ($tasa_iva/100);
								$total_orden= $sub_total + $iva;
								//convertire el total de la orden a texto
								$importe = number_format($total_orden,2);
										
								/*list($entero,$decimal) = explode(".",$importe);
								$valor =  numerotexto($entero);
								if ($decimal==""){
									$decimal = "00";
								}
								$letras = $valor ." ".$decimal."/100 ".$moneda;*/
								
								
								?>
                                
                                <table border="0" width="750px" align="center">	
                                    <tr>
                                        <td align="right" colspan="3" align="right" width="80%"><b>Subtotal:</b></td>
                                        <td align="right">$ <?php echo number_format($sub_total,2);?></td>
                                    </tr>
                                    <?php
										if($id_iva!=3){
											echo 
											"<tr>
												<td align=\"right\" colspan=\"3\" align=\"right\" width=\"80%\"><b>I.V.A. al $tasa_iva % :</b></td>
												<td align=\"right\">$".number_format($iva,2)."</td>
											</tr>";
										}
									
									?>
                                    
                                    <tr>
                                        <td align="right" colspan="3" align="right" width="80%"><b>Total:</b></td>
                                        <td align="right">$ <?php echo number_format($total_orden,2);?></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="right">
                            <?php 
                            if($id_status==1){
                                // si la orden esta activa damos la opcion de autorizarla
                                echo "<a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/compras/autorizar_orden.php?numero=$clave\",\"$clave\");>Autorizar Orden <img src=\"../images/iconos/onebit_48.png\" width=\"24px\" align=\"center\"></a><br>";
								
                                echo "<small><i>Una vez que se autorice la orden de compra no podr&aacute; realizar cambios</i></small><br>";
                                
                            }else{
                              	if ($suma_completos==$existe){
									//si todos los  insumos han entrado al almacen entonces cambio la orden al estado de terminada
									$query = "UPDATE orden_compra SET id_status='3' WHERE id_orden ='$clave';";
									$consulta = $db->consulta($query);
									echo "<br><b>La orden se ha completado y esta cerrada</b><br>";
								}
								//si el estado de la orden es autorizada damos la opcion de imprimir
								echo "<a href=\"ficha_orden_pdf.php?clave=$clave\"><i>Descargar formato de orden de compra</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
                                 
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