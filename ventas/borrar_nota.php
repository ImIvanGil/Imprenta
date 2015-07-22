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
            <li><a href="notas_credito.php" class="current">Notas de Cr&eacute;dito</a></li>
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
                
            <img src="../images/iconos/onebit_31.png" alt="image 3" />
            <a href="nueva_nota.php">Nueva Nota</a>
            
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
    
            <h2>Eliminar Nota de Credito</h2>
            
            
           	<?php
				$db = new MySQL();
				$numero = $_GET['numero'];
				
				
				//consulta el id_cliente y el id_status  de la factura
				$sql_cte = "SELECT id_cliente,id_status_factura FROM nota_credito WHERE id_nota='".$numero."'";
				$consulta_cte = $db->consulta($sql_cte);
				$row_cte = mysql_fetch_array($consulta_cte);
				$id_cliente=$row_cte['id_cliente'];
				$id_status=$row_cte['id_status_factura'];
				
				//consulto el nombre del cliente
				$sql_nombre = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_cliente=$row_nombre['nombre'];
				$nombre_cliente = utf8_decode($nombre_cliente);
				
				
				//no la podre borrar si esta certificada o cancelada
				if($id_status==2||$id_status==3){
					echo"No es posible eliminar la nota de credito a nombre del cliente <b>".$nombre_cliente."</b> por que su estado es certificado o cancelado, s&oacute;lo es posible eliminar notas de credito activas, una vez que han sido certificadas o canceladas ante el SAT, deben permanecer almacenadas por lo menos 3 a&ntilde;os<br><br>";
					echo"<p align=\"right\"><a href=\"notas_credito.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
				}
				else{
					$del1 = "DELETE FROM nota_credito WHERE id_nota='".$numero."';";
					$db->consulta($del1);
					$del2 = "DELETE FROM detalle_nota_credito WHERE id_nota='".$numero."';";
					$db->consulta($del2);
					echo "La nota de credito a nombre del cliente <b> ".$nombre_cliente."</b> con la clave <b>".$numero."</b> ha sido eliminada<br>";
					echo "<p align=\"right\"><a href=\"notas_credito.php\">Regresar</a></p>";
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