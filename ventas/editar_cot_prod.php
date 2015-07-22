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
			if (document.regProd.cant.value == "") {
				alert ('Debe escribir el margen de utilidad deseado');
				document.getElementById('margen').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 

<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['editar'])) {
	$cve_cot = $_GET['numero'];
	$tiva = $_GET['tiva'];
	$detalle = $_GET['key'];
	$cant = $_GET['cant'];
	$margen = $_GET['margen'];
	$cve_prod = $_GET['producto'];
	$cons_cos = $db->consulta("SELECT sum(`costo`) as suma FROM `producto_insumo` WHERE `id_producto`=$cve_prod;");
	while ($row3 = $db->fetch_array($cons_cos))
	{
	  $costo= $row3['suma'];
	}
	//echo "el costo ".$costo."<br>";
	//recibi las variables y ahora hare la consulta con el update
	$query = "UPDATE detalle_cotizacion SET id_producto='$cve_prod',costo='$costo', cantidad='$cant', margen='$margen', id_clase_iva='$tiva' WHERE id_detalle_cot ='$detalle';";
	echo "la consulta es ".$query."<br>";
	$consulta = $db->consulta($query);
	$link = "Location: ficha_cotizacion.php?numero=$cve_cot";
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
    
            <h2>Editar Cotizacion - Producto</h2>
            <?php
				$db = new MySQL();
				$cotizacion = $_GET['numero'];
				$detalle = $_GET['key'];
				
				//voy a consultar los datos del registro
				$consulta_reg = $db->consulta("SELECT *  FROM `detalle_cotizacion` WHERE `id_detalle_cot` = '".$detalle."';");
				while ($row2 = $db->fetch_array($consulta_reg)){
					$id_producto = $row2['id_producto'];
					$cantidad = $row2['cantidad'];
					$margen = $row2['margen'];
					$costo = $row2['costo'];
					$id_clase_iva = $row2['id_clase_iva'];
					
				}
				
				?>
				<form method="get" action="editar_cot_prod.php" name="regProd" onsubmit="return validarProd()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $cotizacion;?>">
                            <input type="hidden" name="key" id="key" value="<?php echo $detalle;?>">	
                            	<table border="0" width="650px">	
                                    <tr>
                                    	<td align="right"><b>Producto: </b></td>
                                    	<td align="left" valign="middle" colspan="9">
                                        <select id="producto" name="producto">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_prod = $db->consulta("SELECT * FROM `producto` ORDER BY id_producto ASC;");
													while ($row3 = $db->fetch_array($lista_prod))
													{
													  $id_prod = $row3['id_producto'];
													  $nombre = utf8_decode($row3['nombre']);
													  
													  //consultare el costo de producto para mostrarlo
													  $cons_cos = $db->consulta("SELECT sum(`costo`) as suma FROM `producto_insumo` WHERE `id_producto`=$id_prod;");
													  while ($row3 = $db->fetch_array($cons_cos))
														{
														  $costo_uni= $row3['suma'];
														  $costo_uni_for = number_format($costo_uni,2);
														}
													  
													  $desc = $nombre." - $".$costo_uni_for;
													  	if($id_prod==$id_producto){
															  echo "<option value=\"".$id_prod."\"selected>".$desc."</option>";
														}else{
															 echo "<option value=\"".$id_prod."\">".$desc."</option>";
														}
													
													
													}
											?>
                                        </select></td>
                                        </tr><tr>
                                        <td align="right"><b>Cantidad: </b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="7" value="<?php echo number_format($cantidad,2);?>" /><br /></td>
                                        <td>&nbsp;</td>
                                        <td align="right"><b>M&aacute;rgen: </b></td>
                                    	<td><input class="texto" type="text" id="margen" name="margen" size="5"value="<?php echo number_format($margen,2);?>" /> % <br /></td>
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