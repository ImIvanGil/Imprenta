<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();

$clave = $_GET['numero'];

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

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
			$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: { 3:{sorter: false},4:{sorter: false},5:{sorter: false}}});
			 $("#tablesorter-test").tablesorterPager({container: $("#pager")});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea eliminar el insumo n\u00famero " + numero +"?\nTodos los registros ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
	}
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
            <li><a href="../inicio.php">Inicio</a></li>
            <li><a href="compras.php">Compras</a></li>
            <li><a href="insumos.php" class="current">Insumos</a></li>
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
    
    	<div class="sb_box">
                
            <img src="../images/iconos/logo_excel.png" alt="image 1" />
            <a href="<?php echo "ficha_inventario_excel.php?numero=$clave";?>">Exportar a Excel</a>
            
        </div>

         
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
    
    
        
         <h2>Ficha de Iventario</h2>

			<?php
                
				
				//DATOS DEL INSUMO
				
                $insumo = $db->consulta("SELECT * FROM `insumo` WHERE id_insumo='".$clave."'");
                $existe = $db->num_rows($insumo);
                if($existe<=0){
                    echo "No hay informaci&oacute;n del insumo con la clave <b>$clave</b>, verifique sus datos";
                
                }else{
                
                while ($row = $db->fetch_array($insumo))
                {
					$nombre = utf8_encode($row['nombre']);
					$descripcion = utf8_encode($row['descripcion']);
					
					$id_moneda = $row['id_moneda'];
					//consulto los datos de la moneda
					$mon = $db->consulta("SELECT *  FROM `moneda` WHERE `id_moneda` = '".$id_moneda."';");
					while ($row2 = $db->fetch_array($mon))
					{
						$moneda = $row2['moneda'];
						$precio_venta = $row2['precio_venta'];
					}
					
			?>
            
            <table align="center" border="0" width="100%">
            	<tr>
                    <td align="right">
					                
                     </td>
                </tr>
                <tr><td align="center">
                  <!-- Recuadro con los datos generales -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Datos Generales </B></legend>
                    <table border="0" width="700px" align="center">	
                        <tr>
                            <td align="left"><b>Nombre:</b>
							<?php echo $nombre; ?></td>
                            <td align="right"><a href="nuevo_mov_inventario.php?<?php echo "numero=".$clave;?>">Nuevo movimiento <img src="../images/iconos/onebit_31.png" width="24px" /></a></td>
                        </tr>
                        <tr>
                            <td align="left" colspan="2"><b>Descripci&oacute;n:</b>
							<?php echo $descripcion; ?></td>
                        </tr>
                        <tr>
                            <td align="left"><b>Moneda de Valuaci&oacute;n:</b>
							<?php echo $moneda; ?></td>
                            <td align="left"><b>Tipo de Cambio Vigente:</b>
							<?php echo $precio_venta; ?></td>
                        </tr>
                        
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                <tr><td align="center">
                  <!-- Recuadro con los datos de presentaciones del insumo -->
                  <div id="data_form">
                  <fieldset>								  
                    <legend> <B>Ficha de Inventario</B></legend>
                    <table border="0" width="100%" align="center">	
                       
                        <tr>
                            <td align="left">
								<?php 
								
                                    $movimientos = $db->consulta("SELECT * FROM `insumo_inventario` WHERE id_insumo='".$clave."' ORDER BY `fecha`,`id_movimiento` ASC");
                                    $existe = $db->num_rows($movimientos);
                                    if($existe<=0){
                                        echo "No se han registrado movimientos de inventario para &eacute;ste insumo";
									
                                    
                                    }else{
									?>
										<table id="tablesorter-pres" class="tablesorter" cellspacing="0" border="1">
                                        <thead>
                                            <tr align="center">
                                                <th class="header" colspan="3">&nbsp;</th>
                                                <th class="header" colspan="3">U N I D A D E S</th>
                                                <th class="header" colspan="2">C O S T O</th>
                                                <th class="header" colspan="3">V A L O R E S</th>
                                                <th class="header" colspan="2">&nbsp;</th>
                                            </tr>
                                            <tr align="center">
                                                <th class="header">No.</th>
                                                <th class="header">Fecha</th>
                                                <th class="header">Descripcion</th>
                                                <th class="header">Entrada</th>
                                                <th class="header">Salida</th>
                                                <th class="header">Existencia</th>
                                                <th class="header">Unitario</th>
                                                <th class="header">Promedio</th>
                                                <th class="header">Debe</th>
                                                <th class="header">Haber</th>
                                                <th class="header">Saldo</th>
                                                <th class="header">Editar</th>
                                                <th class="header">Borrar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
									<?php
										
                                    	$i = 1;
										$existencia = 0;
										$saldo = 0;
										while ($row3 = $db->fetch_array($movimientos))
										{
											$key = $row3['id_movimiento'];
											$fecha = $row3['fecha'];
											$descripcion = $row3['descripcion'];
											$tipo_mov = $row3['id_tipo_movimiento'];
											$unidades = $row3['unidades'];
											$unitario = $row3['unitario'];
											$total_mov = $unidades * $unitario;
											
											//ahora imprimire la tabla
											
												//si es entrada
												 echo "<tr><td class=\"listado\" align=\"center\">".$i."</td>
												 <td align=\"center\" align=\"center\">".$fecha."</td>
												 <td align=\"left\">".$descripcion."</td>";
												 //reviso si es entrada o salida
												 if($tipo_mov==1 || $tipo_mov==3){
													  echo "<td align=\"center\">".$unidades."</td><td>&nbsp;</td>";
													  $existencia = $existencia + $unidades;
													  $saldo = $saldo + $total_mov;
												 }else{
													  echo "<td>&nbsp;</td><td align=\"center\">".$unidades."</td>";
													  $existencia = $existencia - $unidades;
													  $saldo = $saldo - $total_mov;
												 }
												 
												 echo "<td align=\"center\">".$existencia."</td>";
												 echo "<td class=\"listado\" align=\"right\">$ ".number_format($unitario,2)."</td>";
												 //calculo el promedio
												$promedio = $saldo/$existencia;
												echo "<td class=\"listado\" align=\"right\">$ ".number_format($promedio,2)."</td>";
												
												//vuelvo a hacer la revision para imprimir los valores
												 if($tipo_mov==1 || $tipo_mov==3){
													  echo "<td align=\"center\">".number_format($total_mov,2)."</td><td>&nbsp;</td>";
												 }else{
													  echo "<td>&nbsp;</td><td align=\"center\">".number_format($total_mov,2)."</td>";
												 }
												 
												 echo "<td class=\"listado\" align=\"right\">$ ".number_format($saldo,2)."</td>
												 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"editar_mov_inventario.php?numero=$key&insumo=$clave\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
												 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_mov_inventario.php?numero=$key&insumo=$clave\",\"$i\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>";
											
											$i++;
										}
										echo"</tbody></table>";
									}
                                ?>
                            		
                            
                            </td>
                        </tr>
                    </table>
                  </fieldset>
                  </div>
                </td></tr>
                
                
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