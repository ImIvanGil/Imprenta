<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../styles/ui-lightness/jquery.ui.all.css">


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

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE INSUMO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['editar'])) {
	$cve_orden = $_GET['numero'];
	$cve_det = $_GET['key'];
	$num_parte = $_GET['nparte'];
	$cant = $_GET['cant'];
	$cve_ins = $_GET['insumo'];	
	$largo = $_GET['largo'];
	$ancho = $_GET['ancho'];
	$observaciones = $_GET['observaciones'];
	$unitario = $_GET['unitario'];
	$descuento = $_GET['desc'];
	$tiva = $_GET['tiva'];
	
	
	//recibi las variables y ahora hare la consulta con el update
	$query = "UPDATE `imprenta`.`detalle_orden` SET `id_insumo` = '$cve_ins', `num_parte` = '$num_parte', `descripcion` = '$observaciones', `cantidad` = '$cant', `ancho` = '$ancho', `largo` = '$largo', `precio` = '$unitario', `descuento` = '$descuento' WHERE `detalle_orden`.`id_detalle` =$cve_det;";
	
	$consulta = $db->consulta($query);
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
    
            <h2>Editar Orden - Insumo</h2>
            <?php
				$db = new MySQL();
				$orden = $_GET['numero'];
				$detalle = $_GET['key'];
				
				//voy a consultar los datos del registro
				$consulta_reg = $db->consulta("SELECT *  FROM `detalle_orden` WHERE `id_detalle` = '".$detalle."';");
				while ($row2 = $db->fetch_array($consulta_reg)){
					$id_insumo = $row2['id_insumo'];
					$num_parte = $row2['num_parte'];
					$desc = $row2['descripcion'];
					$cantidad = $row2['cantidad'];
					$ancho = $row2['ancho'];
					$largo = $row2['largo'];
					$descuento = $row2['descuento'];
					$unitario = $row2['precio'];
					$unitario = number_format($unitario,2);
					
					$descuento = number_format($descuento,2);
					$id_clase_iva = $row2['id_clase_iva'];
					
				}
				
				?>
                <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos</B></legend>
				<form method="get" action="editar_orden_ins.php" name="regIns" onsubmit="return validarIns()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $orden;?>">
                            <input type="hidden" name="key" id="key" value="<?php echo $detalle;?>">	
                            	
                                
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
													  $id_ins = $row3['id_insumo'];
													  $nombre = $row3['nombre'];
													  $descripcion = $row3['descripcion'];
													  $dato = $nombre." - ".$descripcion;
													  
													  if($id_ins==$id_insumo){
															  echo "<option value=\"".$id_ins."\"selected>".utf8_decode($dato)."</option>";
														}else{
															 echo "<option value=\"".$id_ins."\"selected>".utf8_decode($dato)."</option>";
														}
													
													}
											?>
                                        </select></td>
                                        </tr>
                                        
                                        <tr>
                                        <td align="right"><b>Num. Parte: </b></td>
                                    	<td><input class="texto" type="text" id="nparte" name="nparte" size="10" value="<?php echo $num_parte;?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Cantidad: </b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="5" value="<?php echo $cantidad;?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Largo: </b></td>
                                    	<td><input class="texto" type="text" id="largo" name="largo" size="5" value="<?php echo $largo;?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Ancho: </b></td>
                                    	<td><input class="texto" type="text" id="ancho" name="ancho" size="5" value="<?php echo $ancho;?>"/><br /></td>
                                        
                                        </tr>
                                        
                                         <tr>
                                        <td align="right"><b>Observaciones: </b></td>
                                    	<td colspan="60"><textarea id="observaciones" name="observaciones" rows="2" cols="40"><?php echo $desc;?></textarea><br /></td>
                                        </tr>
                                        
                                        <tr>
                                        <td align="right"><b>Precio unitario: </b></td>
                                    	<td>$ <input class="texto" type="text" id="unitario" name="unitario" size="7" value="<?php echo $unitario;?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Desc.: </b></td>
                                    	<td><input class="texto" type="text" id="desc" name="desc" size="2" value="<?php echo $descuento;?>" /> %<br /></td>
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
													 if($id_clase==$id_clase_iva){
															  echo "<option value=\"".$id_clase."\"selected>".$clase_iva."</option>";
														}else{
															 echo "<option value=\"".$id_clase."\">".$clase_iva."</option>";
														}
													}
											?>
                                        </select></td>
                                        <td colspan="2">&nbsp;</td>
                                        <td><input class="submit_btn reset" type="submit" name="editar" id="editar" value="Guardar"/></td>
                                        </tr>
                                        
                                </table>
                                
                                
                            </form>
                            </fieldset>
                            </div>
				
	
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