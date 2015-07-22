<?php 
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();

$sqlUser = "SELECT userGroup FROM `myuser` WHERE `userName`='$curUser'";
$consultaUser = $db->consulta($sqlUser);
$rowUser = mysql_fetch_array($consultaUser);
$grupo=$rowUser['userGroup'];

?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />

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
       		<?php 
			if($grupo==1){
            		echo "<li><a href=\"../inicio.php\">Inicio</a></li>";
				}elseif($grupo==2){
					echo "<li><a href=\"../inicio_2_ventas.php\">Inicio</a></li>";
				}
			
			if($grupo==1||$grupo==2){
            	echo"<li><a href=\"#\" class=\"current\">Ventas</a></li>";
			}
            
			if($grupo==1){
				echo"<li><a href=\"../compras/compras.php\">Compras</a></li>"; 
				}
			
            if($grupo==1||$grupo==2){
            	echo "<li><a href=\"../produccion/produccion.php\">Producci&oacute;n</a></li>";
			}
			if($grupo==1){
            	echo "<li><a href=\"../administracion/admin.php\">Administraci&oacute;n</a></li>";
			}
			if($grupo==1){
				echo"<li><a href=\"../configuracion/administracion.php\">Configuraci&oacute;n</a></li>";
			}
            
            ?>
			<li><a href="#" onclick="javascript:document.forms['salir'].submit();">Salir</a></li>
         
         <?php
			echo '<form action="../adminuser/logout.php" method="POST" name="salir" id="salir">
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
    
    <div id="templatemo_service_line">
    
    	        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
    
            <h2>Clientes y ventas</h2>
            
            
            <table cellspacing="0" border="0" width="100%">
            	
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_17.png" alt="image 3" width="35px" /></td>
                    <td><a href="clientes.php">Clientes</a></td>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_77.png" alt="image 3" width="35px" /></td>
                    <td><a href="remisiones.php">Remisiones</a></td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_54.png" alt="image 3" width="35px" /></td>
                    <td><a href="../produccion/productos.php">Productos</a></td>
                </tr>
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_39.png" alt="image 3" width="35px" /></td>
                    <td><a href="facturas.php">Facturaci&oacute;n</a></td>
                    <td>&nbsp;</td>
                                        <td width="10%"><img src="../images/iconos/onebit_64.png" alt="image 3" width="35px" /></td>
                    <td><a href="cobros_clientes.php">Cobranza</a></td>
                    
                </tr>
                <tr>
                	<td width="10%"><img src="../images/iconos/onebit_52.png" alt="image 3" width="35px" /></td>
                    <td><a href="notas_credito.php">Notas de Cr&eacute;dito</a></td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_66.png" alt="image 3" width="35px" /></td>
                    <td><a href="reportes.php">Reportes</a></td>
                    
                </tr>
                
            </table>
        
        
        
            
            
       	  <div class="cleaner_h20"></div>
            
            
		</div>
        
        <div class="col_260 col_last">
        
            
            
            
            
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