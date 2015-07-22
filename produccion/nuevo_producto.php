<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 

<!DOCTYPE html>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
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

<script src="../js/jquery-1.8.0.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.autocomplete.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>


<script type="text/javascript">
<!-- muestra y oculta campos en el formulario segun el select de linea de producto
function mostrar(){
	//si la opcion es flexo
	if(document.registro.linea.value=="1"){
		document.getElementById('papel1').style.display='block';
		document.getElementById('papel2').style.display='block';
		document.getElementById('laminado1').style.display='block';
		document.getElementById('laminado2').style.display='block';
		document.getElementById('barnizado1').style.display='block';
		document.getElementById('barnizado2').style.display='block';
		document.getElementById('etiquetadora1').style.display='block';
		document.getElementById('etiquetadora2').style.display='block';
		document.getElementById('rebobinado1').style.display='block';
		document.getElementById('rebobinado2').style.display='block';
		document.getElementById('dientes1').style.display='block';
		document.getElementById('dientes2').style.display='block';
		document.getElementById('repeticionese1').style.display='block';
		document.getElementById('repeticionese2').style.display='block';
		document.getElementById('repeticionesd1').style.display='block';
		document.getElementById('repeticionesd2').style.display='block';
		document.getElementById('prensa1').style.display='none';
		document.getElementById('prensa2').style.display='none';
		document.getElementById('prensa').value="-1";
		document.getElementById('color1').style.display='none';
		document.getElementById('color2').style.display='none';
		document.getElementById('color').value="";
		document.getElementById('exta1').style.display='none';
		document.getElementById('exta2').style.display='none';
		document.getElementById('ext_ancho').value="";
		document.getElementById('extl1').style.display='none';
		document.getElementById('extl2').style.display='none';
		document.getElementById('ext_largo').value="";
		document.getElementById('extl1').style.display='none';
		document.getElementById('extl2').style.display='none';
		document.getElementById('ext_largo').value="";
		document.getElementById('grapado1').style.display='none';
		document.getElementById('grapado2').style.display='none';
		document.getElementById('grapado').checked = false;
		document.getElementById('pegado1').style.display='none';
		document.getElementById('pegado2').style.display='none';
		document.getElementById('pegado').value = '-1';
		document.getElementById('marginal1').style.display='none';
		document.getElementById('marginal2').style.display='none';
		document.getElementById('marginal').value="";
		document.getElementById('prefijo1').style.display='none';
		document.getElementById('prefijo2').style.display='none';
		document.getElementById('prefijo').value="";
		document.getElementById('sufijo1').style.display='none';
		document.getElementById('sufijo2').style.display='none';
		document.getElementById('sufijo').value="";
		document.getElementById('corea1').style.display='block';
		document.getElementById('corea2').style.display='block';
		document.getElementById('cored1').style.display='block';
		document.getElementById('cored2').style.display='block';
		document.getElementById('perforacion1').style.display='none';
		document.getElementById('perforacion2').style.display='none';
		document.getElementById('perforacion').checked = false;
		document.getElementById('engargolado1').style.display='none';
		document.getElementById('engargolado2').style.display='none';
		document.getElementById('engargolado').checked = false;
		document.getElementById('encuadernado1').style.display='none';
		document.getElementById('encuadernado2').style.display='none';
		document.getElementById('encuadernado').checked = false;
		document.getElementById('impresion1').style.display='none';
		document.getElementById('impresion2').style.display='none';
		document.getElementById('impresion').value = '1';
		
		//document.getElementById('prensa').setAttribute(value,'-1');
		
	}else{
		if(document.registro.linea.value=="2"){
			//si la linea es offset
			document.getElementById('prensa1').style.display='block';
			document.getElementById('prensa2').style.display='block';
			document.getElementById('color1').style.display='block';
			document.getElementById('color2').style.display='block';
			document.getElementById('exta1').style.display='block';
			document.getElementById('exta2').style.display='block';
			document.getElementById('extl1').style.display='block';
			document.getElementById('extl2').style.display='block';
			document.getElementById('extl1').style.display='block';
			document.getElementById('extl2').style.display='block';
			document.getElementById('grapado1').style.display='block';
			document.getElementById('grapado2').style.display='block';			
			document.getElementById('grapado').checked = false;
			document.getElementById('pegado1').style.display='block';
			document.getElementById('pegado2').style.display='block';
			document.getElementById('marginal1').style.display='block';
			document.getElementById('marginal2').style.display='block';
			document.getElementById('prefijo1').style.display='block';
			document.getElementById('prefijo2').style.display='block';
			document.getElementById('sufijo1').style.display='block';
			document.getElementById('sufijo2').style.display='block';
			document.getElementById('perforacion1').style.display='block';
			document.getElementById('perforacion2').style.display='block';			
			document.getElementById('perforacion').checked = false;
			document.getElementById('engargolado1').style.display='block';
			document.getElementById('engargolado2').style.display='block';			
			document.getElementById('engargolado').checked = false;
			document.getElementById('encuadernado1').style.display='block';
			document.getElementById('encuadernado2').style.display='block';			
			document.getElementById('encuadernado').checked = false;
			document.getElementById('impresion1').style.display='block';
			document.getElementById('impresion2').style.display='block';
			document.getElementById('corea1').style.display='none';
			document.getElementById('corea2').style.display='none';
			document.getElementById('cored1').style.display='none';
			document.getElementById('cored2').style.display='none';
			document.getElementById('corea').value='';
			document.getElementById('cored').value='';
			document.getElementById('papel1').style.display='none';
			document.getElementById('papel2').style.display='none';
			document.getElementById('laminado1').style.display='none';
			document.getElementById('laminado2').style.display='none';
			document.getElementById('barnizado1').style.display='none';
			document.getElementById('barnizado2').style.display='none';
			document.getElementById('etiquetadora1').style.display='none';
			document.getElementById('etiquetadora2').style.display='none';
			document.getElementById('rebobinado1').style.display='none';
			document.getElementById('rebobinado2').style.display='none';
			document.getElementById('dientes1').style.display='none';
			document.getElementById('dientes2').style.display='none';
			document.getElementById('repeticionese1').style.display='none';
			document.getElementById('repeticionese2').style.display='none';
			document.getElementById('repeticionesd1').style.display='none';
			document.getElementById('repeticionesd2').style.display='none';
			document.getElementById('dientes').value='';
			document.getElementById('repeticionese').value='';
			document.getElementById('repeticionesd').value='';
			document.getElementById('papel').value="-1";
			document.getElementById('laminado').value="0";
			document.getElementById('barnizado').value="0";
			document.getElementById('etiquetadora').value="-1";
		}else{
			//si no se ha selesccionado ni offset ni flexo
			document.getElementById('papel').value="-1";
			document.getElementById('laminado').value="-1";
			document.getElementById('barnizado').value="-1";
			document.getElementById('etiquetadora').value="-1";
			document.getElementById('prensa').value="-1";
			document.getElementById('prensa1').style.display='none';
			document.getElementById('prensa2').style.display='none';
			document.getElementById('color').value="";
			document.getElementById('color1').style.display='none';
			document.getElementById('color2').style.display='none';
			document.getElementById('papel1').style.display='none';
			document.getElementById('papel2').style.display='none';
			document.getElementById('laminado1').style.display='none';
			document.getElementById('laminado2').style.display='none';
			document.getElementById('barnizado1').style.display='none';
			document.getElementById('barnizado2').style.display='none';
			document.getElementById('etiquetadora1').style.display='none';
			document.getElementById('etiquetadora2').style.display='none';
			document.getElementById('rebobinado1').style.display='none';
			document.getElementById('rebobinado2').style.display='none';
			document.getElementById('exta1').style.display='none';
			document.getElementById('exta2').style.display='none';
			document.getElementById('ext_ancho').value="";
			document.getElementById('extl1').style.display='none';
			document.getElementById('extl2').style.display='none';
			document.getElementById('ext_largo').value="";
			document.getElementById('extl1').style.display='none';
			document.getElementById('extl2').style.display='none';
			document.getElementById('ext_largo').value="";
			document.getElementById('grapado1').style.display='none';
			document.getElementById('grapado2').style.display='none';
			document.getElementById('grapado').checked = false;
			document.getElementById('pegado1').style.display='none';
			document.getElementById('pegado2').style.display='none';
			document.getElementById('pegado').value = '-1';
			document.getElementById('marginal1').style.display='none';
			document.getElementById('marginal2').style.display='none';
			document.getElementById('marginal').value="";
			document.getElementById('prefijo1').style.display='none';
			document.getElementById('prefijo2').style.display='none';
			document.getElementById('prefijo').value="";
			document.getElementById('sufijo1').style.display='none';
			document.getElementById('sufijo2').style.display='none';
			document.getElementById('sufijo').value="";
			document.getElementById('corea1').style.display='none';
			document.getElementById('corea2').style.display='none';
			document.getElementById('cored1').style.display='none';
			document.getElementById('cored2').style.display='none';
			document.getElementById('corea').value="";
			document.getElementById('cored').value="";
			document.getElementById('perforacion1').style.display='none';
			document.getElementById('perforacion2').style.display='none';
			document.getElementById('perforacion').checked = false;
			document.getElementById('engargolado1').style.display='none';
			document.getElementById('engargolado2').style.display='none';
			document.getElementById('engargolado').checked = false;
			document.getElementById('encuadernado1').style.display='none';
			document.getElementById('encuadernado2').style.display='none';
			document.getElementById('encuadernado').checked = false;
			document.getElementById('impresion1').style.display='none';
			document.getElementById('impresion2').style.display='none';
			document.getElementById('impresion').value = '1';
			document.getElementById('dientes1').style.display='none';
			document.getElementById('dientes2').style.display='none';
			document.getElementById('repeticionese1').style.display='none';
			document.getElementById('repeticionese2').style.display='none';
			document.getElementById('repeticionesd1').style.display='none';
			document.getElementById('repeticionesd2').style.display='none';
			document.getElementById('dientes').value='';
			document.getElementById('repeticionese').value='';
			document.getElementById('repeticionesd').value='';
			
			
			}
		}
	
	
	}
