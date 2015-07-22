<?php include("../adminuser/adminpro_class.php");
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


<script src="../js/jquery-1.8.0.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.autocomplete.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>

	<style>
	.ui-producto {
		position: relative;
		display: inline-block;
	}
	.ui-producto-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* adjust styles for IE 6/7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.ui-producto-input {
		margin: 0;
		padding: 0.3em;
		width:500px;
	}
	</style>
	<script>
	(function( $ ) {
		$.widget( "ui.producto", {
			_create: function() {
				var input,
					self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "",
					wrapper = this.wrapper = $( "<span>" )
						.addClass( "ui-producto" )
						.insertAfter( select );

				input = $( "<input>" )
					.appendTo( wrapper )
					.val( value )
					.addClass( "ui-state-default ui-producto-input" )
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
					.addClass( "ui-corner-right ui-producto-toggle" )
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
		$( "#producto" ).producto();
		$( "#toggle" ).click(function() {
			$( "#producto" ).toggle();
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
	if (confirm("ALERTA!!!\nEst\u00e1 seguro que desea certificar la nota de credito?\n una vez certificada no podra hacer cambios al comprobante")) location.replace(url);
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
			if (document.regProd.tiva.value == "-1") {
				alert ('Debe seleccionar la clase de iva que se va a calcular');
				document.getElementById('tiva').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 



<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['agregar1'])) {
	$cve_nota = $_GET['numero'];
	$cant = $_GET['cant'];
	$cve_prod = $_GET['producto'];
	$cons_prod = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$cve_prod."'");
	while ($row = $db->fetch_array($cons_prod))
	{
		$unitario = $row['precio'];
	}
	//$unitario = $_GET['unitario'];
	$tiva = $_GET['tiva'];
	
	//echo "el costo ".$costo."<br>";
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into detalle_nota_credito(id_nota, id_producto, unitario, cantidad,id_clase_iva) values('".$cve_nota."','".$cve_prod."','".$unitario."','".$cant."','".$tiva."');");  
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
            <li><a href="notas_credito.php" class="current">Notas de Cr&eacute;dito</a></li>
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
                
            <img src="../images/iconos/onebit_31.png" alt="image 3" />
            <a href="nueva_nota.php">Nueva Nota</a>
            
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
    
            <h2>Ficha de Datos de Nota de Cr&eacute;dito</h2>

			<?php
                $clave = $_GET['numero'];			
				//variables que acumularan los totales
				$sub_total = 0;
				$graba_normal = 0;
				$graba_cero = 0;
				$exento = 0;
				$iva = 0;
				$total_nota = 0;
				
                $nota_cred = $db->consulta("SELECT * FROM `nota_credito` WHERE id_nota='".$clave."'");
                $existe = $db->num_rows($nota_cred);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la nota de cr&eacute;dito con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($nota_cred))
                {
						$cuenta = $row['num_cuenta'];
						$id_empresa = $row['id_empresa'];
						//datos de la empresa
						$sql_empresa = "SELECT * FROM empresa where `id_empresa`='".$id_empresa."'";
						$consulta_empresa = $db->consulta($sql_empresa);
						while($row_emp = mysql_fetch_array($consulta_empresa)){
							$nom_emp=$row_emp['nombre'];
							$rfc_emp=$row_emp['rfc'];
						}
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
							$ciudad_cliente=$row_cliente['ciudad'];
							$estado_cliente=$row_cliente['estado'];
							
							if($numInt_cliente!=""){
								$dir_cliente= $calle_cliente." No.".$numero_cliente."Int.".$numInt_cliente." Col. ".$colonia_cliente;
							}else{
								$dir_cliente= $calle_cliente." No.".$numero_cliente." Col. ".$colonia_cliente;
							}
							
						
							$cp_cliente=$row_cliente['codigo_postal'];
							$tel_cliente=$row_cliente['telefono'];
						}
						
						$fecha = $row['fecha'];
						//voy a separar la fecha de la hora
						$fechas = explode("T", $fecha);
						$solo_fecha = $fechas[0];
						
						$id_forma = $row['id_forma_pago'];
						//forma de pago
						$sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
						$consulta_forma = $db->consulta($sql_forma);
						$row_forma = mysql_fetch_array($consulta_forma);
						$forma = $row_forma['forma_pago'];
						
						$id_status = $row['id_status_factura'];
						// estado de la nota
						$sql_status = "SELECT status FROM status_factura WHERE id_status_factura='".$id_status."'";
						$consulta_status = $db->consulta($sql_status);
						$row_status = mysql_fetch_array($consulta_status);
						$status = $row_status['status'];
						
						$id_met = $row['id_metodo_pago'];
						//metodo de pago
						$sql_met = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo_pago='".$id_met."'";
						$consulta_met = $db->consulta($sql_met);
						$row_met = mysql_fetch_array($consulta_met);
						$metodo = $row_met['metodo_pago'];
						
						$id_mon = $row['id_moneda'];
						//moneda
						$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
						$consulta_mon = $db->consulta($sql_mon);
						$row_mon = mysql_fetch_array($consulta_mon);
						$moneda = $row_mon['moneda'];
						
						$t_cambio = $row['tipo_cambio'];
						$observaciones = $row['observaciones'];
						
						
						$id_iva = $row['id_iva'];
						//voy a buscar la tasa del iva que vamos a usar
						$sql_tas = "SELECT * FROM iva WHERE id_iva='".$id_iva."'";
						$consulta_tas = $db->consulta($sql_tas);
						$row_tas = mysql_fetch_array($consulta_tas);
						$tasa_iva = $row_tas['tasa'];
						$tipo_iva = $row_tas['tipo'];
						
						//formare el nombre del archivo para futuras referencias.
						$file = "$rfc_emp"."_"."$clave";

			?>
            
            <table align="center" border="0" width="100%" cellspacing="0" cellpadding="0">
            	
                <tr><td align="left">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos de Nota</B></legend>
                    <table border="0" width="750px" align="center">	
                        <tr>
                            <td align="right"><b>Fecha:</b></td>
							<td align="left"><?php echo $solo_fecha; ?></td>
                            <td align="right"><b>Estado:</b></td>
							<td align="left"><?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" width="20%"><b>Cliente:</b></td>
							<td colspan="3" align="left" valign="top">
								<?php echo $cve_cte; ?><br />
								<?php echo $nom_cliente; ?><br />
                                <?php echo $rfc_cliente; ?><br />
                                <?php echo $dir_cliente; ?><br />
                                <?php echo $ciudad_cliente.", ".$estado_cliente; ?><br />
                                <?php echo "C.P. ".$cp_cliente; ?><br />
                                <?php echo "Tel. ".$tel_cliente; ?><br />
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><b>Forma de Pago:</b></td>
							<td align="left"><?php echo $forma; ?></td>
                            <td align="right"><b>M&eacute;todo de Pago:</b></td>
							<td align="left"><?php echo $metodo; ?></td>
                        </tr>
                        <tr>
                            <td align="right"><b>Moneda:</b></td>
							<td align="left"><?php echo $moneda; ?></td>
                            <td align="right"><b>Tipo de Cambio:</b></td>
							<td align="left"><?php echo number_format($t_cambio,2); ?></td>
                        </tr>
                        <tr>
                            <td align="right"><b>Tipo de I.V.A.:</b></td>
							<td align="left"><?php echo $tipo_iva; ?></td>
                            
						<?php 
							if($cuenta!=""){
                                echo "<td align=\"right\"><b>No. cuenta pago:</b></td>";
                                echo "<td align=\"left\">$cuenta</td>";
                            }else{
								echo "<td colspan=\"2\">&nbsp;</td>";								
							}
						?>
						
                            
                        </tr>
						<?php 
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
                  <!-- Recuadro con los datos de insumos necesarios para el producto -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Detalle</B></legend>
                    <table border="0" width="700px" align="center">	
               <?php 
				if($id_status==1){
					//si el estatus es activo podemos seguir agregando productos a la factura
				?>
                    
                        <tr>
                            <td align="center">
                            <form method="get" action="ficha_nota.php" name="regProd" onsubmit="return validarProd()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">	
                            	<table border="0" width="100%">	
                                    <tr>
                                    	<td align="right"><b>Producto: </b></td>
                                    	<td align="left	" valign="middle" colspan="9">
                                        <select id="producto" name="producto">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_prod = $db->consulta("SELECT * FROM `producto` ORDER BY id_producto ASC;");
													while ($row3 = $db->fetch_array($lista_prod))
													{
													  $id_prod = $row3['id_producto'];
													  $nombre = $row3['nombre'];
													  $precio = $row3['precio'];
													  $f_precio = number_format($precio,4);
													  $dato = $nombre." - $".$f_precio;
													  echo "<option value=\"".$id_prod."\">".$dato."</option>";
													}
											?>
                                        </select></td>
                                        </tr>
                                        <tr>
                                        <td align="right"><b>Cantidad: </b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="5" /><br /></td>
                                        <!--td>&nbsp;</td>
                                        <td align="right"><b>Unitario: </b></td>
                                    	<td><input class="texto" type="text" id="unitario" name="unitario" size="7" /><br /></td-->
                                        
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
                                        <td>&nbsp;</td>
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
                                    $detalle = $db->consulta("SELECT * FROM `detalle_nota_credito` WHERE id_nota='".$clave."'");
                                    $existe = $db->num_rows($detalle);
                                    if($existe<=0){
                                        echo "<p align=\"center\">No se han agregado productos a la nota de credito</p>";
                                    
                                    }else{
									?>
										<table id="tablesorter-ins" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Cantidad</th>
                                                <th class="header">Unidad</th>
                                                <th class="header">Concepto</th>
                                                <th class="header">Descripci&oacute;n</th>
                                                <th class="header">Tipo I.V.A.</th>
                                                <th class="header">Unitario</th>
                                                <th class="header">Precio Total</th>
                                                
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
											$id_detalle = $row['id_detalle_nota'];
											$id_producto = $row['id_producto'];
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
											
											//consultare el nombre del producto
											$consulta_prod = $db->consulta("SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';");
											 while ($row2 = $db->fetch_array($consulta_prod)){
												 $nom_producto = $row2['nombre'];
												 $desc_producto = $row2['descripcion'];
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
													$graba_normal = $graba_normal+$precio;
												break;
												case 2:
													$graba_cero = $graba_cero+$precio;
												break;
												case 3:
													$exento = $exento + $precio;
												break;
											}
											
											//ahora imprimire la tabla
											?>
                                                
            
													<?php 
                                        
                                                       echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
															 <td align=\"center\">".$cantidad."</td>
													  		 <td class=\"listado\" align=\"center\">".$unidad."</td>
                                                             <td>".$nom_producto."</td>
															 <td align=\"center\">".$desc_producto."</td>
															 <td align=\"center\">".$nom_clase."</td>
															 <td align=\"right\">$".number_format($unitario,4)."</td>
															 <td align=\"right\">$".number_format($precio,2)."</td>";
														if($id_status==1){		 
														   echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_nota_prod.php?numero=$clave&key=$id_detalle\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
																 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/ventas/borrar_nota_prod.php?numero=$clave&key=$id_detalle\",\"$id_detalle\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
																 
																 
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
								$total_nota = $sub_total + $iva;
								//convertire el total de la nota a texto
								$importe = number_format($total_nota,2);
										
								list($entero,$decimal) = explode(".",$importe);
								$valor =  numerotexto($entero);
								if ($decimal==""){
									$decimal = "00";
								}
								$letras = $valor ." ".$decimal."/100 ".$moneda;
								
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
                                        <td align="right">$ <?php echo number_format($total_nota,2);?></td>
                                    </tr>
                                </table>
                                
                            </td>
                        </tr>
                        
                        <tr>
                            <td align="right">
                            <?php 
                            if($id_status==1){
                                // si la factura esta activa damos la opcion de certificarla
                                echo "<a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/ventas/certificar_nota_win.php?numero=$clave\",\"$clave\");>Certificar Nota de Cr&eacute;dito <img src=\"../images/iconos/onebit_48.png\" width=\"24px\" align=\"center\"></a><br>";
								//echo "<a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/ventas/certificar_factura_win.php?numero=$clave\",\"$clave\");>Certificar Factura <img src=\"../images/iconos/onebit_48.png\" width=\"24px\" align=\"center\"></a><br>";
                                echo "<small><i>Esta es una versión previa de la nota de cr&eacute;dito sin validez fiscal, cuando complete todos los datos correctamente debe certificar la nota de cr&eacute;dito para que se registre debidamente ante el SAT. Luego de la certificaci&oacute;n no podr&aacute; realizar cambios</i></small><br>";
                                
                            }else{
                                if($id_status==2){
                                    
                                    //si el estado de la nota de credito es certificada damos la opcion de imprimir los comprobantes
                                    echo "<a href=\"descarga_cfdi_nota.php?file=$file\"><i>Descargar CFDI</i></a><img src=\"../images/iconos/onebit_11.png\" width=\"24px\" /><br>";
                                    echo "<a href=\"cfdi_pdf_nota.php?clave=$clave&letras=$letras\"><i>Descargar Representacion Impresa de CFDI</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
                                    
                                }else{
                                        if($id_status==3){
                                            //finalmente si la factura esta cancelada mostramos la leyenda y la opcion de descargarla
                                            echo "<font color=\"#FF0000\"><b>Esta nota de cr&eacute;dito ha sido cancelada ante el SAT</font></b><br>";
											echo "<a href=\"descarga_cfdi_nota.php?file=$file\"><i>Descargar CFDI</i></a><img src=\"../images/iconos/onebit_11.png\" width=\"24px\" /><br>";
                                            echo "<a href=\"cfdi_pdf_nota.php?clave=$clave&letras=$letras\"><i>Descargar Representacion Impresa de CFDI</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /><br>";
											 echo "<a href=\"descarga_acuse_nota.php?file=$file\"><i>Descargar Acuse de Cancelacion ante el SAT</i></a><img src=\"../images/iconos/onebit_26.png\" width=\"24px\" /><br>";
                                            
                                        }
                                    
                                    }
                                
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
?>