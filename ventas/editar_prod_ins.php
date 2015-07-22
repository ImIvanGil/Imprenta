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


<!--VALIDAR EL FORMULARIO DE insumo -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarIns() {
			if (document.regIns.insumo.value == "-1") {
				alert ('Debe seleccionar un insumo');
				document.getElementById('insumo').focus();
				return false;
			}
			if (document.regIns.cant.value == "") {
				alert ('Debe escribir la cantidad requerida del insumo');
				document.getElementById('cant').focus();
				return false;
			}
			return true;
	}
</SCRIPT> 


<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE INSUMO LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['editar'])) {
	$registro = $_GET['key'];
	$cve_prod = $_GET['numero'];
	$cant = $_GET['cant'];
	$cve_ins = $_GET['insumo'];
	//voy a consultar el id del insumo y de la presentacion
	$consulta_ins = $db->consulta("SELECT *  FROM `insumo_presentacion` WHERE `id_insumo_pres` = '".$cve_ins."';");
	while ($row2 = $db->fetch_array($consulta_ins)){
		$id_insu = $row2['id_insumo'];
		$id_presenta = $row2['id_presentacion'];
		$costo_p = $row2['costo'];
	}
	//Calculo el costo del insumo por la cantidad
	$costo_prod = $costo_p * $cant;
	
	//recibi las variables y ahora hare la consulta con el update
	$query = "UPDATE producto_insumo SET id_insumo='$id_insu',id_presentacion='$id_presenta', cantidad='$cant', costo='$costo_prod' WHERE id_prod_insumo ='$registro';";
	echo "la consulta es ".$query."<br>";
	$consulta = $db->consulta($query);
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
    
            <h2>Editar Producto - Insumo</h2>
            <?php
				$db = new MySQL();
				$producto = $_GET['numero'];
				$registro = $_GET['key'];
				
				//voy a consultar los datos del registro
				$consulta_reg = $db->consulta("SELECT *  FROM `producto_insumo` WHERE `id_prod_insumo` = '".$registro."';");
				while ($row2 = $db->fetch_array($consulta_reg)){
					$id_insu = $row2['id_insumo'];
					$id_presenta = $row2['id_presentacion'];
					$cantidad = $row2['cantidad'];
					$cantidad = number_format($cantidad,2);
					$compara1 = $id_insu.$id_presenta;
				}
				
				?>
				<form method="get" action="editar_prod_ins.php" name="regIns" onsubmit="return validarIns()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $producto;?>">
                            <input type="hidden" name="key" id="key" value="<?php echo $registro;?>">	
                            	<table border="0" width="550px">	
                                    <tr>
                                    	<td><b>Insumo: </b></td>
                                    	<td align="right" valign="middle">
                                        <select id="insumo" name="insumo">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_pres = $db->consulta("SELECT * FROM `insumo_presentacion`;");
													while ($row3 = $db->fetch_array($lista_pres))
													{
														$id_insumo_pres = $row3['id_insumo_pres'];
														$insu1 = $row3['id_insumo'];
														$pres1 = $row3['id_presentacion'];
														$costo = $row3['costo'];
													 	$costo = number_format($costo,2);
														
														  //consultare el nombre del insumo
														  $consulta_ins = $db->consulta("SELECT *  FROM `insumo` WHERE `id_insumo` = '".$insu1."';");
														   while ($row2 = $db->fetch_array($consulta_ins)){$nom_ins = $row2['nombre'];}
														   //consultare el nombre de la presentacion
														  $consulta_pres = $db->consulta("SELECT *  FROM `presentacion` WHERE `id_presentacion` = '".$pres1."';");
														   while ($row2 = $db->fetch_array($consulta_pres)){$nom_pres = $row2['nombre'];}
														  $desc = $nom_ins." - ".$nom_pres." - $".$costo;
														
														
														
														$compara2 = $insu1.$pres1;
														
														
														if($compara1==$compara2){
															  echo "<option value=\"".$id_insumo_pres."\"selected>".$desc."</option>";
														}else{
															  echo "<option value=\"".$id_insumo_pres."\">".$desc."</option>";
														}
													  
													}
											?>
                                        </select></td>
                                        <td width="15%">&nbsp;</td>
                                        <td width="25%" align="right"><b>Cantidad:</b></td>
                                    	<td><input class="texto" type="text" id="cant" name="cant" size="10" value="<?php echo $cantidad;?>" /><br /></td>
                                        <td width="15%">&nbsp;</td>
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