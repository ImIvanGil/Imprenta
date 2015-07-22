<?php include("adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="images/favicon.ico">
<title>Sistema de ERP</title>
<link href="templatemo_style.css" rel="stylesheet" type="text/css" />

<script language="javascript" src="js/jquery.js"></script> 
<script type="text/javascript" src="js/jquery-latest.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="js/docs.js"></script>

<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script src="js/jquery.ui.draggable2.js"></script>

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
			$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra']});
		} 
	);
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
            <li><a href="facturas.php">Facturas</a></li>
            <li><a href="clientes.php">Clientes</a></li>
            <li><a href="productos.php">Productos</a></li>
            <li><a href="insumos.php">Insumos</a></li>
            <!-- li><a href="reportes.php">Reportes</a></li -->        
			<li><a href="administracion.php" class="current">Configuraci&oacute;n</a></li>
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
    
    
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
    
            <h2>Bit&aacute;cora de Operaciones</h2>
            
            
            <table id="tablesorter-test" class="tablesorter" cellspacing="0">
            <thead>
                <tr align="center">
                    <th class="header">No.</th>
                    <th class="header">Usuario</th>
                    <th class="header">Fecha</th>
                    <th class="header">Operaci&oacute;n</th>
                    <th class="header">Descripci&oacute;</th>
                </tr>
            </thead>
            <tbody>
            
				<?php 
                    $db = new MySQL();
    				$i = 1;
                    $lista_facturas = $db->consulta("SELECT * FROM `bitacora` ORDER BY id_operacion;");
                    while ($rowf = $db->fetch_array($lista_facturas))
                        {
							$clave = $rowf['id_operacion'];
							$usuario = $rowf['usuario'];
							$fecha = $rowf['fecha'];
							$operacion = $rowf['operacion'];
							$descripcion = $rowf['descripcion'];
							
							
							
							 echo "<tr align=\"center\">
							 <td class=\"listado\">".$i."</td>
							 <td class=\"listado\">".$usuario."</td>
							 <td align=\"left\">".$fecha."</td>
							 <td class=\"listado\">".$operacion."</td>
							 <td class=\"listado\">".$descripcion."</td></tr>";
								 
							
							$i++;
                        }
						
                ?>
            </tbody>
            </table>
        <br /> <br />
         <div id="pager" class="tablesorterPager" align="center">
        <form>
            <img src="images/first.png" class="first"/>
            <img src="images/prev.png" class="prev"/>
            <input type="text" class="pagedisplay"/>
            <img src="images/next.png" class="next"/>
            <img src="images/last.png" class="last"/>
            <select class="pagesize">
                <option selected="selected"  value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option  value="40">40</option>
            </select>
        </form>
       </div>
        
        
            
            
       	  <div class="cleaner_h20"></div>
            
            
		</div>
        
        <div class="col_260 col_last">
        
            <h2>Opciones</h2>
            
        	<div class="sb_news_box">
            	<ul class="tmo_list col_260">
                     <li><span>&raquo;</span><a href="#">Exportar a Excel</a></li>
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