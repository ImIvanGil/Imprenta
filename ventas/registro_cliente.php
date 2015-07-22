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
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="clientes.php" class="current">Clientes</a></li>
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
                
            <img src="../images/iconos/onebit_18.png" alt="image 3" />
            <a href="nuevo_cliente.php">Nuevo Cliente</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_cliente.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Cliente</h2>
            
            
            <?php 
																	
				$operacion = $_GET['operacion'];
				$nombre = utf8_encode($_GET['nombre']);
				$cve = $_GET['cve'];
				//$nombre = utf8_decode($nombre);
				$contacto = $_GET['contacto'];
				//$contacto = utf8_decode($contacto);
				$calle = utf8_encode($_GET['calle']);
				$num = $_GET['num'];
				$numInt = $_GET['numInt'];
				$colonia = utf8_encode($_GET['colonia']);
				$cp = $_GET['cp'];
				$pais = $_GET['pais'];
				$estado = $_GET['estado'];
				$ciudad = utf8_encode($_GET['ciudad']);
				$tel = $_GET['tel'];
				$mail = $_GET['mail'];
				$rfc = $_GET['rfc'];
				$rfc = strtoupper($rfc);
				$id_vendedor = $_GET['vendedor'];
				$id_tipo = $_GET['tipo'];
				$id_status = $_GET['status'];
				$id_facturacion = $_GET['facturacion'];
				$limite = $_GET['limite'];
				
				$db = new MySQL();
				
														
				if ($operacion =="registrar"){
					
					$sql_verifica = "SELECT * FROM cliente WHERE clave ='$cve'";
					$consulta_verifica = $db->consulta($sql_verifica);
					if($db->num_rows($consulta_verifica)>0){
						echo"No es posible registrar el cliente con la clave <b>".$cve."</b> por que actualmente hay un cliente con esa clave, favor de verificar sus datos<br><br>";
						echo"<p align=\"right\"><a href=\"clientes.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
					}else{
						//si es un cliente nuevo
						$consulta = $db->consulta("insert into cliente(clave,nombre, calle, numero, no_interior, colonia, ciudad, codigo_postal, estado, pais, telefono, correo, rfc, contacto ,id_vendedor, id_tipo_cliente,id_estado_cliente,limite_credito,id_tipo_facturacion) values('".$cve."','".$nombre."','".$calle."','".$num."','".$numInt."','".$colonia."','".$ciudad."','".$cp."','".$estado."','".$pais."','".$tel."','".$mail."','".$rfc."','".$contacto."','".$id_vendedor."','".$id_tipo."','".$id_status."','".$limite."','".$id_facturacion."');");  
						
						$nombre = utf8_decode($nombre);
						echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El cliente ".$nombre." ha sido registrado exitosamente. Gracias! </strong></p>";
						echo "<p align=\"right\"><a href=\"clientes.php\">Regresar</a></p>";
					}
					
				}
				else{
					
					$numero_original = $_GET['clave'];
					
					//si es una operacion de editar cliente
					$query = "UPDATE cliente SET clave='$cve',nombre='$nombre',calle='$calle', numero='$num', no_interior='$numInt', colonia='$colonia', ciudad='$ciudad', estado='$estado', pais='$pais', codigo_postal='$cp', telefono='$tel', correo='$mail', rfc='$rfc', contacto='$contacto', id_vendedor='$id_vendedor', id_tipo_cliente='$id_tipo', id_estado_cliente='$id_status', limite_credito='$limite', id_tipo_facturacion='$id_facturacion' WHERE id_cliente ='$numero_original';";
							
					$consulta = $db->consulta($query);
					
					$nombre = utf8_decode($nombre);
					
					echo "<p align=\"center\" style=\"font-size: 14px;\"><strong>El cliente ".$nombre." con la clave ".$numero_original." ha sido actualizado exitosamente. Gracias! </strong></p>";
					echo "<p align=\"right\"><a href=\"clientes.php\">Regresar</a></p>";
				
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