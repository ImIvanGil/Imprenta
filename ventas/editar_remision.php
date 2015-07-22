<?php include("../adminuser/adminpro_class.php");
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
	.ui-cliente {
		position: relative;
		display: inline-block;
	}
	.ui-cliente-toggle {
		position: absolute;
		top: 0;
		bottom: 0;
		margin-left: -1px;
		padding: 0;
		/* adjust styles for IE 6/7 */
		*height: 1.7em;
		*top: 0.1em;
	}
	.ui-cliente-input {
		margin: 0;
		padding: 0.3em;
		width:500px;
	}
	</style>
	<script>
	(function( $ ) {
		$.widget( "ui.cliente", {
			_create: function() {
				var input,
					self = this,
					select = this.element.hide(),
					selected = select.children( ":selected" ),
					value = selected.val() ? selected.text() : "",
					wrapper = this.wrapper = $( "<span>" )
						.addClass( "ui-cliente" )
						.insertAfter( select );

				input = $( "<input>" )
					.appendTo( wrapper )
					.val( value )
					.addClass( "ui-state-default ui-cliente-input" )
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
					.addClass( "ui-corner-right ui-cliente-toggle" )
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
		$( "#cliente" ).cliente();
		$( "#toggle" ).click(function() {
			$( "#cliente" ).toggle();
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
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="remisiones.php" class="current">Remisiones</a></li>
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
                
            <img src="../images/iconos/onebit_06.png" alt="image 3" />
            <a href="nueva_remision.php">Nueva Remision</a>
            
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
    
            <h2>Editar Remision</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.cliente.value == "-1") {
						alert ('Debe seleccionar un cliente');
						document.getElementById('cliente').focus();
						return false;
					}
					if (document.registro.fecha.value == "") {
						alert ('Debe eleccionar una fecha para la remision');
						document.getElementById('fecha').focus();
						return false;
					}
					if (document.registro.forma_pago.value == "-1") {
						alert ('Debe seleccionar una forma de pago');
						document.getElementById('forma_pago').focus();
						return false;
					}
					if (document.registro.status.value == "-1") {
						alert ('Debe seleccionar un status para la remision');
						document.getElementById('status').focus();
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
					if (document.registro.iva.value == "-1") {
						alert ('Debe seleccionar una tipo de IVA');
						document.getElementById('iva').focus();
						return false;
					}
					if (document.registro.plazo.value == "") {
						alert ('Debe escribir el plazo de pago en dias');
						document.getElementById('plazo').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT>  
            <?php
              $db = new MySQL();
              $clave = $_GET['numero'];
			  $remision = $db->consulta("SELECT * FROM `remision` WHERE id_remision='".$clave."'");
              while ($row = $db->fetch_array($remision))
			  {
				  $id_cliente = $row['id_cliente'];
				  
				  $id_forma_pago = $row['id_forma_pago'];
				  $id_metodo = $row['id_metodo_pago'];
				  $id_status = $row['id_status_remision'];
				  $id_moneda = $row['id_moneda'];
				  $iva = $row['id_iva'];
				  $plazo = $row['plazo_pago'];
				  $oc_cliente = $row['oc_cliente'];
				  
				  $observaciones = $row['observaciones'];
				  
				  $fecha = $row['fecha'];
				  
				  
				}
				
					
			?>
            
            <form method="post" enctype="multipart/form-data" action="registro_remision.php" name="registro" onSubmit="return validar()">
							<input type="hidden" name="operacion" id="operacion" value="editar">
                            <input type="hidden" name="clave" id="clave" value="<?php echo $clave;?>">	
                            <input type="hidden" name="status" id="status" value="<?php echo $id_status;?>">
                            
							<table align="center" width="550px" border="0px">
								
								<tr align="center"><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Datos de Registro </B></legend>
								    <table border="0" width="500px">	
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Cliente: </td>
                                    	<td align="left" valign="middle">
                                        <select id="cliente" name="cliente">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tam = $db->consulta("SELECT `id_cliente`,`clave`,`nombre` FROM `cliente`;");
													while ($row6 = $db->fetch_array($lista_tam))
													{
													  $id_cliente1 = $row6['id_cliente'];
													  $cve_cte = $row6['clave'];
													  $cte = utf8_decode($row6['nombre']);
														 if($id_cliente==$id_cliente1){
																  echo "<option value=\"".$id_cliente1."\"selected>".$cve_cte."-".$cte."</option>";
															}else{
																   echo "<option value=\"".$id_cliente1."\">".$cve_cte."-".$cte."</option>";
															}
													  
													}
											?>
                                        </select>
                                    </td>
                                    </tr>

								    <tr><td align="right"><span>*</span>Fecha:</td>
								    <td> <input class="texto" type="text" id="fecha" name="fecha" size="20" value="<?php echo $fecha;?>" /><br /></td></tr>
                                    
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
													  
														if($id_forma==$id_forma_pago){
															  echo "<option value=\"".$id_forma."\"selected>".$forma."</option>";
														}else{
															   echo "<option value=\"".$id_forma."\">".$forma."</option>";
														}

													  
													  
													  
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                     <tr>
                                    <td align="right"><span>*</span>M&eacute;todo de Pago: </td>
                                    	<td align="left" valign="middle">
                                        <select id="metodo_pago" name="metodo_pago">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_met = $db->consulta("SELECT * FROM `metodo_pago`;");
													while ($row6 = $db->fetch_array($lista_met))
													{
													  $id_met = $row6['id_metodo_pago'];
													  $met = $row6['metodo_pago'];
													  
													  if($id_met==$id_metodo){
															  echo "<option value=\"".$id_met."\"selected>".$met."</option>";
														}else{
															   echo "<option value=\"".$id_met."\">".$met."</option>";
														}
													  
													  
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                     <tr>
                                    <td align="right"><span>*</span>Tipo IVA: </td>
                                    	<td align="left" valign="middle">
                                        <select id="id_iva" name="id_iva">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_iva = $db->consulta("SELECT * FROM `iva`;");
													while ($row6 = $db->fetch_array($lista_iva))
													{
													  $id_iva2 = $row6['id_iva'];
													  $tipo_iva = $row6['tipo'];
													  
													  if($id_iva2==$iva){
															  echo "<option value=\"".$id_iva2."\"selected>".$tipo_iva."</option>";
														}else{
															   echo "<option value=\"".$id_iva2."\">".$tipo_iva."</option>";
														}
													  
													  
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
												$lista_mon = $db->consulta("SELECT * FROM `moneda`;");
													while ($row6 = $db->fetch_array($lista_mon))
													{
													  $id_mon = $row6['id_moneda'];
													  $mon = $row6['moneda'];
													  
													  if($id_mon==$id_moneda){
															  echo "<option value=\"".$id_mon."\"selected>".$mon."</option>";
														}else{
															   echo "<option value=\"".$id_mon."\">".$mon."</option>";
														}
													  
													  
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    <tr><td align="right"><span>*</span>Plazo de pago:</td>
								    <td> <input class="texto" type="text" id="plazo" name="plazo" size="10" value="<?php echo $plazo;?>" /><br /></td></tr>
                                    
                                    <tr><td align="right">Orden de compra del cliente:</td>
								    <td> <input class="texto" type="text" id="oc_cliente" name="oc_cliente" size="20" value="<?php echo $oc_cliente;?>" /><br /></td></tr>
                                    
                                    <tr>
                                    <td align="right" valign="top">Observaciones: </td>
                                    <td align="left" valign="middle"> <textarea id="observaciones" name="observaciones" rows="0" cols="0"><?php echo $observaciones;?></textarea><br />
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