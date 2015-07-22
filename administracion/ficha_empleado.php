<?php
ob_start();
?>
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
            <li><a href="empleados.php" class="current">Empleados</a></li>
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
            <a href="nuevo_cliente.php">Nuevo Empleado</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_empleado.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Ficha de Empleado</h2>
            <div id="data_form">
            <?php
			$clave = $_GET['numero'];
			$empleado = $db->consulta("SELECT * FROM `empleado` WHERE id_empleado='".$clave."'");
			$existe = $db->num_rows($empleado);
			if($existe<=0){
				echo "No hay informaci&oacute;n del empleado con la clave <b>$clave</b>, verifique sus datos";
			
			}else{
			
			while ($row = $db->fetch_array($empleado))
				{
				$nombre = $row['nombre'];
				$direccion = $row['direccion'];
				$tel = $row['telefono'];
				$cel = $row['celular'];
				$cve = $row['clave'];
				$rfc = $row['rfc'];
				$sal = $row['salario_diario'];
				$sal = number_format($sal,2);
				$alta = $row['fecha_contratacion'];
				$baja = $row['fecha_baja'];
				$imss = $row['imss'];
				$id_status = $row['id_status'];
				$id_puesto = $row['id_puesto'];
				$entrada = $row['hora_entrada'];
				$observaciones = $row['observaciones'];
				$comision = $row['comision']*100;
				
				//consulta del status
				$stat = $db->consulta("SELECT * FROM `status_empleado` WHERE id_status='".$id_status."'");
				while ($rows = $db->fetch_array($stat)){
					$status = $rows['status'];
				}
				//consulta del puesto
				$pues = $db->consulta("SELECT * FROM `puesto` WHERE id_puesto='".$id_puesto."'");
				while ($rowp = $db->fetch_array($pues)){
					$puesto = $rowp['puesto'];
				}
				?>
                
                <table align="center" border="0" width="100%">
               
            <tr><td>
              <!-- Recuadro con los datos generales -->
              <fieldset>								  
                <legend> <B>Datos de Registro </B></legend>
                <table border="0" width="90%" align="center">	
                    
                    <tr>
                        <td align="left" colspan="3"><b>No. de Empleado:</b>
                        <?php echo $clave; ?></td>
                    </tr>
                    <tr>
                        <td align="left"><b>Clave:</b>
                        <?php echo $cve; ?></td>
                        <td align="right"><b>Status:</b></td>
                       	<td align="left"><?php echo $status;?></td>
                    </tr>
                    <tr>
                        <td align="left"><b>Nombre:</b>
                        <?php echo $nombre; ?></td>
                        <td align="right"><b>R.F.C.:</b></td>
                       	<td align="left"><?php echo $rfc;?></td>
                    </tr>
                    <tr>
                        <td align="left" colspan="3"><b>Direcci&oacute;n:</b>
                        <?php echo $direccion; ?></td>
                    </tr>
                    <tr>
                        <td align="left"><b>Tel&eacute;fono:</b>
                        <?php echo $tel; ?></td>
                        <td align="right"><b>Celular:</b></td>
                       	<td align="left"><?php echo $cel;?></td>
                    </tr>
                    <tr>
                        <td align="left"><b>Salario Diario:</b>
                        <?php echo "$ ".$sal; ?></td>
                        <td align="right"><b>Comisi&oacute;n:</b></td>
                       	<td align="left"><?php echo number_format($comision,2);?> %</td>
                    </tr>
                    
                    <tr>
                        <td align="left"><b>Fecha de Alta:</b>
                        <?php echo $alta; ?></td>
                        <?php 
							if($id_status==1){
								echo"<td align=\"right\">&nbsp;</td>
                       			<td align=\"left\">&nbsp;</td>";
							}else{
								echo"<td align=\"right\"><b>Fecha de Baja:</b></td>
                       			<td align=\"left\">$baja</td>";
							}
						
						?>
                        
                        
                    </tr>
                     <tr>
                        <td align="left"><b>Clave IMSS:</b><?php echo $imss;?></td>
                        <td align="right"><b>Hora de Entrada:</b></td>
                       	<td align="left"><?php echo $entrada;?></td>
                    </tr>
                    <tr><td align="left" colspan="3"><b>Observaciones: </b><?php echo $observaciones;?></td></tr>
                
                
                </table>
              </fieldset>
            </td></tr>
             <tr align="right">
                  <td align="roght"><?php echo "<a href=\"ficha_empleado_pdf.php?numero=$clave&status=$status&puesto=$puesto\" target=\"blank\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";?></td>
              </tr>
            </table> 

				
		<?php		
			}
				
		}
		?>

            
       
            </div>
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