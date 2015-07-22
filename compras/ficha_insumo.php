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

<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
		$("#tablesorter-pres").tablesorter({sortList:[[1,0]], widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false},3:{sorter: false},4:{sorter: false}}});
		} 
	);
	
	$(document).ready(function() 
		{ 
		$("#tablesorter-prov").tablesorter({sortList:[[1,0]], widthFixed: true, widgets: ['zebra'], headers: { 0:{sorter: false},3:{sorter: false},4:{sorter: false}}});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el elemento n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
</script>

<!--VALIDAR EL FORMULARIO DE PRESENTACION -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarPres() {
			if (document.regPres.depende.value == "-1") {
				alert ('Debe seleccionar un insumo como dependencia');
				document.getElementById('depende').focus();
				return false;
			}
			
			return true;
	}
</SCRIPT> 

<!--VALIDAR EL FORMULARIO DE PROVEEDOR -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarProv() {
			if (document.regProv.proveedor.value == "-1") {
				alert ('Debe seleccionar un proveedor');
				document.getElementById('proveedor').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE DEPENDENCIA LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['agregar1'])) {
	$cve_ins = $_GET['numero'];
	$cve_depende = $_GET['depende'];
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into insumo_dependencias( id_primario,id_accesorio) values('".$cve_ins."','".$cve_depende."');");  
}
?>

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PROVEEDOR LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['agregar2'])) {
	$cve_ins = $_GET['numero'];
	$cve_prov = $_GET['proveedor'];
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into insumo_proveedor(id_insumo, id_proveedor) values('".$cve_ins."','".$cve_prov."');");  
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

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_insumo.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Ficha de Datos de Insumo</h2>

			<?php
                $clave = $_GET['numero'];
                $insumo = $db->consulta("SELECT * FROM `insumo` WHERE id_insumo='".$clave."'");
                $existe = $db->num_rows($insumo);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del insumo con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($insumo))
                {
					$nombre = $row['nombre'];
					$descripcion = $row['descripcion'];
					$max = $row['maximo'];
					$min = $row['minimo'];
					$id_unidad = $row['id_unidad'];
					//consulto la unidad
					$uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
					while ($row2 = $db->fetch_array($uni))
					{
						$unidad = $row2['unidad'];
					}
					
					
					$id_moneda = $row['id_moneda'];
					//consulto la moneda
					$mon = $db->consulta("SELECT *  FROM `moneda` WHERE `id_moneda` = '".$id_moneda."';");
					while ($row2 = $db->fetch_array($mon))
					{
						$moneda = $row2['moneda'];
					}
					
					
					$id_linea = $row['id_linea'];
					//consulto la linea
					$lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
					while ($row2 = $db->fetch_array($lin))
					{
						$linea = $row2['linea'];
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
                    <legend> <B>Datos de Insumo </B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="left" colspan="2"><b>Nombre:</b>
							<?php echo $nombre; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="2"><b>Descripci&oacute;n:</b>
							<?php echo $descripcion; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="2"><b>Moneda:</b>
							<?php echo $moneda; ?></td>
                        </tr>
                         <tr>
                            <td align="left"><b>Linea:</b>
							<?php echo $linea; ?></td>
                            <td align="left"><b>Unidad de medida:</b>
							<?php echo $unidad; ?></td>
                        </tr>
                         <tr>
                            <td align="left"><b>M&aacute;ximo en Inventario:</b>
							<?php echo $max." ".$unidad; ?></td>
                            <td align="left"><b>M&iacute;nimo en Inventario:</b>
							<?php echo $min." ".$unidad; ?></td>
                        </tr>
                        
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="center">
                  <!-- Recuadro con los datos de presentaciones del insumo -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Dependencias</B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="center">
                            <form method="get" action="ficha_insumo.php" name="regPres" onsubmit="return validarPres()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">	
                            	<table border="0" width="550px" align="center">	
                                    <tr>
                                    	<td width="30%"><b>Seleccione un Insumo: </b></td>
                                    	<td align="left" valign="middle">
                                        <select id="depende" name="depende">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_pres = $db->consulta("SELECT * FROM `insumo` WHERE tipo=2;");
													while ($row3 = $db->fetch_array($lista_pres))
													{
													  $id_insumo = $row3['id_insumo'];
													  $nombre = $row3['nombre'];
													  echo "<option value=\"".$id_insumo."\">".$nombre."</option>";
													}
											?>
                                        </select></td>
                                        
                                        <td width="15%">&nbsp;</td>
                                        <td><input class="submit_btn reset" type="submit" name="agregar1" id="agregar1" value="Agregar"/></td>
                                    </tr>
                                </table>
                            </form>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
								<?php 
                                    $dependencias = $db->consulta("SELECT * FROM `insumo_dependencias` WHERE id_primario='".$clave."'");
                                    $existe = $db->num_rows($dependencias);
                                    if($existe<=0){
                                        echo "No se han registrado dependencias de &eacute;ste insumo";
									
                                    
                                    }else{
									?>
										<table id="tablesorter-pres" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Insumo</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$i = 1;
										while ($row = $db->fetch_array($dependencias))
										{
											$id_dependencia = $row['id_dependencia'];
											$id_accesorio = $row['id_accesorio'];
											//consultare el nombre del insumo  accesorio
											$consulta_nom = $db->consulta("SELECT *  FROM `insumo` WHERE `id_insumo` = '".$id_accesorio."';");
											while ($row2 = $db->fetch_array($consulta_nom))
											{
												$nombre_acc = $row2['nombre'];
											}
											//ahora imprimire la tabla
											?>
                                                
            
													<?php 
                                        
                                                       echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
                                                             <td>".$nombre_acc."</td>
                                                             <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_insumo_dep.php?numero=$clave&pres=$id_accesorio\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
                                                             <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_insumo_dep.php?numero=$clave&pres=$id_accesorio\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
                                                             
                                                             </tr>";
                                                            
                                                            
                                                    ?>
                                                
											
											<?php	
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
                
                
                <tr><td align="center">
                  <!-- Recuadro con los datos de los proveedores-->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Proveedoress</B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="center">
                            <form method="get" action="ficha_insumo.php" name="regProv" onsubmit="return validarProv()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $clave;?>">	
                            	<table border="0" width="550px">	
                                    <tr>
                                    	<td align="right"><b>Proveedor: </b></td>
                                    	<td align="left" valign="middle">
                                        <select id="proveedor" name="proveedor">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_prov = $db->consulta("SELECT `id_proveedor`,`nombre` FROM `proveedor`;");
													while ($row6 = $db->fetch_array($lista_prov))
													{
													  $id_prov = $row6['id_proveedor'];
													  $prov = utf8_decode($row6['nombre']);
													  echo "<option value=\"".$id_prov."\">".$prov."</option>";
													}
											?>
                                        </select></td>
                                        <td width="15%">&nbsp;</td>
                                        <td><input class="submit_btn reset" type="submit" name="agregar2" id="agregar2" value="Agregar"/></td>
                                    </tr>
                                </table>
                            </form>
                            </td>
                        </tr>
                        <tr>
                            <td align="left">
								<?php 
                                    $proveedores = $db->consulta("SELECT * FROM `insumo_proveedor` WHERE id_insumo='".$clave."'");
                                    $existe = $db->num_rows($proveedores);
                                    if($existe<=0){
                                        echo "No se han registrado proveedores de &eacute;ste insumo, registre proveedores para tener completa su base de datos";
                                    
                                    }else{
									?>
										<table id="tablesorter-prov" class="tablesorter" cellspacing="0">
                                        <thead>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Proveedor</th>
                                                <th class="header">Tel&eacute;fono</th>
                                                <th>Editar</th>
                                                <th>Eliminar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$j = 1;
										while ($row = $db->fetch_array($proveedores))
										{
											$id_proveedor = $row['id_proveedor'];
											
											//consultare el nombre y el telefono del proveedor
											$consulta_nom = $db->consulta("SELECT `nombre`,`telefono`  FROM `proveedor` WHERE `id_proveedor` = '".$id_proveedor."';");
											while ($row2 = $db->fetch_array($consulta_nom))
											{
												$nombre = $row2['nombre'];
												$tel = $row2['telefono'];
												
											}
											//ahora imprimire la tabla
											?>
                                                
            
													<?php 
                                        
                                                       echo "<tr><td class=\"listado\" align=\"center\">".$j."</td>
                                                             <td>".$nombre."</td>
															 <td align=\"center\">".$tel."</td>
                                                             <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_proveedor.php?numero=".$id_proveedor."\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
                                                             <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_insumo_prov.php?numero=$clave&prov=$id_proveedor\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
                                                             
                                                             </tr>";
                                                            
                                                            
                                                    ?>
                                                
											
											<?php	
											$j++;
										}
									}
                                ?>
                            		</tbody>
                                </table>
                            
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
?>