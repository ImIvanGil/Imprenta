<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
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

<!--script src="../js/jquery-1.4.2.js"></script>
<script src="../js/jquery.ui.core.js"></script>
<script src="../js/jquery.ui.widget.js"></script>
<script src="../js/jquery.ui.datepicker.js"></script>
<script src="../js/valida/jquery.validate.js"></script>
<script src="../js/localization/messages_es.js"></script-->

<script src="../js/jquery-1.8.0.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.autocomplete.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>


	<style>
	.ui-proveedor {
		position: relative;
		display: inline-block;
	}
	.ui-proveedor-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* adjust styles for IE 6/7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.ui-proveedor-input {
		margin: 0;
		padding: 0.3em;
		width:500px;
	}
	</style>
	<script>
	(function( $ ) {
		$.widget( "ui.proveedor", {
			_create: function() {
				var input,
					self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "",
					wrapper = this.wrapper = $( "<span>" )
						.addClass( "ui-proveedor" )
						.insertAfter( select );

				input = $( "<input>" )
					.appendTo( wrapper )
					.val( value )
					.addClass( "ui-state-default ui-proveedor-input" )
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
					.addClass( "ui-corner-right ui-proveedor-toggle" )
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
		$( "#proveedor" ).proveedor();
		$( "#toggle" ).click(function() {
			$( "#proveedor" ).toggle();
		});
	});
	</script>

<script>
//muestra el calendario
$(function() {
	$( "#fecha" ).datepicker({
		changeMonth: true,
		changeYear: true,
		showOn: 'both',
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: false,
		selectOtherMonths: false

  
	});
	
});
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
            <li><a href="admin.php">Administraci&oacute;n</a></li>
            <li><a href="cargos.php" class="current">Cargos</a></li>
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
                
            <img src="../images/iconos/onebit_47.png" alt="image 3" />
            <a href="nuevo_cargo.php">Nuevo Cargo</a>
            
        </div>

         <div class="sb_box">
            <a href="#">Buscar</a>
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <form method="get" action="cargos.php" name="busqueda" id="busqueda" onSubmit="return validarBusqueda()"> 
            <input type="text" class="texto" width="30" name="parametro" id="parametro" />
            <input class="submit_btn reset" type="submit" value="Ir"/>
            </form>
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Nuevo Cargo</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.proveedor.value == "-1") {
						alert ('Debe seleccionar un proveedor');
						document.getElementById('proveedor').focus();
						return false;
					}
					if (document.registro.fecha.value == "") {
						alert ('Debe eleccionar una fecha para la factura');
						document.getElementById('fecha').focus();
						return false;
					}
					if (document.registro.forma_pago.value == "-1") {
						alert ('Debe seleccionar una forma de pago');
						document.getElementById('forma_pago').focus();
						return false;
					}
					if (document.registro.metodo_pago.value == "-1") {
						alert ('Debe seleccionar un metodo de pago');
						document.getElementById('metodo_pago').focus();
						return false;
					}
					if (document.registro.moneda.value == "-1") {
						alert ('Debe seleccionar una moneda');
						document.getElementById('moneda').focus();
						return false;
					}
					if (document.registro.plazo.value == "") {
						alert ('Debe escribir el plazo de pago en dias');
						document.getElementById('plazo').focus();
						return false;
					}
					if (document.registro.subtotal.value == "") {
						alert ('Debe escribir el subtotal del cargo');
						document.getElementById('subtotal').focus();
						return false;
					}
					if (document.registro.impuesto.value == "") {
						alert ('Debe escribir el impuesto del cargo');
						document.getElementById('impuesto').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            
            
            <form method="post" enctype="multipart/form-data" action="registro_cargo.php" name="registro" onSubmit="return validar()">
							<input type="hidden" name="operacion" id="operacion" value="registrar">	
                            <!--Envio en un hidden el numero de la empresa -->
                            <input type="hidden" name="empresa" id="empresa" value="1">	
                            <!--Declaro la factura activa al momento de registrarla -->
                            <input type="hidden" name="status" id="status" value="1">	
                           
							<table align="center" width="550px" border="0px">
								
								<tr align="center"><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Datos de Registro </B></legend>
								    <table border="0" width="500px">	
                                    
                                   
                                    <tr>
                                    <td align="right"><span>*</span>Proveedor: </td>
                                    	<td align="left" valign="middle">
                                        <select id="proveedor" name="proveedor">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tam = $db->consulta("SELECT `id_proveedor`,`nombre` FROM `proveedor`;");
													while ($row6 = $db->fetch_array($lista_tam))
													{
													  $id_proveedor = $row6['id_proveedor'];
													  $cte = utf8_decode($row6['nombre']);
													  echo "<option value=\"".$id_proveedor."\">".$cte."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>

								    <tr><td align="right"><span>*</span>Fecha:</td>
								    <td> <input class="texto" type="text" id="fecha" name="fecha" size="20" /><br /></td></tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Forma de pago: </td>
                                    	<td align="left" valign="middle">
                                        <select id="forma_pago" name="forma_pago">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_forma = $db->consulta("SELECT * FROM `forma_pago`;");
													while ($row6 = $db->fetch_array($lista_forma))
													{
													  $id_forma = $row6['id_forma_pago'];
													  $forma = $row6['forma_pago'];
													  echo "<option value=\"".$id_forma."\">".$forma."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                   <tr>
                                    <td align="right"><span>*</span>Metodo de pago: </td>
                                    	<td align="left" valign="middle">
                                        <select id="metodo_pago" name="metodo_pago">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_metodo = $db->consulta("SELECT * FROM `metodo_pago`;");
													while ($row6 = $db->fetch_array($lista_metodo))
													{
													  $id_metodo = $row6['id_metodo_pago'];
													  $metodo = $row6['metodo_pago'];
													  echo "<option value=\"".$id_metodo."\">".$metodo."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                   
                                    <tr>
                                    <td align="right"><span>*</span>Moneda: </td>
                                    	<td align="left" valign="middle">
                                        <select id="moneda" name="moneda">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_moneda = $db->consulta("SELECT * FROM `moneda`;");
													while ($row6 = $db->fetch_array($lista_moneda))
													{
													  $id_moneda = $row6['id_moneda'];
													  $moneda = $row6['moneda'];
													  echo "<option value=\"".$id_moneda."\">".$moneda."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Plazo de pago: </td>
                                    <td align="left" valign="middle"><input class="texto" type="text" id="plazo" name="plazo" size="10" /> d&iacute;as<br />
                                   </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right">Referencia: </td>
                                    <td align="left" valign="middle"><input class="texto" type="text" id="referencia" name="referencia" size="20" /><br />
                                   </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Sub-Total: </td>
                                    <td align="left" valign="middle"><input class="texto" type="text" id="subtotal" name="subtotal" size="20" /><br />
                                   </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Impuesto: </td>
                                    <td align="left" valign="middle"><input class="texto" type="text" id="impuesto" name="impuesto" size="20" /><br />
                                   </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right" valign="top">Observaciones: </td>
                                    <td align="left" valign="middle"> <textarea id="observaciones" name="observaciones" rows="0" cols="0"></textarea><br />
                                   </td>
                                    </tr>
                                   
                                    </table>
								  </fieldset>
								</td></tr>

								
								<tr><td colspan = "2"><small><span>*</span>Campos obligatorios</small></td></tr>

								<tr><td colspan="2"align="right"> 	   
								 <BR> <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 <input class="submit_btn reset" type="reset" value="Cancelar"/>
								</td></tr>
								
								</table>  
							</form>
        
        
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
?>