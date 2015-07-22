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
	if (confirm("ALERTA!!!\nEst\u00e1 seguro que desea certificar la cargo?\n una vez certificada no podra hacer cambios al comprobante")) location.replace(url);
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
	$cve_fac = $_GET['numero'];
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
	$consulta = $db->consulta("insert into detalle_cargo(id_cargo, id_producto, unitario, cantidad,id_clase_iva) values('".$cve_fac."','".$cve_prod."','".$unitario."','".$cant."','".$tiva."');");  
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
    
            <h2>Ficha de Datos de Cargo</h2>

			<?php
                $clave = $_GET['numero'];			
				$cargo = $db->consulta("SELECT * FROM `cargo` WHERE id_cargo='".$clave."'");
                $existe = $db->num_rows($cargo);
                if($existe<=0){
                    echo "No hay informaci&oacute;n de la cargo con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($cargo))
                {
					
						$id_empresa = $row['id_empresa'];
						$referencia = $row['referencia'];
						$subtotal = $row['sub_total'];
						$impuesto = $row['impuestos'];
						$total = $subtotal + $impuesto;
					  	$id_proveedor = $row['id_proveedor'];
						//datos del proveedor
						$sql_proveedor = "SELECT * FROM proveedor where `id_proveedor`='".$id_proveedor."'";
						$consulta_proveedor = $db->consulta($sql_proveedor);
						while($row_proveedor = mysql_fetch_array($consulta_proveedor)){
							$nom_proveedor=$row_proveedor['nombre'];
							$rfc_proveedor=utf8_decode($row_proveedor['rfc']);
							$calle_proveedor=utf8_decode($row_proveedor['calle']);
							$numero_proveedor=$row_proveedor['numero'];
							$colonia_proveedor=utf8_decode($row_proveedor['colonia']);
							$ciudad_proveedor=$row_proveedor['ciudad'];
							$estado_proveedor=$row_proveedor['estado'];
							
							$dir_proveedor= $calle_proveedor." No.".$numero_proveedor." Col. ".$colonia_proveedor;
							
							
						
							$cp_proveedor=$row_proveedor['codigo_postal'];
							$tel_proveedor=$row_proveedor['telefono'];
						}
						
						$fecha = $row['fecha'];
						
						$id_forma = $row['id_forma_pago'];
						//forma de pago
						$sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
						$consulta_forma = $db->consulta($sql_forma);
						$row_forma = mysql_fetch_array($consulta_forma);
						$forma = $row_forma['forma_pago'];
						
						$id_status = $row['id_status_cargo'];
						// estado de la cargo
						$sql_status = "SELECT status FROM status_cargo WHERE id_status_cargo='".$id_status."'";
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
						$plazo = $row['plazo_pago'];
						$observaciones = $row['observaciones'];
						
						

			?>
            
            <table align="center" border="0" width="100%" cellspacing="0" cellpadding="0">
            	
                <tr><td align="left">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos de cargo</B></legend>
                    <table border="0" width="750px" align="center">	
                        <tr>
                            <td align="right"><b>Fecha:</b></td>
							<td align="left"><?php echo $fecha; ?></td>
                            <td align="right"><b>Estado:</b></td>
							<td align="left"><?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <td align="right" valign="top" width="20%"><b>proveedor:</b></td>
							<td colspan="3" align="left" valign="top">
								<?php echo $nom_proveedor; ?><br />
                                <?php echo $dir_proveedor; ?><br />
                                <?php echo $ciudad_proveedor.", ".$estado_proveedor; ?><br />
                                <?php echo "C.P. ".$cp_proveedor; ?><br />
                                <?php echo "Tel. ".$tel_proveedor; ?><br />
                            </td>
                        </tr>
                        <tr>
                            <td align="right"><b>Forma de Pago:</b></td>
							<td align="left"><?php echo $forma; ?></td>
                            <td align="right"><b>M&eacute;todo de Pago:</b></td>
							<td align="left"><?php echo $metodo; ?></td>
                        </tr>
                        <tr>
                            <td align="right"><b>Plazo de pago:</b></td>
							<td align="left"><?php echo $plazo; ?> d&iacute;as</td>
                            <?php 
								if($referencia!=""){
									echo "<td align=\"right\"><b>Referencia.:</b></td>";
									echo "<td align=\"left\">$referencia</td>";
								}else{
									echo "<td colspan=\"2\">&nbsp;</td>";								
								}
							?>
                        </tr>
                        <tr>
                            <td align="right"><b>Moneda:</b></td>
							<td align="left"><?php echo $moneda; ?></td>
                            <td align="right"><b>Tipo de Cambio:</b></td>
							<td align="left"><?php echo number_format($t_cambio,2); ?></td>
                        </tr>
                        
                         <tr>
                            <td align="right"><b>Subtotal:</b></td>
							<td align="right">$<?php echo number_format($subtotal,2); ?></td>
                            <td align="right">&nbsp;</td>
							<td align="left">&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="right"><b>Impuesto:</b></td>
							<td align="right">$<?php echo number_format($impuesto,2); ?></td>
                            <td align="right">&nbsp;</td>
							<td align="left">&nbsp;</td>
                        </tr> 
                        <tr>
                            <td align="right"><b>Total Cargo:</b></td>
							<td align="right"><b>$<?php echo number_format($total,2); ?></b></td>
                            <td align="right">&nbsp;</td>
							<td align="left">&nbsp;</td>
                        </tr>
                        
                        
                        <tr>
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
                <tr><td align="right">
                <?php
                     echo "<a href=\"ficha_cargo_pdf.php?clave=$clave\"><i>Descargar ficha del Cargo</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" /><br>";
					 echo "<a href=\"autorizar_cargo.php?clave=$clave&estado=2\"><i>Autorizar pago</i></a><img src=\"../images/iconos/onebit_38.png\" width=\"24px\" />";
				 
                ?>
        		
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