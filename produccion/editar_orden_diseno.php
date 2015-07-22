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
    
            <h2>Editar Orden de Dise&ntilde;o</h2>
            <div id="data_form">
            
           <SCRIPT LANGUAGE="JavaScript">
				//validar el formulario
				function validar() {
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
              $db = new MySQL();
              $clave = $_GET['numero'];
			  $orden = $db->consulta("SELECT * FROM `orden_diseno` WHERE id_orden='".$clave."'");
              while ($row = $db->fetch_array($orden))
			  {
				  $fecha = $row['fecha'];
				  $especificaciones = utf8_decode($row['especificaciones']);
				  $id_cliente = $row['id_cliente'];
				  $id_producto = $row['id_producto'];
				  $id_vendedor = $row['ordena'];
				  $id_estado = $row['id_estado'];
				  
					//datos del vendedor
						$sql_vendedor = "SELECT * FROM myuser where `ID`='".$id_vendedor."'";
						$consulta_vendedor = $db->consulta($sql_vendedor);
						while($row_vend = mysql_fetch_array($consulta_vendedor)){
							$nom_vendedor=$row_vend['userRemark'];
							//$nom_vendedor=$id_vendedor;
						}
					//datos del producto
						$sql_prod = "SELECT `id_producto` FROM detalle_orden_produccion where `id_orden`='".$clave."'";
						$consulta_prod = $db->consulta($sql_prod);
						while($row_prod = mysql_fetch_array($consulta_prod)){
							$id_producto=$row_prod['id_producto'];
						}
					
				  
				}
				
					
			?>
            	

                
                <form method="POST" action="registro_orden_diseno.php"  onSubmit="return validar()" name="registro" id="registro">
               <input type="hidden" name="operacion" id="operacion" value="editar">
               <input type="hidden" name="prod_original" id="prod_original" value="<?php echo $id_producto;?>">
               <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">

               
               <table border="0" align="center">
               
               <tr><td align="right">Vendedor:</td>
                <td> <?php echo $nom_vendedor;?> <input type="hidden" id="ordena" name="ordena" value="<?php echo $id_vendedor;?>"/></td>
               </tr>
               
               <tr><td align="right">Fecha orden:</td>
                <td> <?php echo $fecha;?> <input type="hidden" id="fecha" name="fecha" value="<?php echo $fecha;?>"/></td>
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
                                $id_cte = $row6['id_cliente'];
                                $cve_cte = $row6['clave'];
                                $cte = utf8_decode($row6['nombre']);
								
								if($id_cliente==$id_cte){
									echo "<option value=\"".$id_cte."\" selected>".$cve_cte."-".$cte."</option>";
								}else{
									echo "<option value=\"".$id_cte."\">".$cve_cte."-".$cte."</option>";
								}
                                
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
                          $lista_tam = $db->consulta("SELECT `id_producto`,`clave`,`nombre` FROM `producto`;");
                              while ($row6 = $db->fetch_array($lista_tam))
                              {
                                $id_prod = $row6['id_producto'];
                                $cve_cte = $row6['clave'];
                                $cte = utf8_decode($row6['nombre']);
								
								if($id_producto==$id_prod){
									echo "<option value=\"".$id_prod."\" selected>".$cve_cte."-".$cte."</option>";
								}else{
									echo "<option value=\"".$id_prod."\">".$cve_cte."-".$cte."</option>";
								}
								
                                
                              }
                      ?>
                  </select>
                 
              </td>
              </tr>

                <tr><td align="right" valign="top">Especificaciones:</td>
                <td align="left"><textarea class="textarea" name="especificaciones" id="especificaciones" cols="120" rows="2"><?php echo $especificaciones;?></textarea></td></tr>
                <tr>
                
                <tr>
                <td align="right"><span>*</span>Estado: </td>
                    <td align="left" valign="middle">
                    <select id="estado" name="estado">
                        <option value="-1">Selecciona Uno...</option>
                        <?php 
                            $lista_sta = $db->consulta("SELECT * FROM `status_orden_diseno`;");
                            while ($row6 = $db->fetch_array($lista_sta))
                            {
                              $id_status = $row6['id_status'];
                              $status = $row6['status'];
                             
                               if($id_estado==$id_status){
                                      echo "<option value=\"".$id_status."\"selected>".$status."</option>";
                                }else{
                                       echo "<option value=\"".$id_status."\">".$status."</option>";
                                }
                              
                              
                            }
                        ?>
                    </select>
                </td>
                </tr>
                                    
                
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