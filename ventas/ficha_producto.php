<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
		$("#tablesorter-mat").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: { 3:{sorter: false}}});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el elemento n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
</script>

<SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarMat(){
			if (document.material.papel.value == "-1") {
				alert ('Debe seleccionar un tipo de papel');
				document.getElementById('papel').focus();
				return false;
			}
			if (document.material.color.value == "") {
				alert ('Debe escribir el color del papel');
				document.getElementById('color').focus();
				return false;
			}
			return true;
	}
</SCRIPT>

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE INSUMO LOS VOY A GUARDAR  -->
 <?php
if (isset($_POST['agregar'])) {
	$cve_prod = $_POST['clave'];
	$id_papel = $_POST['papel'];
	//recibi las variables y ahora hare la consulta con el insert
	$consulta = $db->consulta("insert into copias_producto(id_papel, id_producto) values('".$id_papel."','".$cve_prod."');"); 
	$link = "Location: ficha_producto.php?numero=$cve_prod";
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
            <li><a href="ventas.php">Ventas</a></li>
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

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_producto.php">Buscar</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Ficha de Datos de Producto</h2>

		<?php
                $clave = $_GET['numero'];			
				//variable que acumulara el costo total
				$costo_total = 0;
                $producto = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$clave."'");
                $existe = $db->num_rows($producto);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del producto con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($producto))
                {
					$num_parte = $row['clave'];
					$nombre = $row['nombre'];
					$precio = $row['precio'];
					$f_precio = number_format($precio,4);
					$descripcion = $row['descripcion'];
					$id_cliente = $row['id_cliente'];
					$id_tamano = $row['tamano'];
					$id_tintas = $row['id_tintas'];
					$id_status = $row['id_status_prod'];
					$id_linea = $row['id_linea'];
					$id_unidad = $row['id_unidad'];
					$id_papel = $row['id_tipo_papel'];
					
					$id_laminado = $row['laminado'];
					switch ($id_laminado){
						case -1:
							$laminado = "No";
						break;
						case 1:
							$laminado = "Si";
						break;
					}
					
					$id_barnizado = $row['barnizado'];
					switch ($id_barnizado){
						case -1:
							$barnizado = "Ninguno";
						break;
						case 1:
							$barnizado = "UV";
						break;
						case 2:
							$barnizado = "Antiest&aacute;tico";
						break;
						case 3:
							$barnizado = "Base Agua";
						break;
					}
					
					$id_prensa = $row['prensa'];
					switch ($id_prensa){
						case -1:
							$prensa ="No hay información";
						break;
						case 1:
							$prensa ="Abdick";
						break;
						case 2:
							$prensa ="Forma Continua";
						break;
						case 3:
							$prensa ="Full Color";
						break;
					}
					
					$id_etiquetadora = $row['etiquetadora'];
					switch ($id_etiquetadora){
						case -1:
							$etiquetadora = "No";
						break;
						case 1:
							$etiquetadora = "Izquierdo";
						break;
						case 2:
							$etiquetadora = "Derecho";
						break;
					}
					
					//buscar la unidad
					$consulta_uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
					while ($row2 = $db->fetch_array($consulta_uni)){$unidad = $row2['unidad'];}
					
					//buscar el tamaño
					$consulta_tam = $db->consulta("SELECT *  FROM `tamano` WHERE `id_tamano` = '".$id_tamano."';");
					while ($row2 = $db->fetch_array($consulta_tam)){$tamano = $row2['tamano'];}
					
					//buscar el cliente
					$consulta_cli = $db->consulta("SELECT *  FROM `cliente` WHERE `id_cliente` = '".$id_cliente."';");
					while ($row2 = $db->fetch_array($consulta_cli)){$cliente = $row2['nombre'];}
					
					//buscar el status
					$consulta_sta = $db->consulta("SELECT *  FROM `status_producto` WHERE `id_status_producto` = '".$id_status."';");
					while ($row2 = $db->fetch_array($consulta_sta)){$status = $row2['status_producto'];}
					
					//buscar la linea
					$consulta_lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
					while ($row2 = $db->fetch_array($consulta_lin)){$linea = $row2['linea'];}
					
					//buscar el tipo de papel
					$consulta_pap = $db->consulta("SELECT *  FROM `tipo_papel` WHERE `id_tipo` = '".$id_papel."';");
					while ($row2 = $db->fetch_array($consulta_pap)){$papel = $row2['tipo'];}
					
					//buscar las tintas
					$consulta_tin = $db->consulta("SELECT *  FROM `tinta` WHERE `id_tinta` = '".$id_tintas."';");
					while ($row2 = $db->fetch_array($consulta_tin)){$tinta = $row2['tinta'];}
					
					
					
					
					
			?>
            
            <table align="center" border="0" width="100%">
            	<tr>
                    <td align="right">
					<?php 
                        //echo "<a href=\"#\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
						//echo "<a href=\"ficha_producto_pdf.php?numero=$clave\" target=\"blank\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
                    ?>                 
                     </td>
                </tr>
                <tr><td align="center">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos de Producto </B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="left"><b>Clave:</b>
							<?php echo $num_parte; ?></td>
                            <td align="left" colspan="2"><b>Estado:</b>
							<?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Nombre:</b>
							<?php echo $nombre; ?></td>
                        </tr>
                         <tr>
                            <td align="left" colspan="3"><b>Cliente:</b>
							<?php echo $cliente; ?></td>
                        </tr>
                         <tr>
                            <td align="left" colspan="3"><b>Precio: $</b>
							<?php echo $f_precio; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Descripci&oacute;n:</b>
							<?php echo $descripcion; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>L&iacute;nea:</b>
							<?php echo $linea; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Unidad de medida:</b>
							<?php echo $unidad; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Tama&ntilde;o:</b>
							<?php echo $tamano; ?></td>
                            <td align="left" colspan="2"><b>Tinta:</b>
							<?php echo $tinta; ?></td>
                        </tr>
                        
                        <?php 
						if($id_linea =="2"){
							echo "<tr><td colspan=\"3\"><b>Prensa: </b>$prensa</td></tr>";
						}else{
							echo "<tr><td colspan=\"3\"><b>Tipo de papel: </b>$papel</td></tr>";
							echo "<tr><td colspan=\"3\"><b>Laminado: </b>$laminado</td></tr>";
							echo "<tr><td colspan=\"3\"><b>Barnizado: </b>$barnizado</td></tr>";
							echo "<tr><td colspan=\"3\"><b>Etiquetadoa: </b>$etiquetadora</td></tr>";
						}
						
						
						?>
                        
                        
                        
                    </table>
                  </fieldset>
                  <?php
                  if($id_linea=="2"){
				  
				  ?>
                  <form method="post" name="material" id="material" action="ficha_producto.php" onsubmit="return validarMat();">
                  <input type="hidden" name="clave" id="clave" value="<?php echo $clave;?>" />
                  <fieldset><legend><b>Detalle de Copias</b></legend>
                        <table width="90%" align="center" border="0">
                        <td align="right"><b>Papel: </b></td><td align="left">
                        <select id="papel" name="papel">
                            <option value="-1">Selecciona Uno...</option>
                            <?php 
                                $lista_pap = $db->consulta("SELECT * FROM `tipo_papel`;");
                                    while ($row7 = $db->fetch_array($lista_pap))
                                    {
                                      $id_tipo_pap = $row7['id_tipo'];
                                      $papel = $row7['tipo'];
                                      echo "<option value=\"".$id_tipo_pap."\">".$papel."</option>";
                                    }
                            ?>
                        </select>
                        </td>
                        <td align="right"><input type="submit" class="submit_btn" name="agregar" id="agregar"value="Agregar >>"/></td>
                        </table><br />
                        
                    <?php 
						//muestro los tipos de papel regsitrados
						$cons = "SELECT * FROM `copias_producto` WHERE id_producto='$clave'";
						  $copias = $db->consulta($cons);
						  $existe = $db->num_rows($copias);
						  if($existe<=0){
						  		echo "<br><p align=\"center\"><b><i>No existen registros de copias para este producto.</i></b></p>";
						  }else{ 
					?>
							  
							 <table align="center" width="60%" cellspacing="0" id="tablesorter-mat" class="tablesorter">
								<thead>
									<tr align="center">
                                    	<th class="header">No.</th>
										<th class="header">Tipo de Papel</th>
                                        <th class="header">Eliminar</th>
									</tr>
								</thead>
								<tbody>
					<?php
								$i = 1;
								while ($row = $db->fetch_array($copias))
								{
									$id_registro = $row['id_registro'];
									$id_papel = $row['id_papel'];
									//consulto el nombre del papel
									$cons_p = "SELECT * FROM `tipo_papel` WHERE `id_tipo` =$id_papel;";
									$lista_p = $db->consulta($cons_p);
									
                                    while ($row9 = $db->fetch_array($lista_p))
                                    {
                                      $nombre_papel = $row9['tipo'];
                                    }
									
									$color = $row['color'];
											
									   echo "<tr><td align=\"center\">$i</td><td align=\"center\">$nombre_papel</td>
									   <td align=\"center\"><a class=\"icono\" title=\"Borrar Registro\" href=JavaScript:confirma(\"http://localhost/imprenta/produccion/borrar_copia.php?id_registro=$id_registro&producto=$clave\",\"$i\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
									  $i++;
										
								}
								echo "<tr></tbody></table>";
					
							
						  }
						  
						
					?>
                        
                    </fieldset>
                    </form>
                  <?php 
				  }
				  ?>
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