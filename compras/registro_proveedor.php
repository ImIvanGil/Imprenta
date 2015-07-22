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
            <li><a href="proveedores.php" class="current">Proveedores</a></li>
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
                
            <img src="../images/iconos/onebit_64.png" alt="image 3" />
            <a href="nuevo_proveedor.php">Nuevo Proveedor</a>
            
        </div>
        <div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_70.png" alt="image 3" />
            <a href="proveedores.php">Lista Proveedores</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_proveedor.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Proveedor</h2>
            
            
            <?php 
																	
				$operacion = $_POST['operacion'];
				$nombre = $_POST['nombre'];
				//$nombre = utf8_decode($nombre);
				$contacto = $_POST['contacto'];
				//$contacto = utf8_decode($contacto);
				$calle = $_POST['calle'];
				$num = $_POST['num'];
				$colonia = $_POST['colonia'];
				$cp = $_POST['cp'];
				$pais = $_POST['pais'];
				$estado = $_POST['estado'];
				$ciudad = $_POST['ciudad'];
				$tel = $_POST['tel'];
				$tel2 = $_POST['tel2'];
				$tel3= $_POST['tel3'];
				$mail = $_POST['mail'];
				$rfc = $_POST['rfc'];
				$dias = $_POST['dias'];
				$rfc = strtoupper($rfc);
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es un proveedor nuevo
					$consulta = $db->consulta("insert into proveedor(nombre, calle, numero, colonia, ciudad, codigo_postal, estado, pais, telefono, telefono1, telefono2, correo, rfc, contacto,dias) values('".$nombre."','".$calle."','".$num."','".$colonia."','".$ciudad."','".$cp."','".$estado."','".$pais."','".$tel."','".$tel2."','".$tel3."','".$mail."','".$rfc."','".$contacto."','".$dias."');");  
					
					//$nombre = utf8_encode($nombre);
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El proveedor ".$nombre." ha sido registrado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"proveedores.php\">Regresar</a></p>";
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar proveedor
					$query = "UPDATE proveedor SET nombre='$nombre',calle='$calle', numero='$num', colonia='$colonia', ciudad='$ciudad', estado='$estado', pais='$pais', codigo_postal='$cp', telefono='$tel', telefono1='$tel2', telefono2='$tel3', correo='$mail', rfc='$rfc', contacto='$contacto', dias='$dias' WHERE id_proveedor ='$numero_original';";
							
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El proveedor ".$nombre." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"proveedores.php\">Regresar</a></p>";
				
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