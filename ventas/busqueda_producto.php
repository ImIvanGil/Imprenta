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
		$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: { 7:{sorter: false},8:{sorter: false},9:{sorter: false}}});
		 $("#tablesorter-test").tablesorterPager({container: $("#pager")});
	} 
);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el producto n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
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
            <a href="#">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
        <SCRIPT LANGUAGE="JavaScript">
		  <!-- Funcion que valida que se hayan escrito los campos obligatorios-->
				function validar() {
				  if (document.buscar.par1.value == "-1"&document.buscar.par2.value == "-1"&document.buscar.par3.value == "-1") {
					  alert ('Debe seleccionar por lo menos un parametro de busqueda');
					  document.getElementById('par1').focus();
					  return false;
				  }
				  if (document.buscar.par1.value != "-1" && document.buscar.par1.value == document.buscar.par2.value) {
					  alert ('Seleccione un parametro de busqueda diferente');
					  document.getElementById('par2').focus();
					  return false;
				  }
				  if (document.buscar.par1.value != "-1" && document.buscar.par1.value == document.buscar.par3.value) {
					  alert ('Seleccione un parametro de busqueda diferente');
					  document.getElementById('par3').focus();
					  return false;
				  }
				  if (document.buscar.par3.value != "-1" && document.buscar.par3.value == document.buscar.par2.value) {
					  alert ('Seleccione un parametro de busqueda diferente');
					  document.getElementById('par3').focus();
					  return false;
				  }
				  if (document.buscar.par1.value != "-1"&document.buscar.dat1.value == "") {
					  alert ('Debe escribir el dato para el parametro seleccionado');
					  document.getElementById('dat1').focus();
					  return false;
				  }
				  if (document.buscar.par2.value != "-1"&document.buscar.dat2.value == "") {
					  alert ('Debe escribir el dato para el parametro seleccionado');
					  document.getElementById('dat2').focus();
					  return false;
				  }
				  if (document.buscar.par3.value != "-1"&document.buscar.dat3.value == "") {
					  alert ('Debe escribir el dato para el paramtero seleccionado');
					  document.getElementById('dat3').focus();
					  return false;
				  }
				  if (document.buscar.par1.value == "-1"&document.buscar.dat1.value != "") {
					  alert ('Debe especificar el tipo de buqueda que desea hacer');
					  document.getElementById('par1').focus();
					  return false;
				  }
				  if (document.buscar.par2.value == "-1"&document.buscar.dat2.value != "") {
					  alert ('Debe especificar el tipo de buqueda que desea hacer');
					  document.getElementById('par2').focus();
					  return false;
				  }
				  if (document.buscar.par3.value == "-1"&document.buscar.dat3.value != "") {
					  alert ('Debe especificar el tipo de buqueda que desea hacer');
					  document.getElementById('par3').focus();
					  return false;
				  }
				  
				  
				  return true;
			   	}
		</SCRIPT> 
        
    
            <h2>B&uacute;squeda de Insumos</h2>
            <div id="data_form">
            
            <form method="get" action="busqueda_producto.php" name="buscar" onSubmit="return validar()">
                <table align="center" width="500px" border="0px">
                    
                    <tr><td>
                      <BR>
                      <fieldset>								  
                        <table border="0" width="500px">	
                        <tr><td align="right" valign="middle">
                        	<select id="par1" name="par1">
                                <option value="-1">Selecciona Uno...</option>
                                <option value="id_producto">Numero</option>
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripcion</option>
                            </select></td>
                        <td width="50%"> <input class="texto" type="text" id="dat1" name="dat1" size="40" /><br /></td></tr>
                        <tr><td align="right" valign="middle">
                        	<select id="par2" name="par2">
                                <option value="-1">Selecciona Uno...</option>
                                <option value="id_producto">Numero</option>
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripcion</option>
                            </select></td>
                        <td width="50%"> <input class="texto" type="text" id="dat2" name="dat2" size="40" /><br /></td></tr>
                        <tr><td align="right" valign="middle">
                        	<select id="par3" name="par3">
                                <option value="-1">Selecciona Uno...</option>
                                <option value="id_producto">Numero</option>
                                <option value="nombre">Nombre</option>
                                <option value="descripcion">Descripcion</option>
                            </select></td>
                        <td width="50%"> <input class="texto" type="text" id="dat3" name="dat3" size="40" /><br /></td></tr>
                        </table>
                      </fieldset>
                    </td></tr>
    
                    <tr><td colspan="2"align="right"> 	   
                     <BR> <input class="submit_btn reset" type="submit" name="buscar" id="buscar" value="Buscar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                     <input class="submit_btn reset" type="reset" value="Cancelar"/>
                    </td></tr>
                    
                    </table>  
                </form>
        
        
            </div>
            <!-- EN ESTA SECCION HARE LA CONSULTA Y MOSTRARE LOS RESULTADOS SI EXISTEN -->
			   <?php
                //+++++++++++++++++++++++++++++++SI SELECCIONO BUSCAR ++++++++++++++++++++++++++++++++++++++++
                    if (isset($_GET['buscar'])) {
                        
                        $par1 = $_GET['par1'];
						$dat1 = $_GET['dat1'];
                        $par2 = $_GET['par2'];
						$dat2 = $_GET['dat2'];
						$par3 = $_GET['par3'];
						$dat3 = $_GET['dat3'];
                        // armare la consulta
						$consulta = "select * from `producto`";
						
						//reviso si hay parametros para buscar
						if($dat1!=""){
							$pars = $par1." LIKE '%".$dat1."%'";
							if($dat2!=""){
								$pars = $pars." OR ".$par2." LIKE '%".$dat2."%'";
							}
							if($dat3!=""){
								$pars = $pars." OR ".$par3." LIKE '%".$dat3."%'";
							}
						}else{
							if($dat2!=""){
								$pars = $par2." LIKE '".$dat2."'";
								if($dat3!=""){
									$pars = $pars." OR ".$par3." LIKE '%".$dat3."%'";
								}
							}else{
								if($dat3!=""){
									$pars = $par3." LIKE '%".$dat3."%'";
								}
							}
						}
						
						//ahora le agrego el where a la consulta
						if($pars!=""){
							$consulta = $consulta." WHERE ".$pars." ORDER BY id_producto ASC;";
						}
						//echo "la consulta es ".$consulta."<br>";
						
                        $lista_res = $db->consulta($consulta);
                        $cuenta_res= $db->num_rows($lista_res);
                        if($cuenta_res==0){
                            //si la lista de proveedoress no tiene valores
                            echo "<p align=\"center\">Su b&uacute;squeda no produjo resultados</p>";
                        }else{
                        	//si existen registros con esas caracteristicas se muestran los resultados
						?>
							<table id="tablesorter-test" class="tablesorter" cellspacing="0">
                            <thead>
                                <tr align="center">
                                    <th class="header">No.</th>
                                    <th class="header">Clave</th>
                                    <th class="header">Nombre</th>
                                    <th class="header">Descripci&oacute;n</th>
                                    <th class="header">Tama&ntilde;o</th>
                                    <th class="header">Copias</th>
                                    <th class="header">Tinta</th>
                                    <th>Ficha</th>
                                    <th>Editar</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody>
                            	<?php 
									while ($row = $db->fetch_array($lista_res))
										{
										$clave = $row['id_producto'];
										$cve = $row['clave'];
										$nombre = $row['nombre'];
										//$contacto = $row['contacto'];
										$descripcion = $row['descripcion'];
										$id_tamano = $row['id_tamano'];
										$id_copias = $row['id_copias'];
										$id_tintas = $row['id_tintas'];
										
										//buscar el tamaño
										$consulta_tam = $db->consulta("SELECT *  FROM `tamano` WHERE `id_tamano` = '".$id_tamano."';");
										while ($row2 = $db->fetch_array($consulta_tam)){$tamano = $row2['tamano'];}
										
										//buscar las copias
										$consulta_cop = $db->consulta("SELECT *  FROM `copias` WHERE `id_copias` = '".$id_copias."';");
										while ($row2 = $db->fetch_array($consulta_cop)){$copias = $row2['copias'];}
										
										//buscar las tintas
										$consulta_tin = $db->consulta("SELECT *  FROM `tinta` WHERE `id_tinta` = '".$id_tintas."';");
										while ($row2 = $db->fetch_array($consulta_tin)){$tinta = $row2['tinta'];}
										
										 echo "<tr align=\"center\">
										 <td class=\"listado\">".$clave."</td>
										 <td class=\"listado\">".$cve."</td>
										 <td>".utf8_decode($nombre)."</td>
										 <td class=\"listado\">".utf8_decode($descripcion)."</td>
										 <td class=\"listado\">".$tamano."</td>
										 <td>".$copias."</td>
										 <td class=\"listado\">".$tinta."</td>
										 <td class=\"listado\"><a class=\"icono\" href=\"ficha_producto.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" align=\"center\"></a></td>
										 <td class=\"listado\"><a class=\"icono\" href=\"editar_producto.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
										 <td class=\"listado\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/ventas/borrar_producto.php?numero=$clave\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
										 
										 </tr>";
										
										}
								?>
							</tbody>
							</table>
                                <br /> <br />
                                 <div id="pager" class="tablesorterPager" align="center">
                                <form>
                                    <img src="../images/first.png" class="first"/>
                                    <img src="../images/prev.png" class="prev"/>
                                    <input type="text" class="pagedisplay"/>
                                    <img src="../images/next.png" class="next"/>
                                    <img src="../images/last.png" class="last"/>
                                    <select class="pagesize">
                                        <option selected="selected"  value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option  value="40">40</option>
                                    </select>
                                </form>
                               </div>
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