</script>
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
            <li><a href="productos.php" class="current">Productos</a></li>
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
            <a href="nuevo_producto.php">Nuevo Producto</a>
            
        </div>

        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Nuevo Producto</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.cve.value == "") {
						alert ('Debe escribir una clave para identificar al producto');
						document.getElementById('cve').focus();
						return false;
					}
					if (document.registro.cliente.value == "-1") {
						alert ('Debe seleccionar el cliente');
						document.getElementById('cliente').focus();
						return false;
					}
					if (document.registro.nombre.value == "") {
						alert ('Debe escribir el nombre del producto');
						document.getElementById('nombre').focus();
						return false;
					}
					if (document.registro.precio.value == "") {
						alert ('Debe llenar el precio del producto');
						document.getElementById('precio').focus();
						return false;
					}
					if (document.registro.descripcion.value == "") {
						alert ('Debe escribir una descripcion del producto');
						document.getElementById('descripcion').focus();
						return false;
					}
					if (document.registro.dado.value == "") {
						alert ('Debe llenar el valor de dado');
						document.getElementById('dado').focus();
						return false;
					}
					if (document.registro.largo.value == "") {
						alert ('Debe escribir el valor de largo');
						document.getElementById('largo').focus();
						return false;
					}
					if (document.registro.ancho.value == "") {
						alert ('Debe escribir el valor de ancho');
						document.getElementById('ancho').focus();
						return false;
					}
					if (document.registro.cant.value == "") {
						alert ('Debe escribir el valor de cantidad por unidad');
						document.getElementById('cant').focus();
						return false;
					}
					if (document.registro.linea.value == "-1") {
						alert ('Debe seleccionar la linea de producto');
						document.getElementById('linea').focus();
						return false;
					}
					// SI LA LINEA ES FLEXO VOY A VALIDARLOS CAMPOS QUE CORRESPONDEN
					if (document.registro.linea.value == "1") {
						if (document.registro.papel.value == "-1") {
							alert ('Debe seleccionar el tipo de papel');
							document.getElementById('papel').focus();
							return false;
						}
						if (document.registro.laminado.value == "0") {
							alert ('Debe seleccionar el tipo de laminado');
							document.getElementById('laminado').focus();
							return false;
						}
						if (document.registro.barnizado.value == "0") {
							alert ('Debe seleccionar el tipo de barnizado');
							document.getElementById('barnizado').focus();
							return false;
						}
						if (document.registro.etiquetadora.value == "-1") {
							alert ('Debe seleccionar la informacion de etiquetadora');
							document.getElementById('etiquetadora').focus();
							return false;
						}
						if (document.registro.rebobinado.value == "-1") {
							alert ('Debe seleccionar la informacion de rebobinado');
							document.getElementById('rebobinado').focus();
							return false;
						}
						if (document.registro.dientes.value == "") {
							alert ('Debe llenar la informacion de dientes del plate roll');
							document.getElementById('dientes').focus();
							return false;
						}
						if (document.registro.repeticionese.value == "") {
							alert ('Debe llenar el numero de repeticiones al eje');
							document.getElementById('repeticionese').focus();
							return false;
						}
						if (document.registro.repeticionesd.value == "") {
							alert ('Debe llenar el numero de repeticiones al desarrollo');
							document.getElementById('repeticionesd').focus();
							return false;
						}
						if (document.registro.corea.value == "") {
							alert ('Debe llenar el valor de ancho core');
							document.getElementById('corea').focus();
							return false;
						}
						if (document.registro.cored.value == "") {
							alert ('Debe llenar el valor de diametro del core');
							document.getElementById('cored').focus();
							return false;
						}
					}
					
					//SI LA LINEA ES OFFSET, VALIDO LOS CAMPOS
					if (document.registro.linea.value == "2") {
						if (document.registro.prensa.value == "-1") {
							alert ('Debe seleccionar el tipo de prensa');
							document.getElementById('prensa').focus();
							return false;
						}
						if (document.registro.color.value == "") {
							alert ('Debe llenar los datos de pantone');
							document.getElementById('color').focus();
							return false;
						}
						if (document.registro.ext_ancho.value == "") {
							alert ('Debe llenar el valor de extendido ancho');
							document.getElementById('ext_ancho').focus();
							return false;
						}
						if (document.registro.ext_largo.value == "") {
							alert ('Debe llenar el valor de extendido largo');
							document.getElementById('ext_largo').focus();
							return false;
						}
						if (document.registro.marginal.value == "") {
							alert ('Debe llenar el valor de marginal');
							document.getElementById('marginal').focus();
							return false;
						}
						if (document.registro.prefijo.value == "") {
							alert ('Debe llenar el valor de prefijo');
							document.getElementById('prefijo').focus();
							return false;
						}
						if (document.registro.sufijo.value == "") {
							alert ('Debe llenar el valor de sufijo');
							document.getElementById('sufijo').focus();
							return false;
						}
						if (document.registro.impresion.value == "-1") {
							alert ('Debe seleccionar el tipo de impresion');
							document.getElementById('impresion').focus();
							return false;
						}
						
					}
					
					if (document.registro.status.value == "-1") {
						alert ('Debe seleccionar el status de producto');
						document.getElementById('status').focus();
						return false;
					}
					if (document.registro.unidad.value == "-1") {
						alert ('Debe seleccionar la unidad de medida');
						document.getElementById('unidad').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            
            
           <form method="post" enctype="multipart/form-data" action="registro_producto.php" name="registro" onSubmit="return validar()">
							<input type="hidden" name="operacion" id="operacion" value="registrar">	
							<table align="center" width="550px" border="0px">
								
								<tr><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Datos de Registro </B></legend>
								    <table border="0">	
                                    <tr><td align="right"><span>*</span>Clave:</td>
								    <td> <input class="texto" type="text" id="cve" name="cve" size="8" maxlength="8" required/><br /></td></tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Cliente: </td>
                                    	<td align="left" valign="middle">
                                        <select id="cliente" name="cliente">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tam = $db->consulta("SELECT `id_cliente`,`clave`,`nombre` FROM `cliente`;");
													while ($row6 = $db->fetch_array($lista_tam))
													{
													  $id_cliente = $row6['id_cliente'];
													  $cve_cte = $row6['clave'];
													  $cte = utf8_decode($row6['nombre']);
													  echo "<option value=\"".$id_cliente."\">".$cve_cte."-".$cte."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>


								    <tr><td align="right"><span>*</span>Nombre Producto:</td>
								    <td> <input class="texto" type="text" id="nombre" name="nombre" size="60" /><br /></td></tr>
                                    <tr><td align="right"><span>*</span>Precio: $</td>
								    <td> <input class="texto" type="text" id="precio" name="precio" size="8" maxlength="8" /><br /></td></tr>
                                    <tr><td align="right" valign="top"><span>*</span>Descripci&oacute;n:</td>
								    <td> <textarea id="descripcion" class="required" cols="0" rows="0" name="descripcion"></textarea><br /></td></tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Dado: </td>
                                    	<td align="left" valign="middle">
                                        <input class="texto" type="text" id="dado" name="dado" size="20" required/>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Largo: </td>
                                    	<td align="left" valign="middle">
                                        <input class="texto" type="text" id="largo" name="largo" size="20" />
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Ancho: </td>
                                    	<td align="left" valign="middle">
                                        <input class="texto" type="text" id="ancho" name="ancho" size="20" />
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Cantidad por unidad: </td>
                                    	<td align="left" valign="middle">
                                        <input class="texto" type="text" id="cant" name="cant" size="20" />
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right">Precorte: </td>
                                    	<td align="left" valign="middle">
                                        <input type="checkbox" id="precorte" name="precorte" value="1" />
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Tinta: </td>
                                    	<td align="left" valign="middle">
                                        <select id="tinta" name="tinta">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tin = $db->consulta("SELECT `id_tinta`,`tinta` FROM `tinta`;");
													while ($row6 = $db->fetch_array($lista_tin))
													{
													  $id_tin = $row6['id_tinta'];
													  $tin = $row6['tinta'];
													  echo "<option value=\"".$id_tin."\">".$tin."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><span>*</span>L&iacute;nea de Producto: </td>
                                    	<td align="left" valign="middle">
                                        <select id="linea" name="linea" onchange="mostrar();">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tin = $db->consulta("SELECT * FROM `linea_producto` WHERE id_linea<=2;");
													while ($row6 = $db->fetch_array($lista_tin))
													{
													  $id_lin = $row6['id_linea'];
													  $linea = $row6['linea'];
													  echo "<option value=\"".$id_lin."\">".$linea."</option>";
													}
											?>
                                        </select>
                                    </td>
                                     <tr>
                                    
                                    <tr>
                                    <td align="right"><div id="prensa1" style="display:none;"><span>*</span>Tipo de prensa: </div></td>
                                    	<td align="left" valign="middle"><div id="prensa2" style="display:none;">
                                        <select id="prensa" name="prensa">
                                            <option value="-1">Seleccione una opci&oacute;n</option>
                                            <option value="1">Abdick</option>
                                            <option value="2">Forma Continua</option>
                                            <option value="3">Full Color</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                   <tr>
                                    <td align="right"><div id="color1" style="display:none;"><span>*</span>Pantone: </div></td>
                                    	<td align="left" valign="middle"><div id="color2" style="display:none;">
                                        <textarea id="color" class="required" cols="0" rows="0" name="color"></textarea><br /></div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="exta1" style="display:none;"><span>*</span>Extendido ancho: </div></td>
                                    	<td align="left" valign="middle"><div id="exta2" style="display:none;">
                                        <input class="texto" type="text" id="ext_ancho" name="ext_ancho" size="20" /><br /></div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="extl1" style="display:none;"><span>*</span>Extendido largo: </div></td>
                                    	<td align="left" valign="middle"><div id="extl2" style="display:none;">
                                        <input class="texto" type="text" id="ext_largo" name="ext_largo" size="20" /><br /></div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="grapado1" style="display:none;">Grapado: </div></td>
                                    	<td align="left" valign="middle"><div id="grapado2" style="display:none;">
                                        <input type="checkbox" id="grapado" name="grapado" value="1"/></div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="pegado1" style="display:none;"><span>*</span>Pegado: </div></td>
                                    	<td align="left" valign="middle"><div id="pegado2" style="display:none;">
                                        <select id="pegado" name="pegado">
                                            <option value="-1">Ninguno</option>
                                            <option value="1">Quimico</option>
                                            <option value="2">Bond</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="marginal1" style="display:none;"><span>*</span>Marginal: </div></td>
                                    	<td align="left" valign="middle"><div id="marginal2" style="display:none;">
                                        <input class="texto" type="text" id="marginal" name="marginal" size="60" /><br /></div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="prefijo1" style="display:none;"><span>*</span>Prefijo: </div></td>
                                    	<td align="left" valign="middle"><div id="prefijo2" style="display:none;">
                                        <input class="texto" type="text" id="prefijo" name="prefijo" size="20" /><br /></div>
                                    </td>
                                    </tr>

                                    
                                    <tr>
                                    <td align="right"><div id="sufijo1" style="display:none;"><span>*</span>Sufijo: </div></td>
                                    	<td align="left" valign="middle"><div id="sufijo2" style="display:none;">
                                        <input class="texto" type="text" id="sufijo" name="sufijo" size="20" /><br /></div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="perforacion1" style="display:none;">Perforacion: </div></td>
                                    	<td align="left" valign="middle"><div id="perforacion2" style="display:none;">
                                        <input type="checkbox" id="perforacion" name="perforacion" value="1"/></div>
                                    </td>
                                    </tr>
                                    
                                     <tr>
                                    <td align="right"><div id="engargolado1" style="display:none;">Engargolado: </div></td>
                                    	<td align="left" valign="middle"><div id="engargolado2" style="display:none;">
                                        <input type="checkbox" id="engargolado" name="engargolado" value="1" /></div>
                                    </td>
                                    </tr>
                                    
                                     <tr>
                                    <td align="right"><div id="encuadernado1" style="display:none;">Encuadernado: </div></td>
                                    	<td align="left" valign="middle"><div id="encuadernado2" style="display:none;">
                                        <input type="checkbox" id="encuadernado" name="encuadernado" value="1"/></div>
                                    </td>
                                    </tr>
                                    
                                    
                                     <tr>
                                    <td align="right"><div id="impresion1" style="display:none;"><span>*</span>Impresion: </div></td>
                                    	<td align="left" valign="middle"><div id="impresion2" style="display:none;">
                                        <select id="impresion" name="impresion">
                                            <option value="-1">Seleccione</option>
                                            <option value="1">Frente</option>
                                            <option value="2">Reverso</option>
                                            <option value="3">Ambos lados</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    
                                  
                                    
                                    
                                    <tr>
                                    <td align="right"><div id="papel1" style="display:none;"><span>*</span>Tipo de papel: </div></td>
                                    	<td align="left" valign="middle"><div id="papel2" style="display:none;">
                                        <select id="papel" name="papel">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_pap = $db->consulta("SELECT * FROM `insumo` where `id_clase` = '3' ;");
													while ($row7 = $db->fetch_array($lista_pap))
													{
													  $id_tipo_pap = $row7['id_insumo'];
													  $papel = utf8_decode($row7['nombre']);
													  echo "<option value=\"".$id_tipo_pap."\">".$papel."</option>";
													}
											?>
                                        </select>
                                        </div>
                                    </td>
                                    
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="laminado1" style="display:none;"><span>*</span>Laminado: </div></td>
                                    	<td align="left" valign="middle"><div id="laminado2" style="display:none;">
                                        <select id="laminado" name="laminado">
                                            <option value="0">Seleccione</option>
                                            <option value="-1">No</option>
                                            <option value="1">si</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="barnizado1" style="display:none;"><span>*</span>Barnizado: </div></td>
                                    	<td align="left" valign="middle"><div id="barnizado2" style="display:none;">
                                        <select id="barnizado" name="barnizado">
                                            <option value="0">Seleccione</option>
                                            <option value="-1">Ninguno</option>
                                            <option value="1">UV</option>
                                            <option value="2">Antiest&aacute;tico</option>
                                            <option value="3">Base Agua</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                      <tr>
                                    <td align="right"><div id="etiquetadora1" style="display:none;"><span>*</span>Etiquetadora: </div></td>
                                    	<td align="left" valign="middle"><div id="etiquetadora2" style="display:none;">
                                        <select id="etiquetadora" name="etiquetadora">
                                            <option value="-1">Seleccione una opcion</option>
                                            <option value="1">Si</option>
                                            <option value="2">No</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                      <tr>
                                    <td align="right"><div id="rebobinado1" style="display:none;"><span>*</span>Rebobinado: </div></td>
                                    	<td align="left" valign="middle"><div id="rebobinado2" style="display:none;">
                                        <select id="rebobinado" name="rebobinado">
                                            <option value="-1">Seleccione una opcion</option>
                                            <option value="1">Izquierdo</option>
                                            <option value="2">Derecho</option>
                                        </select>
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="dientes1" style="display:none;"><span>*</span>Dientes del plate roll: </div></td>
                                    	<td align="left" valign="middle"><div id="dientes2" style="display:none;">
                                        <input class="texto" type="text" id="dientes" name="dientes" size="20" />
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="repeticionese1" style="display:none;"><span>*</span>No. de Repeticiones al eje: </div></td>
                                    	<td align="left" valign="middle"><div id="repeticionese2" style="display:none;">
                                        <input class="texto" type="text" id="repeticionese" name="repeticionese" size="20" />
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="repeticionesd1" style="display:none;"><span>*</span>No. de Repeticiones al desarrollo: </div></td>
                                    	<td align="left" valign="middle"><div id="repeticionesd2" style="display:none;">
                                        <input class="texto" type="text" id="repeticionesd" name="repeticionesd" size="20" />
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    
                                    
                                    
                                    <tr>
                                    <td align="right"><div id="cored1" style="display:none;"><span>*</span>Diametro del core: </div></td>
                                    	<td align="left" valign="middle"><div id="cored2" style="display:none;">
                                        <input class="texto" type="text" id="cored" name="cored" size="20" />
                                        </div>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right"><div id="corea1" style="display:none;"><span>*</span>Ancho del core: </div></td>
                                    	<td align="left" valign="middle"><div id="corea2" style="display:none;">
                                        <input class="texto" type="text" id="corea" name="corea" size="20" />
                                        </div>
                                    </td>
                                    </tr>
                                   
                                    
                                    
                                    
                                    
                                    <td align="right"><span>*</span>Status de Producto: </td>
                                    	<td align="left" valign="middle">
                                        <select id="status" name="status">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tin = $db->consulta("SELECT * FROM `status_producto`;");
													while ($row6 = $db->fetch_array($lista_tin))
													{
													  $id_sta = $row6['id_status_producto'];
													  $status = $row6['status_producto'];
													  echo "<option value=\"".$id_sta."\">".$status."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    
                                    </tr>
                                    
                                     <tr>
                                    <td align="right"><span>*</span>Unidad de medida: </td>
                                    	<td align="left" valign="middle">
                                        <select id="unidad" name="unidad">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_tin = $db->consulta("SELECT * FROM `unidades`;");
													while ($row6 = $db->fetch_array($lista_tin))
													{
													  $id_unidad = $row6['id_unidad'];
													  $unidad = $row6['unidad'];
													  echo "<option value=\"".$id_unidad."\">".$unidad."</option>";
													}
											?>
                                        </select>
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