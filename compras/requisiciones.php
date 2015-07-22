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

<!-- script que hace el requisicionamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
			$("#tablesorter-test").tablesorter({sortList:[[0,1]], widthFixed: true, widgets: ['zebra'], headers: { 4:{sorter: false},5:{sorter: false},6:{sorter: false}}});
			 $("#tablesorter-test").tablesorterPager({container: $("#pager")});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar o cancelar la requisicion de compra n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
</script>

<SCRIPT LANGUAGE="JavaScript">
<!-- Funcion que valida que se hayan escrito los campos obligatorios en el formulario de buscar-->
	function validarBusqueda() {
		if (document.busqueda.parametro.value == "") {
			alert ('Debe escribir un valor en el campo de busqueda');
			document.getElementById('parametro').focus();
			return false;
		}
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
            <li><a href="compras.php">Compras</a></li>
            <li><a href="requisiciones.php" class="current">Requisiciones</a></li>
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
                
            <img src="../images/iconos/onebit_39.png" alt="image 3" />
            <a href="nueva_req.php">Nueva Requisici&oacute;n</a>
            
        </div>

         <div class="sb_box">
            <a href="#">Buscar</a>
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <form method="get" action="requisiciones.php" name="busqueda" id="busqueda" onSubmit="return validarBusqueda()"> 
            <input type="text" class="texto" width="30" name="parametro" id="parametro" />
            <input class="submit_btn reset" type="submit" value="Ir"/>
            </form>
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
    
            <h2>Requisiciones de Compra</h2>
            
            
            <table id="tablesorter-test" class="tablesorter" cellspacing="0">
            <thead>
                <tr align="center">
                    <th class="header">No.</th>
                    <th class="header">Proveedor</th>
                    <th class="header">Fecha</th>
                    <th class="header">Estado</th>
                    <th>Ver</th>
                    <th>Editar</th>
                    <th>Eliminar/<br />Cancelar</th>
                </tr>
            </thead>
            <tbody>
            
				<?php 
                    $db = new MySQL();
    				$i = 1;
					
					if (isset($_GET['parametro'])) {
						  $buscar = $_GET['parametro'];
						  $cons ="SELECT * FROM `requisicion` WHERE `id_requisicion`= '$buscar' OR `id_proveedor` in (SELECT `id_proveedor` FROM `proveedor` WHERE `nombre` like '%$buscar%') ORDER BY id_requisicion DESC;";
						  $lista_requisicion = $db->consulta($cons);
						  
						  $mensaje = "Resultados de la b&uacute;squeda que contienen la palabra clave <b>$buscar</b>";
					  }else{
						  $lista_requisicion = $db->consulta("SELECT * FROM `requisicion` ORDER BY id_requisicion DESC LIMIT 20;");
						  $mensaje = "Se muestran las ultimas 20 requisisciones generadas, para localizar un registro en espec&iacute;fico, use el campo de b&uacute;squeda.";
					  }
					  
					echo "<span><i>$mensaje<i></span>";
                    while ($rowf = $db->fetch_array($lista_requisicion))
                        {
							$clave = $rowf['id_requisicion'];
							$fecha = $rowf['fecha'];
							
							$id_prov= $rowf['id_proveedor'];
							//buscar el nombre del proveedor
							$sql_pro = "SELECT * FROM proveedor where `id_proveedor`='".$id_prov."'";
							$consulta_prov = $db->consulta($sql_pro);
							while($row_prov = mysql_fetch_array($consulta_prov)){
								$nom_proveedor=$row_prov['nombre'];
							}
							
							
							
							$id_status = $rowf['id_status'];
							//buscar el status
							$consulta_sta = $db->consulta("SELECT *  FROM `status_orden` WHERE `id_status` = '".$id_status."';");
							while ($row3 = $db->fetch_array($consulta_sta)){
								$status = $row3['status'];
							}
							
							
							 echo "<tr align=\"center\">
							 <td class=\"listado\">".$clave."</td>
							 <td align=\"left\">".utf8_decode($nom_proveedor)."</td>
							 <td class=\"listado\">".$fecha."</td>
							 <td class=\"listado\">".$status."</td>
							 <td class=\"listado\"><a class=\"icono\" title=\"Ver Orden\" href=\"ficha_requisicion.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" align=\"center\"></a></td>";
							 if($id_status==1){
								 echo "<td class=\"listado\"><a class=\"icono\" title=\"Editar Requisicion\" href=\"editar_requisicion.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
							 <td class=\"listado\"><a class=\"icono\" title=\"Borrar Requisicion\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_requisicion.php?numero=$clave\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
							 </tr>";
							 }else{
								 if($id_status==2){
									 echo "<td class=\"listado\">Operacion <br>No Permitida</td>
							 		<td class=\"listado\"><a class=\"icono\" title=\"Cancelar Requisicion\"  href=JavaScript:confirma(\"http://localhost/imprenta/compras/cancelar_requisicion.php?numero=$clave\",\"$clave\");><img src=\"../images/iconos/onebit_35.png\" width=\"24px\" align=\"center\"></a></td></tr>";
								 }else{
									 echo "<td class=\"listado\">Operacion <br>No Permitida</td>
							 		<td class=\"listado\">Operacion <br>No Permitida</td></tr>";
								 }
								 
							 }
							 
							 
							$i++;
                        }
						
                ?>
            </tbody>
            </table>
        <br /> <br />
        
        
        
       	  <div class="cleaner_h20"></div>
            
            
		</div>
        
        <div class="col_260 col_last">
        
            <h2>Opciones</h2>
            
        	<div class="sb_news_box">
            	<ul class="tmo_list col_260">
                     <li><span>&raquo;</span><a href="lista_requisiciones.php">Exportar a Excel</a></li>
               </ul>
               
            </div>
            
            
            
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