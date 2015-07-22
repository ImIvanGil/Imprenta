<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
$userId = $prot->getId();
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
	$cve = $_POST['numero'];
	$cant = $_POST['cant'];
	$cve_prod = $_POST['producto'];
	$completo =0;
	
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into detalle_orden_produccion(id_orden, id_producto, cantidad, completo) values('".$cve."','".$cve_prod."','".$cant."','".$completo."');");  
	$link = "Location: ficha_orden_prod.php?numero=$cve";
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
            <li><a href="produccion.php">Producci&oacute;n</a></li>
            <li><a href="almacen.php" class="current">Almac&eacute;n</a></li>
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
            <a href="nueva_orden_produccion.php">Nueva Orden de Producci&oacute;n</a>
            
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
    
            <h2>Ficha de Almacen</h2>

			
                <?php
                $clave = $_GET['numero'];
				
				//DATOS DEL PRODUCTO
                $producto = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$clave."'");
                $existe = $db->num_rows($producto);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del producto con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($producto))
                {
					$nombre = utf8_encode($row['nombre']);
					$descripcion = utf8_encode($row['descripcion']);
					
					//nombre del usuario
					  $nom_us = $db->consulta("SELECT `userRemark` FROM `myuser` WHERE `ID`=$userId;");
					  while($row19 = $db->fetch_array($nom_us)){
						  $nom_usuario = $row19['userRemark'];
						}
			?>
            
            <table align="center" border="0" width="100%">
            	<tr>
                    <td align="right">
					<?php 
                        //echo "<a href=\"#\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
						//echo "<a href=\"ficha_insumo_pdf.php?numero=$clave\" target=\"blank\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
                    ?>                 
                     </td>
                </tr>
                <tr><td align="center">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos Generales </B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="left"><b>Nombre:</b>
							<?php echo $nombre."</td>";?>
                            <td align="right"><a href="nuevo_mov_almacen.php?<?php echo "numero=".$clave;?>">Nuevo movimiento <img src="../images/iconos/onebit_31.png" width="24px" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="2"><b>Descripci&oacute;n:</b>
							<?php echo $descripcion; ?></td>
                        </tr>
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="center">
                  <!-- Recuadro con los datos de presentaciones del insumo -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Ficha de Almac&eacute;n</B></legend>
                    <table border="0"  align="center">	
                       
                        <tr>
                            <td align="left">
								<?php 
                                    $movimientos = $db->consulta("SELECT * FROM `producto_almacen` WHERE id_producto='".$clave."' ORDER BY `fecha`,`id_movimiento` ASC");
                                    $existe = $db->num_rows($movimientos);
                                    if($existe<=0){
                                        echo "No se han registrado movimientos de almac&eacute;n para este producto";
									
                                    
                                    }else{
									?>
										<table id="tablesorter-pres" class="tablesorter" cellspacing="0" border="1">
                                        <thead>
                                            <tr align="center">
                                                <th class="header" colspan="3">&nbsp;</th>
                                                <th class="header" colspan="3">U N I D A D E S</th>
                                                <th class="header" colspan="2">C O S T O</th>
                                                <th class="header" colspan="3">V A L O R E S</th>
                                                <th class="header" colspan="3">&nbsp;</th>
                                            </tr>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Fecha</th>
                                                <th class="header">Orden Producci&oacute;n</th>
                                                <th class="header">Entrada</th>
                                                <th class="header">Salida</th>
                                                <th class="header">Existencia</th>
                                                <th class="header">Unitario</th>
                                                <th class="header">Promedio</th>
                                                <th class="header">Debe</th>
                                                <th class="header">Haber</th>
                                                <th class="header">Saldo</th>
                                                <th class="header">Editar</th>
                                                <th class="header">Borrar</th>
                                                <th class="header">Ficha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$i = 1;
										$existencia = 0;
										$saldo = 0;
										while ($row3 = $db->fetch_array($movimientos))
										{
											$key = $row3['id_movimiento'];
											$fecha = $row3['fecha'];
											$o_produccion = $row3['id_orden_produccion'];
											if($o_produccion<=0){
												$o_produccion = "-";
											}
											$tipo_mov = $row3['id_tipo_movimiento'];
											$unidades = $row3['cantidad'];
											$unitario = $row3['unitario'];
											$total_mov = $unidades * $unitario;
											
											//ahora imprimire la tabla
											
												//si es entrada
												 echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
												 <td align=\"center\" align=\"center\">".$fecha."</td>
												 <td align=\"center\" align=\"center\">".$o_produccion."</td>";
												 //reviso si es entrada o salida
												 if($tipo_mov==1){
													  echo "<td align=\"center\">".$unidades."</td><td>&nbsp;</td>";
													  $existencia = $existencia + $unidades;
													  $saldo = $saldo + $total_mov;
												 }else{
													  echo "<td>&nbsp;</td><td align=\"center\">".$unidades."</td>";
													  $existencia = $existencia - $unidades;
													  $saldo = $saldo - $total_mov;
												 }
												 
												 echo "<td align=\"center\">".$existencia."</td>";
												 echo "<td class=\"listado\" align=\"right\">$ ".number_format($unitario,2)."</td>";
												 //calculo el promedio
												$promedio = $saldo/$existencia;
												echo "<td class=\"listado\" align=\"right\">$ ".number_format($promedio,2)."</td>";
												
												//vuelvo a hacer la revision para imprimir los valores
												 if($tipo_mov==1){
													  echo "<td align=\"center\">".number_format($total_mov,2)."</td><td>&nbsp;</td>";
												 }else{
													  echo "<td>&nbsp;</td><td align=\"center\">".number_format($total_mov,2)."</td>";
												 }
												 
												 echo "<td class=\"listado\" align=\"right\">$ ".number_format($saldo,2)."</td>
												 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_mov_almacen.php?numero=$key&producto=$clave\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
												 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/produccion/borrar_mov_almacen.php?numero=$key&producto=$clave\",\"$i\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
												 if($tipo_mov==2){
													echo "<td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"ficha_salida_almacen_pdf.php?movimiento=$key&numero=$clave&fecha=$fecha&solicita=$nom_usuario&cantidad=$unidades&nomprod=$nombre\"><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" align=\"center\"></a></td>"; 
												}else{
													echo "<td class=\"listado\" align=\"center\">-</td>"; 
												}
											
											$i++;
										}
										echo"</tbody></table>";
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