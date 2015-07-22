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
            <li><a href="admin.php">Administraci&oacute;n</a></li>
            <li><a href="pagos.php" class="current">Pagos</a></li>
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
                
            <img src="../images/iconos/onebit_55.png" alt="image 3" />
            <a href="nuevo_pago.php">Registro de pago</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="pagos.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Registro de Asignaci&oacute;n de Pagos</h2>
            
            
            <?php 
																	
				$id_pago = $_POST['clave'];
				$id_proveedor = $_POST['proveedor'];
				$asignar = $_POST['asignar'];

				//consulto el nombre del proveedor
				$sql_nombre = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_proveedor=utf8_decode($row_nombre['nombre']);
				
				$disponible = $asignar;
				$cuantas = 0;
				$i = 0;
				$lista_cargos = $db->consulta("SELECT * FROM `cargo` WHERE `id_proveedor`=$id_proveedor and `id_status_cobranza` BETWEEN 2 AND 3 order by `fecha` ASC;");
				while ($rowf = $db->fetch_array($lista_cargos)){
					$d1= "saldar".$i;
					$d2= "parcial".$i;
					$d3= "tot".$i;
					$d4= "saldo".$i;
					$d5= "id_fac".$i;
					
					if (isset($_POST[$d1])) {
						//si se selecciono saldar vamos a liquidar todo
						$saldar=$_POST[$d1];
						$total = $_POST[$d3];
						$resto = $_POST[$d4];
						$id_fac = $_POST[$d5];
						if($total==$resto){
							$parc = 1;
						}else{
							$parc = 0;
						}
						//verificamos que el saldo no sea mayor que lo que podemos asignar
						if($resto<=$disponible){
							$query_asigna = "INSERT INTO `detalle_pago_proveedor` (`id_pago_proveedor`,`id_cargo`, `monto`, `parcial`) VALUES ('".$id_pago."','".$id_fac."','".$resto."','".$parc."');";
							$consulta = $db->consulta($query_asigna);
							$query_act_fac = "UPDATE cargo SET id_status_cobranza='4' WHERE id_cargo ='$id_fac';";
							$consulta = $db->consulta($query_act_fac);
							$disponible = $disponible-$resto;
							$cuantas = $cuantas+1;
							echo "El cargo n&uacute;mero $id_fac ha sido saldado<br>";
							 
						}else{
							echo "No se puede saldar el cargo n&uacute;mero $id_fac por que su saldo de $ " .number_format($resto,8)." excede el monto disponible del pago <br>";
						}
						
						
					}else{
						//si esta seleccionado el abono parcial
						
						if(isset($_POST[$d2])){
							$parcial=$_POST[$d2];
							$total = $_POST[$d3];
							$resto = $_POST[$d4];
							$id_fac = $_POST[$d5];
							$nuevo_saldo= $resto - $parcial;
							$nuevo_saldo = number_format($nuevo_saldo,2);
							$parc = 0;
							
							if($nuevo_saldo==0){
								$stat_cob = 4;
							}else{
								$stat_cob = 3;
							}
							
							//verificamos que el abono no sea mayor que lo que podemos asignar
							if($parcial<=$disponible){
								if($parcial!=0){
									$query_asigna = "INSERT INTO `detalle_pago_proveedor` (`id_pago_proveedor`,`id_cargo`, `monto`, `parcial`) VALUES ('".$id_pago."','".$id_fac."','".$parcial."','".$parc."');";
									$consulta = $db->consulta($query_asigna);
									$query_act_fac = "UPDATE cargo SET id_status_cobranza='".$stat_cob."' WHERE id_cargo ='$id_fac';";
									$consulta = $db->consulta($query_act_fac);
									$disponible = $disponible-$parcial;
									$cuantas = $cuantas+1;
									echo "El cargo n&uacute;mero $id_fac ha sido abonada por $".number_format($parcial,2)." el nuevo saldo es de $ $nuevo_saldo<br>";
								}
							}else{
								echo "No se puede saldar el cargo n&uacute;mero $id_fac por que su abono parcial de $ " .number_format($parcial)." excede el monto disponible del pago <br>";
							}
							
						
						}	
					}
					
				$i++;	
				}
				
				if($cuantas>0){
					//si se asignaron cargos actualizo el pago para decir que ya esta asignado
					$query_act_pago = "UPDATE pago_proveedor SET aplicado='2' WHERE id_pago ='$id_pago';";
					$consulta = $db->consulta($query_act_pago);
				}
				
				echo "<br>";
				echo "<p align=\"left\">El pago a nombre del proveedor ".$nombre_proveedor." con la clave ".$id_pago." ha sido aplicado exitosamente. Gracias!</p>";
				echo "<p align=\"left\">Se acreditaron ".$cuantas." cargos</p>";
				if($disponible<=0){
					echo "<p align=\"left\">Se ha asignado por completo el pago</p>";
				}else{
					echo "<p align=\"left\">Restan $ ".number_format($disponible,2)." para asignar a mas cargos</p>";
				}
				echo "<p align=\"left\"><a href=\"asignar_pago.php?numero=$id_pago\">Regresar</a></p>";
				
				//$link = "Location: asignar_pago.php?numero=$id_pago";
				//header($link);
				
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
ob_end_flush();
?>