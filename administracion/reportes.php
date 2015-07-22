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
	$( "#finicio" ).datepicker({
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
	
	$( "#ffin" ).datepicker({
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

<script type="text/javascript">
<!-- muestra y oculta campos en el formulario segun el select
function mostrar(){
//Si la opcion con id Conocido_1 (dentro del documento > formulario con name fcontacto >     y a la vez dentro del array de Conocido) esta activada
if (document.reporte.tipo.value == "proveedor") {
	//muestra la fecha de baja cuando el status es suspendido
	document.getElementById('div_proveedor1').style.display='block';
	document.getElementById('div_proveedor2').style.display='block';
	document.getElementById('div_vendedor1').style.display='none';
	document.getElementById('div_producto1').style.display='none';
	document.getElementById('div_vendedor2').style.display='none';
	document.getElementById('div_producto2').style.display='none';
	document.getElementById('proveedor').focus();
	document.getElementById('producto').value == "-1";
	document.getElementById('vendedor').value == "-1";
} else {
	if(document.reporte.tipo.value == "vendedor"){
		//muestra la fecha de baja cuando el status es vetado
		document.getElementById('div_vendedor1').style.display='block';
		document.getElementById('div_vendedor2').style.display='block';
		document.getElementById('div_proveedor1').style.display='none';
		document.getElementById('div_proveedor2').style.display='none';
		document.getElementById('div_producto1').style.display='none';
		document.getElementById('div_producto1').style.display='none';
		document.getElementById('vendedor').focus();
		document.getElementById('producto').value == "-1";
		document.getElementById('proveedor').value == "-1";
	}else{
		if(document.reporte.tipo.value == "producto"){
		//muestra la fecha de baja cuando el status es vetado
		document.getElementById('div_producto1').style.display='block';
		document.getElementById('div_producto2').style.display='block';
		document.getElementById('div_proveedor1').style.display='none';
		document.getElementById('div_proveedor2').style.display='none';
		document.getElementById('div_vendedor1').style.display='none';
		document.getElementById('div_vendedor2').style.display='none';
		document.getElementById('producto').focus();
		document.getElementById('vendedor').value == "-1";
		document.getElementById('proveedor').value == "-1";
		}else{
				document.getElementById('div_proveedor1').style.display='none';
				document.getElementById('div_proveedor2').style.display='none';
				document.getElementById('div_vendedor1').style.display='none';
				document.getElementById('div_producto1').style.display='none';
				document.getElementById('div_vendedor2').style.display='none';
				document.getElementById('div_producto2').style.display='none';
				document.getElementById('producto').value == "-1";
				document.getElementById('proveedor').value == "-1";
				document.getElementById('vendedor').value == "-1";
				
			}
		}
	}
}


-->
</script>

<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar al proveedor n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
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
            <li><a href="admin.php">Administraci&oacute;n</a></li>
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
                
            <img src="../images/iconos/onebit_18.png" alt="image 3" />
            <a href="../compras/nuevo_proveedor.php">Nuevo Proveedor</a>
            
        </div>

         <div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_78.png" alt="image 3" />
            <a href="nuevo_cargo.php">Nuevo Cargo</a>
            
        </div>
        
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
        <SCRIPT LANGUAGE="JavaScript">
		  <!-- Funcion que valida que se hayan escrito los campos obligatorios-->
				function validar() {
				  if (document.reporte.tipo.value == "-1") {
					  alert ('Debe seleccionar el tipo de reporte');
					  document.getElementById('tipo').focus();
					  return false;
				  }
				  if (document.reporte.finicio.value == "") {
					  alert ('Seleccione la fecha de inicio del reporte');
					  document.getElementById('finicio').focus();
					  return false;
				  }
				  if (document.reporte.ffin.value == "") {
					  alert ('Seleccione la fecha de termino del reporte');
					  document.getElementById('ffin').focus();
					  return false;
				  }
				  
				  return true;
			   	}
		</SCRIPT> 
        
    
            <h2>Reportes de Administraci&oacute;n y Pagos</h2>
            <div id="data_form">
            
            
            <form method="get" action="redireccionar_reporte.php" name="reporte" onSubmit="return validar()">
                <table align="center" width="500px" border="0px">
                    
                    <tr><td>
                      <BR>
                      <fieldset>								  
                        <table border="0" width="100%">	
                        <tr>
                        <td align="right"><span>*</span> Tipo de reporte: </td>
                        <td align="left" valign="middle" colspan="3">
                        	<select id="tipo" name="tipo" onchange="mostrar();">
                                <option value="-1">Seleccione...</option>
                                <!--option value="general">General de ventas</option>
                                <option value="proveedor">Ventas por proveedor</option>
                                <option value="linea">Ventas por Linea</option>
                                <option value="vendedor">Ventas por vendedor</option>
                                <option value="producto">Ventas por producto</option>
                                <option value="ext">Comparativo de moneda extranjera</option>
                                <option value="comisiones">Comisiones</option>
                                <option value="cobranza">Cuentas por cobrar</option-->
                                <option value="anti">Antig&uuml;edad de cuentas por pagar</option>
								<!--option value="canceladas">Cargos Cancelados</option-->
                            </select>
                        </td>
                        </tr>
                        
                        <tr><td align="right"><div id="div_proveedor1" style="display:none;"><span>*</span>Proveedor:</div></td><td colspan="3"><div id="div_proveedor2" style="display:none;"> 
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
                          </div></td></tr>
                          
                          <tr><td align="right"><div id="div_vendedor1" style="display:none;"><span>*</span>Vendedor:</div></td><td colspan="3"><div id="div_vendedor2" style="display:none;">
                        	<select id="vendedor" name="vendedor">
                                <option value="-1">Selecciona Uno...</option>
                                <?php 
                                    $lista_vend = $db->consulta("SELECT `id_empleado`,`clave`,`nombre` FROM `empleado` WHERE `id_puesto`=1 AND `id_status`=1;");
                                    
                                        while ($row6 = $db->fetch_array($lista_vend))
                                        {
                                          $id_vendedor = $row6['id_empleado'];
                                          $vend = utf8_decode($row6['nombre']);
                                          echo "<option value=\"".$id_vendedor."\">".$vend."</option>";
                                        }
                                ?>
                            </select>
                          </div></td></tr>
                          
                          <tr><td align="right"><div id="div_producto1" style="display:none;"><span>*</span>Producto:</div></td><td colspan="3"><div id="div_producto2" style="display:none;"> 
                        	<select id="producto" name="producto">
                                <option value="-1">Selecciona Uno...</option>
                                <?php 
                                    $lista_prod = $db->consulta("SELECT * FROM `producto` ORDER BY id_producto ASC;");
                                        while ($row3 = $db->fetch_array($lista_prod))
                                        {
                                          $id_prod = $row3['id_producto'];
                                          $nombre = utf8_decode($row3['nombre']);
                                          echo "<option value=\"".$id_prod."\">".$nombre."</option>";
                                        }
                                ?>
                            </select>
                          </div></td></tr>
                          <tr>
                        	<td align="right"><span>*</span> de: </td><td align="left"><input class="texto" type="text" id="finicio" name="finicio" size="10" /></td>
                            <td align="right"><span>*</span> a: </td><td align="left"><input class="texto" type="text" id="ffin" name="ffin" size="10" /></td>
                       	</tr>
                        
                        </table>
                      </fieldset>
                    </td></tr>
                    <tr><td colspan = "2"><small><span>*</span>Campos obligatorios</small></td></tr>
    
                    <tr><td colspan="2"align="right"> 	   
                     <BR> <input class="submit_btn reset" type="submit" name="consultar" id="consultar" value="Consultar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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