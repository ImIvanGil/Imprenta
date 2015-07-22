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
		width:300px;
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

<SCRIPT LANGUAGE="JavaScript">
//validar el formulario
function validar() {
	if (document.registro.insumo.value == "-1") {
		alert ('Debe seleccionar un insumo');
		document.getElementById('insumo').focus();
		return false;
	}
	if (document.registro.fecha.value == "") {
		alert ('Debe escribr la fecha en que se efectuo la operacion');
		document.getElementById('fecha').focus();
		return false;
	}
	if (document.registro.tipo.value == "-1") {
		alert ('Debe seleccionar el tipo de operacion');
		document.getElementById('tipo').focus();
		return false;
	}
	if (document.registro.unidades.value == "") {
		alert ('Debe escribir las unidades de insumo que entran o salen del inventario');
		document.getElementById('unidades').focus();
		return false;
	}
	if (document.registro.tipo.value == "1" || document.registro.tipo.value == "3") {
			if(document.registro.unitario.value == ""){
				alert ('Debe escribir el costo unitario de los productos que van a entrar al inventario');
				document.getElementById('unitario').focus();
				return false;
			}
	}
	if (document.registro.descripcion.value == "") {
		alert ('Debe escribir escribir una descripcion que sirva como referencia del movimiento');
		document.getElementById('descripcion').focus();
		return false;
	}
	
	return true;
}
</SCRIPT>

<script type="text/javascript">
<!-- muestra y oculta campos en el formulario segun el select de tipo
function mostrar(){
if (document.registro.tipo.value == "1" || document.registro.tipo.value == "3") {
	//muestra el campo de costo unitario si es una entrada de mercancias si es una salida debera calclar el costo unitario promedio al momento de guardar
	document.getElementById('cunitario').style.display='block';
	
	document.getElementById('cunitario').focus();
} else {
	
		if(document.registro.tipo.value == "2" || document.registro.tipo.value == "-1"){
			//oculta y elimina el costo unitario si el tipo de operacion no esta seleccionado o si es salida
			document.getElementById('cunitario').style.display='none';
			document.registro.unitario.value = "";
		
		}
		
	}
	//oculta el div con id 'desdeotro'
}


-->
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
            <li><a href="compras.php">Compras</a></li>
            <li><a href="insumos.php" class="current">Insumos</a></li>
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
                
            <img src="../images/iconos/onebit_14.png" alt="image 3" />
            <a href="nuevo_insumo.php">Nuevo Insumo</a>
            
        </div>

         
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
        
        
    <h3>Registro de nuevo movimiento de inventario</h3>
            <div id="comment_form">
           
           <fieldset>								  
          <legend> <B>Datos de Movimiento </B></legend>
          <table border="0px" align="center" width="100%">	

          <?php
		  	if(isset($_GET['numero'])){
				$num = $_GET['numero'];
			}else{
				$num =-1;
			}
		  
		  ?>
          
          
         <form method="POST" action="registro_mov_inventario.php"  onSubmit="return validar()" name="registro" id="registro"> 
         <input type="hidden" name="operacion" id="operacion" value="registrar">
         <input type="hidden" name="presentacion" id="presentacion" value="<?php echo $id_pres;?>">
         
         <tr>
        	<td align="right"><span>*</span>Insumo: </td>
            <td align="left" valign="middle" colspan="3">
            <select id="insumo" name="insumo">
                <option value="-1">Selecciona Uno...</option>
                <?php 
                    $lista_tam = $db->consulta("SELECT `id_insumo`,`nombre`,`descripcion` FROM `insumo`;");
                        while ($row6 = $db->fetch_array($lista_tam))
                        {
                          $id_insumo = $row6['id_insumo'];
                          $nombre_ins = $row6['nombre'];
                          $desc = $row6['descripcion'];
						  if($id_insumo == $num){
							  echo "<option value=\"".$id_insumo."\" selected>".$nombre_ins."-".$desc."</option>";
						  }else{
							 echo "<option value=\"".$id_insumo."\">".$nombre_ins."-".$desc."</option>"; 
						  }
						  
                          
                        }
                ?>
            </select>
        </td>
        </tr>
         
                                         
         <tr><td align="right">*Fecha:</td>
          <td> 
          <?php 
		  	//consultare la fecha de hoy
			$hoy = date('Y-m-d');
		  ?>
          <input class="texto" type="text" id="fecha" name="fecha" size="10" value="<?php echo $hoy;?>" />
          
          </td>
         
          
         <td align="right">*Tipo de movimiento:</td>
          <td><select id="tipo" name="tipo" onchange="mostrar();">
                  <option value="-1">Seleccione</option>
                  <?php 
                  
                  $tipos = $db->consulta("SELECT * FROM `tipo_mov_inventario`");
                  while ($row = $db->fetch_array($tipos))
                      {
                      $id_tipo = $row['id_tipo'];
                      $tipo_movimiento = $row['tipo_movimiento'];
                      echo "<option value='".$id_tipo."'>".$tipo_movimiento."</option>";
                      }
                  ?>
                  
              </select>
          </td>
          
          
         </tr>
         
         <tr>
         <td align="left" colspan="4"><div id="cunitario" style="display:none;">&nbsp;&nbsp;&nbsp;*Costo unitario: $ <input type="text" class="texto" id="unitario" name="unitario" size="20"></div></td>
         </tr>
         
       
          
          <tr>
          <td align="right">*Unidades:</td>
          <td colspan="3"><input type="text" class="texto" id="unidades" name="unidades" size="20"></td>
          </tr>
          
          <tr><td align="right" valign="top">*Descripci&oacute;n:</td>
          <td align="left" colspan="3"><textarea class="textarea" name="descripcion" id="descripcion" cols="40" rows="2"></textarea></td></tr>
          <tr>
          <td colspan="4" align="right">
              <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input class="submit_btn reset" type="reset" value="Cancelar"/>
          </td>
          </tr>
          </table>
          
          </fieldset>

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