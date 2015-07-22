<?php 
ob_start();
include("../adminuser/adminpro_class.php");
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
			if (document.regProd.unitario.value == "") {
				alert ('Debe escribir el precio unitario');
				document.getElementById('unitario').focus();
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
if (isset($_GET['editar'])) {
	$cve_fac = $_GET['numero'];
	$cve_det = $_GET['key'];
	$cant = $_GET['cant'];
	$cve_prod = $_GET['producto'];
	$unitario = $_GET['unitario'];
	$tiva = $_GET['tiva'];
	
	//recibi las variables y ahora hare la consulta con el update
	$query = "UPDATE detalle_factura SET id_producto='$cve_prod',unitario='$unitario', cantidad='$cant', id_clase_iva='$tiva' WHERE id_detalle_fact ='$cve_det';";
	echo "la consulta es ".$query."<br>";
	$consulta = $db->consulta($query);
	$link = "Location: ficha_factura.php?numero=$cve_fac";
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
            <li><a href="facturas.php" class="current">Facturas</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="insumos.php">Insumos</a></li>
            <!-- li><a href="reportes.php">Reportes</a></li -->        
			<li><a href="administracion.php">Configuraci&oacute;n</a></li>
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
                
            <img src="../images/iconos/onebit_78.png" alt="image 3" />
            <a href="nueva_factura.php">Nueva Factura</a>
            
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
    
            <h2>Editar Factura - Producto</h2>
            <?php
				$db = new MySQL();
				$factura = $_GET['numero'];
				$detalle = $_GET['key'];
				
				//voy a consultar los datos del registro
				$consulta_reg = $db->consulta("SELECT *  FROM `detalle_factura` WHERE `id_detalle_fact` = '".$detalle."';");
				while ($row2 = $db->fetch_array($consulta_reg)){
					$id_producto = $row2['id_producto'];
					$cantidad = $row2['cantidad'];
					$unitario = $row2['unitario'];
					$id_clase_iva = $row2['id_clase_iva'];
					
				}
				
				?>
				<form method="get" action="editar_fac_prod.php" name="regProd" onsubmit="return validarProd()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $factura;?>">
                            <input type="hidden" name="key" id="key" value="<?php echo $detalle;?>">	
                            	<table border="0" width="700px">	
                                    <tr>
                                    	<td><b>Producto: </b></td>
                                    	<td align="right" valign="middle">
                                        <select id="producto" name="producto">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_prod = $db->consulta("SELECT * FROM `producto` ORDER BY id_producto ASC;");
													while ($row3 = $db->fetch_array($lista_prod))
													{
													  $id_prod = $row3['id_producto'];
													  $nombre = $row3['nombre'];
													  
													  	if($id_prod==$id_producto){
															  echo "<option value=\"".$id_prod."\"selected>".$nombre."</option>";
														}else{
															 echo "<option value=\"".$id_prod."\">".$nombre."</option>";
														}
													
													
													}
											?>
                                        </select></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Cantidad: </b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="7" value="<?php echo number_format($cantidad,2);?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>Precio Unitario: </b></td>
                                    	<td><input class="texto" type="text" id="unitario" name="unitario" size="7" value="<?php echo number_format($unitario,2);?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        
                                        
                                    	<td><b>Tipo I.V.A.: </b></td>
                                    	<td align="right" valign="middle">
                                        <select id="tiva" name="tiva">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_iva = $db->consulta("SELECT * FROM `clase_iva` ORDER BY id_clase ASC;");
													while ($row3 = $db->fetch_array($lista_iva))
													{
													  $id_clase = $row3['id_clase'];
													  $clase = $row3['clase_iva'];
													  
													  if($id_clase==$id_clase_iva){
															  echo "<option value=\"".$id_clase."\"selected>".$clase."</option>";
														}else{
															 echo "<option value=\"".$id_clase."\">".$clase."</option>";
														}
													
													
													}
											?>
                                        </select></td>
                                        <td>&nbsp;</td>
                                        <td><input class="submit_btn reset" type="submit" name="editar" id="editar" value="Actualizar"/></td>
                                    </tr>
                                </table>
                            </form>
				
	
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