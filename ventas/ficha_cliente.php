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
    
            <h2>Ficha de Datos de Cliente</h2>

			<?php
                $clave = $_GET['numero'];
                $cliente = $db->consulta("SELECT * FROM `cliente` WHERE id_cliente='".$clave."'");
                $existe = $db->num_rows($cliente);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del cliente con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($cliente))
                {
					$nombre = utf8_decode($row['nombre']);
					$cve = $row['clave'];
					$calle = utf8_decode($row['calle']);
					$num = $row['numero'];
					$numInt = $row['no_interior'];
					$colonia = utf8_decode($row['colonia']);
					//$colonia = $row['colonia'];
					$ciudad = utf8_decode($row['ciudad']);
					$estado = utf8_decode($row['estado']);
					$tel = $row['telefono'];
					$cp = $row['codigo_postal'];
					$mail = $row['correo'];
					$rfc = $row['rfc'];
					$contacto = utf8_decode($row['contacto']);
					
					$id_vendedor = $row['id_vendedor'];
				    $consulta_vende = $db->consulta("SELECT nombre  FROM `empleado` WHERE `id_empleado` = '".$id_vendedor."';");
					while ($row2 = $db->fetch_array($consulta_vende))
					{
						$vendedor = $row2['nombre'];
					}
					
					$id_tipo = $row['id_tipo_cliente'];
					$consulta_tipo = $db->consulta("SELECT *  FROM `tipo_cliente` WHERE `id_tipo_cliente` = '".$id_tipo."';");
					while ($row2 = $db->fetch_array($consulta_tipo))
					{
						$tipo = $row2['tipo_cliente'];
					}
					
				    $id_status = $row['id_estado_cliente'];
					$consulta_sta = $db->consulta("SELECT *  FROM `status_cliente` WHERE `id_status_cliente` = '".$id_status."';");
					while ($row2 = $db->fetch_array($consulta_sta))
					{
						$status = $row2['status_cliente'];
					}
					
				    $id_facturacion = $row['id_tipo_facturacion'];
					$consulta_fac = $db->consulta("SELECT *  FROM `tipo_facturacion` WHERE `id_tipo_facturacion` = '".$id_facturacion."';");
					while ($row2 = $db->fetch_array($consulta_fac))
					{
						$facturacion = $row2['tipo_facturacion'];
					}
					
					
				    $limite = $row['limite_credito'];
					$limite = number_format($limite,2);
					
					$cve_pais = $row['pais'];
					$consulta_pais = $db->consulta("SELECT *  FROM `pais` WHERE `Code` = '".$cve_pais."';");
					while ($row2 = $db->fetch_array($consulta_pais))
					{
						$pais = $row2['Name'];
					}
					
					$direccion = $calle." ".$num." ".$numInt.", ".$colonia.", ".$ciudad." C.P. ".$cp;
					
                
            ?>
            
            <table align="center" border="0" width="100%">
                <tr><td align="center">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos de Registro </B></legend>
                    <table border="0" width="600px" align="center">	
                        <tr>
                            <td align="left"><b>No. de Cliente:</b>
							<?php echo $clave; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Clave:</b>
							<?php echo $cve; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Nombre:</b>
							<?php echo $nombre; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Contacto:</b>
							<?php echo $contacto; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Direcci&oacute;n:</b>
                            <?php echo $direccion; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Estado:</b>
                            <?php echo $estado; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Pais:</b>
                            <?php echo $pais; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Tel&eacute;fono:</b>
                            <?php echo $tel; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>E-mail:</b>
                            <?php echo $mail; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>R.F.C.:</b>
                            <?php echo $rfc; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Vendedor asignado:</b>
                            <?php echo $vendedor; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Tipo de cliente:</b>
                            <?php echo $tipo; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Limite de cr&eacute;dito:</b>
                            <?php echo $limite; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Estado del cliente:</b>
                            <?php echo $status; ?></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="3"><b>Tipo de facturaci&oacute;n:</b>
                            <?php echo $facturacion; ?></td>
                        </tr>
                        
                    </table>
                  </fieldset>
                  </div>
                </td></tr>

                 <tr>
                     <td align="right">
                            <?php 
                                echo "<a href=\"ficha_cliente_pdf.php?numero=$clave\" target=\"blank\"><i>Imprimir Ficha&nbsp;&nbsp;</i></a><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" />";
                            ?>                 
                     </td>
                 </tr>
                
                
            </table>  
            </form>
            <?php
            }
            
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