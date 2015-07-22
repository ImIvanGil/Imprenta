<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
$curId = $prot->getId();
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


<script>
//muestra el calendario
$(function() {
	$( "#entrega" ).datepicker({
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
                
            <img src="../images/iconos/onebit_39.png" alt="image 3" />
            <a href="ordenes_produccion.php">Ordenes de Producci&oacute;n</a>
            
        </div>

      
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Nueva Orden de Produccion</h2>
            <div id="data_form">
            
           <SCRIPT LANGUAGE="JavaScript">
			//validar el formulario
			function validar() {
				if (document.registro.entrega.value == "") {
					alert ('Debe escribr la fecha en la que se entregara el producto');
					document.getElementById('entrega').focus();
					return false;
				}
				if (document.registro.cliente.value == "-1") {
					alert ('Debe seleccionar al cliente');
					document.getElementById('cliente').focus();
					return false;
				}
				if (document.registro.producto.value == "-1") {
					alert ('Debe seleccionar un producto');
					document.getElementById('producto').focus();
					return false;
				}
				return true;
			}
			</SCRIPT>
            
            
            	
				<?php 
					$fecha = date('Y-m-d');
					
					$MaxDias = 6; //Cantidad de dias maximo para el prestamo, este sera util para crear el for  
      
          
					 //Creamos un for desde 0 hasta 6  
					 for ($i=0; $i<$MaxDias; $i++)  
						{  
							//Acumulamos la cantidad de segundos que tiene un dia en cada vuelta del for  
							$Segundos = $Segundos + 86400;  
							  
						   //Obtenemos el dia de la fecha, aumentando el tiempo en N cantidad de dias, segun la vuelta en la que estemos  
							$caduca = date("D",time()+$Segundos);  
							  
								//Comparamos si estamos en sabado o domingo, si es asi restamos una vuelta al for, para brincarnos el o los dias...  
								if ($caduca == "Sat")  
								{  
									$i--;  
								}  
								else if ($caduca == "Sun")  
								{  
									$i--;  
								}  
								else  
								{  
									//Si no es sabado o domingo, y el for termina y nos muestra la nueva fecha  
									$entrega = date("Y-m-d",time()+$Segundos);  
								}  
						}  

					
				
				?>
                
               <form method="POST" action="registro_orden_produccion.php" enctype="multipart/form-data"  onSubmit="return validar()" name="registro" id="registro">
               
               
               <table border="0" align="center" width="100%">
               <input type="hidden" name="operacion" id="operacion" value="registrar">
               
               <tr><td align="right">Vendedor:</td>
                <td> <?php echo $curUser;?> <input type="hidden" id="ordena" name="ordena" value="<?php echo $curId;?>"/></td>
               </tr>
               
               <tr><td align="right">Fecha orden:</td>
                <td> <?php echo $fecha;?> <input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?>"/></td>
               </tr>
               
               <tr><td align="right"><span>*</span>Fecha entrega:</td>
                <td> <input class="texto" type="text" id="entrega" name="entrega" size="10" value="<?php echo $entrega;?>" /> <input type="checkbox" name="urge" id="urge" value="1"/>Urgente</td>
               </tr>
               
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
              
              <tr>
              <td align="right"><span>*</span>Producto: </td>
                  <td align="left" valign="middle">
                  <select id="producto" name="producto">
                      <option value="-1">Selecciona Uno...</option>
                      <?php 
                          $lista_tam = $db->consulta("SELECT `id_producto`, `id_linea`,`clave`,`nombre` FROM `producto`;");
                              while ($row6 = $db->fetch_array($lista_tam))
                              {
								$id_linea = $row6['id_linea'];
								$consulta_lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
								while ($row2 = $db->fetch_array($consulta_lin)){$linea = $row2['linea'];}
					
                                $id_producto = $row6['id_producto'];
                                $cve = $row6['clave'];
                                $prod = utf8_decode($row6['nombre']);
                                echo "<option value=\"".$id_producto."\">".$cve."-".$linea."-".$prod."</option>";
                              }
                      ?>
                  </select>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="nuevo_dis" id="nuevo_dis" value="1"/>Nuevo dise&ntilde;o
              </td>
              </tr>

               <tr><td align="right">Orden de compra Cliente:</td>
                <td> <input class="texto" type="text" id="orden" name="orden" size="30"  /></td>
               </tr>
              
                <tr><td align="right" valign="top">Observaciones:</td>
                <td align="left"><textarea class="textarea" name="observaciones" id="observaciones" cols="0" rows="0"></textarea></td></tr>
                <tr>
                <td colspan="2" align="right">
                    <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="submit_btn reset" type="reset" value="Cancelar"/>
                </td>
                </tr>
                </table>

        
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