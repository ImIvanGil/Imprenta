<?php 
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
            <li><a href="../ventas/ventas.php">Ventas</a></li>
            <li><a href="../compras/compras.php">Compras</a></li>
            <li><a href="../produccion/produccion.php">Producci&oacute;n</a></li>
            <li><a href="../administracion/admin.php">Administraci&oacute;n</a></li>
            
			<li><a href="#" class="current">Configuraci&oacute;n</a></li>
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
    
            <h2>Configuraci&oacute;n del Sistema</h2>
            
            
            <table cellspacing="0" border="0" width="100%">
            	<tr>
                    <td colspan="2"><h6>Generales</h6></td>
                    <td>&nbsp;</td>
                    <td colspan="2"><h6>Par&aacute;metros</h6></td>
                </tr>
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_19.png" alt="image 3" width="35px" /></td>
                    <td><a href="empresa.php">Datos Generales</a></td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_55.png" alt="image 3" width="35px" /></td>
                    <td><a href="formas_pago.php">Formas de Pago</a></td>
                </tr>
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_04.png" alt="image 3" width="35px" /></td>
                    <td><a href="usuarios.php">Usuarios</a></td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_52.png" alt="image 3" width="35px" /></td>
                    <td><a href="metodos_pago.php">M&eacute;todos de Pago</a></td>
                </tr>
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_60.png" alt="image 3" width="35px" /></td>
                    <td><a href="timbres.php">Consumo de timbres</a></td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_20.png" alt="image 3" width="35px" /></td>
                    <td><a href="colores.php">Colores de tinta</a></td>
                </tr>
                <tr>
                    <td width="10%"><img src="../images/iconos/onebit_73.png" alt="image 3" width="35px" /></td>
                    <td><a href="ciudades.php">Ciudades</a></td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_66.png" alt="image 3" width="35px" /></td>
                    <td><a href="presentaciones.php">Presentaciones</a></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_14.png" alt="image 3" width="35px" /></td>
                    <td><a href="unidades.php">Unidades de Medida</a></td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="10%"><img src="../images/iconos/onebit_39.png" alt="image 3" width="35px" /></td>
                    <td><a href="papel.php">Tipos de Papel</a></td>
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