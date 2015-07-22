<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>
<link rel="shortcut icon" href="images/favicon.ico">
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
            <li><a href="../ventas/ventas.php">Ventas</a></li>
            <li><a href="../produccion/produccion.php">Producci&oacute;n</a></li>
            <li><a href="../admin.php">Admnistraci&oacute;n</a></li>
            <li><a href="#">Compras</a></li>
            
			<li><a href="administracion.php" class="current">Configuraci&oacute;n</a></li>
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
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Empresa</h2>
            
            
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
				$mail = $_POST['mail'];
				$regimen = $_POST['regimen'];
				$rfc = $_POST['rfc'];
				$rfc = strtoupper($rfc);
				
				$db = new MySQL(); 
														
				if ($operacion =="registrar"){
					//si es un empresa nueva
					$consulta = $db->consulta("insert into empresa(nombre, calle, numero, colonia, ciudad, codigo_postal, estado, pais, telefono, correo, rfc, contacto,regimen) values('".$nombre."','".$calle."','".$num."','".$colonia."','".$ciudad."','".$cp."','".$estado."','".$pais."','".$tel."','".$mail."','".$rfc."','".$contacto."','".$regimen."');");
					
					//$nombre = utf8_encode($nombre);
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>la empresa ".$nombre." ha sido registrada exitosamente. Gracias! </strong></p>";
					
					echo "<p align=\"right\"><a href=\"empresa.php\">Regresar</a></p>";
					
				}
				else{
					
					$numero_original = $_POST['clave'];
					//si es una operacion de editar empresa
					$query = "UPDATE empresa SET nombre='$nombre',calle='$calle', numero='$num', colonia='$colonia', ciudad='$ciudad', estado='$estado', pais='$pais', codigo_postal='$cp', telefono='$tel', correo='$mail', rfc='$rfc', contacto='$contacto', regimen='$regimen' WHERE id_empresa ='$numero_original';";
							
					$consulta = $db->consulta($query);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>La empresa ".$nombre." con la clave ".$numero_original." ha sido actualizada exitosamente. Gracias! </strong></p>";
					
					//obtendre los valores de los tipos de cambio
					$monedas = $db->consulta("SELECT * FROM `moneda`;");
					while ($row = $db->fetch_array($monedas))
					{
						$id_moneda = $row['id_moneda'];
						$Vident = "VEN".$id_moneda;
						$Cident = "COM".$id_moneda;
						$venta = $_POST[$Vident];
						$compra = $_POST[$Cident];
						$query = "UPDATE moneda SET precio_compra='$compra',precio_venta='$venta' WHERE id_moneda ='$id_moneda';";
						$consulta = $db->consulta($query);
						
					}
					
					//obtendre los valores de los diferentes tipos de IVA
					$ivas = $db->consulta("SELECT * FROM `iva`;");
					while ($row = $db->fetch_array($ivas))
					{
						$id_iva = $row['id_iva'];
						$ctasa = "tas".$id_iva;
						$tasa = $_POST[$ctasa];
						$query = "UPDATE iva SET tasa='$tasa' WHERE id_iva ='$id_iva';";
						$consulta = $db->consulta($query);
						
					}
					
					// verifica haya sido cargado el archivo de imagen
					  if(is_uploaded_file($_FILES['logo']['tmp_name'])) { 
					  
						  // Se guardaría dentro de "carpeta" con el nombre original
						  // $ruta= "carpeta/nuevo_nombre.jpg"; si también se quiere renombrar
						  $ruta= "../images/logo.jpg"; 
						  
						  // se coloca en su lugar final
						  if(move_uploaded_file($_FILES['logo']['tmp_name'], $ruta)) { 
						  echo "Logotipo actualizado correctamente";
						  }
					  } 
													
					
					echo "<p align=\"right\"><a href=\"empresa.php\">Regresar</a></p>";
				
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