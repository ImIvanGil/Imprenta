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

<!--VALIDAR EL FORMULARIO DE PRESENTACION -->
 <SCRIPT LANGUAGE="JavaScript">
	<!-- Funcion que valida que se hayan escrito los campos obligatorios-->
	function validarPres() {
			if (document.regPres.presentacion.value == "-1") {
				alert ('Debe seleccionar un insumo como dependencia');
				document.getElementById('dependencia').focus();
				return false;
			}
			
			return true;
	}
</SCRIPT> 


<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRESENTACION LOS VOY A GUARDAR  -->
 <?php
if (isset($_GET['editar'])) {
	$cve_ins = $_GET['numero'];
	$cve_dep = $_GET['dependencia'];
	$dep_orig = $_GET['pres'];	

	//recibi las variables y ahora hare la consulta con el update
	$query = "UPDATE insumo_dependencias SET id_accesorio='$cve_dep' WHERE id_accesorio ='$dep_orig' AND id_primario = '$cve_ins';";
	echo "la consulta es ".$query."<br>";
	$consulta = $db->consulta($query);
	$link = "Location: ficha_insumo.php?numero=$cve_ins";
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
    
            <h2>Editar Dependencia - Insumo</h2>
            <?php
				$db = new MySQL();
				$insumo = $_GET['numero'];
				$dependencia = $_GET['pres'];
				
				//consulta el nombre de la dependencia
				$sql_nombre = "SELECT nombre FROM insumo WHERE id_insumo='".$dependencia."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombreDep=$row_nombre['nombre'];
				
				//consulta el nombre del insumo
				$sql_nombre = "SELECT nombre FROM insumo WHERE id_insumo='".$insumo."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombreIns=$row_nombre['nombre'];
				
				
				?>
				<form method="get" action="editar_insumo_dep.php" name="regPres" onsubmit="return validarPres()">
                            <input type="hidden" name="numero" id="numero" value="<?php echo $insumo;?>">
                            <input type="hidden" name="pres" id="pres" value="<?php echo $dependencia;?>">	
                            	<table border="0" width="550px">	
                                    <tr>
                                    	<td><b>Dependencia: </b></td>
                                    	<td align="right" valign="middle">
                                        <select id="dependencia" name="dependencia">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_pres = $db->consulta("SELECT * FROM `insumo` WHERE tipo=2;");
													while ($row3 = $db->fetch_array($lista_pres))
													{
														$id_dep = $row3['id_insumo'];
														$dep1 = $row3['nombre'];
														if($id_dep==$dependencia){
															  echo "<option value=\"".$id_dep."\"selected>".$dep1."</option>";
														}else{
															  echo "<option value=\"".$id_dep."\">".$dep1."</option>";
														}
													  
													}
											?>
                                        </select></td>
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