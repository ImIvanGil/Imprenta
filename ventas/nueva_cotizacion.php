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

<script src="../js/jquery-1.4.2.js"></script>
<script src="../js/jquery.ui.core.js"></script>
<script src="../js/jquery.ui.widget.js"></script>
<script src="../js/jquery.ui.datepicker.js"></script>
<script src="../js/valida/jquery.validate.js"></script>
<script src="../js/localization/messages_es.js"></script>

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
            <li><a href="cotizaciones.php" class="current">Cotizador</a></li>
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
                
            <img src="../images/iconos/onebit_60.png" alt="image 3" />
            <a href="nueva_cotizacion.php">Nueva Cotizaci&oacute;n</a>
            
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
    
            <h2>Nueva Cotizaci&oacute;n</h2>
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
						alert ('Debe eleccionar una fecha para la cotizacion');
						document.getElementById('fecha').focus();
						return false;
					}
					if (document.registro.condicion.value == "-1") {
						alert ('Debe seleccionar un tipo de pago');
						document.getElementById('condicion').focus();
						return false;
					}
					if (document.registro.status.value == "-1") {
						alert ('Debe seleccionar un status para la cotizacion');
						document.getElementById('status').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            <form method="post" enctype="multipart/form-data" action="registro_cotizacion.php" name="registro" onSubmit="return validar()">
            <input type="hidden" name="operacion" id="operacion" value="registrar">	
			<table align="center" width="100%" border="0px">
			<tr align="center"><td>
            <fieldset>								  
			<legend> <B>Datos de Registro </B></legend>
            	<table border="0" width="100%">	
                  <tr>
                  <td align="right"><span>*</span>Cliente: </td>
                      <td align="left" valign="middle">
                      <select id="cliente" name="cliente">
                          <option value="-1">Selecciona Uno...</option>
                          <?php 
                              $lista_tam = $db->consulta("SELECT `id_cliente`,`nombre` FROM `cliente`;");
                                  while ($row6 = $db->fetch_array($lista_tam))
                                  {
                                    $id_cliente = $row6['id_cliente'];
                                    $cte = $row6['nombre'];
                                    echo "<option value=\"".$id_cliente."\">".utf8_decode($cte)."</option>";
                                  }
                          ?>
                      </select>
                  </td>
                  </tr>
                  <tr><td align="right"><span>*</span>Fecha:</td>
                  <td> <input class="texto" type="text" id="fecha" name="fecha" size="20" /><br /></td></tr>
                  
                  <tr>
                  <td align="right"><span>*</span>Condici&oacute;n de pago: </td>
                      <td align="left" valign="middle">
                      <select id="condicion" name="condicion">
                          <option value="-1">Selecciona Uno...</option>
                          <?php 
                              $lista_tipo = $db->consulta("SELECT * FROM `tipo_pago_cliente`;");
                                  while ($row6 = $db->fetch_array($lista_tipo))
                                  {
                                    $id_tipo = $row6['id_tipo_pago'];
                                    $tipo = $row6['tipo_pago'];
                                    echo "<option value=\"".$id_tipo."\">".$tipo."</option>";
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
													  $id_iva = $row6['id_iva'];
													  $iva = $row6['tipo'];
													  echo "<option value=\"".$id_iva."\">".$iva."</option>";
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                   
                  <tr>
                  <td align="right"><span>*</span>Estado de la Cotizaci&oacute;n: </td>
                      <td align="left" valign="middle">
                      <select id="status" name="status">
                          <option value="-1">Selecciona Uno...</option>
                          <?php 
                              $lista_sta = $db->consulta("SELECT * FROM `status_cotizacion`;");
                                  while ($row6 = $db->fetch_array($lista_sta))
                                  {
                                    $id_sta = $row6['id_status_cotizacion'];
                                    $sta = $row6['status'];
                                    echo "<option value=\"".$id_sta."\">".$sta."</option>";
                                  }
                          ?>
                      </select>
                  </td>
                  </tr>
                  
                  <tr><td align="right">Vigencia:</td>
                  <td> <input class="texto" type="text" id="vigencia" name="vigencia" size="6" />  d&iacute;as<br /></td></tr>

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