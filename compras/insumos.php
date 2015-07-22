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



<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
	$(document).ready(function() 
		{ 
			$("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: { 4:{sorter: false},5:{sorter: false},6:{sorter: false},7:{sorter: false},8:{sorter: false},9:{sorter: false}}});
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


<SCRIPT LANGUAGE="JavaScript">
<!-- Funcion que valida que se hayan escrito los campos obligatorios en el formulario de buscar-->
	function validarBusqueda() {
		if (document.busqueda.parametro.value == "") {
			alert ('Debe escribir un valor en el campo de busqueda');
			document.getElementById('parametro').focus();
			return false;
		}
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
    
    	<div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_14.png" alt="image 3" />
            <a href="nuevo_insumo.php">Nuevo Insumo</a>
            
        </div>
        
        <div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_31.png" alt="image 3" />
            <a href="nuevo_mov_inventario.php">Nuevo Movimiento de Inventario</a>
            
        </div>

        
        <div class="sb_box">
                
            <a href="#">Buscar</a>
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <form method="get" action="insumos.php" name="busqueda" id="busqueda" onSubmit="return validarBusqueda()"> 
            <input type="text" class="texto" width="30" name="parametro" id="parametro" />
            <input class="submit_btn reset" type="submit" value="Ir"/>
            </form>
            
        </div>
        
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
    
            <h2>Lista de Insumos</h2>
            
            
            <table id="tablesorter-test" class="tablesorter" cellspacing="0">
            <thead>
                <tr align="center">
                    <th class="header">No.</th>
                    <th class="header">Nombre</th>
                    <th class="header">Linea</th>
                    <th>Ficha</th>
                    <th>Inventario</th>
                    <th>Existencia</th>
                    <th>&nbsp;</th>
                    <th>Unitario</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody>
            
				<?php 
                    $db = new MySQL();
					
					
					/************************/
					if (isset($_GET['parametro'])) {
						  $buscar = $_GET['parametro'];
						  $cons ="SELECT * FROM `insumo` WHERE `nombre` like '%$buscar%' OR `descripcion` like '%$buscar%' OR `num_parte` like '%$buscar%' ORDER BY id_insumo DESC;";
						  $lista_insumos = $db->consulta($cons);
						  
						  $mensaje = "Resultados de la b&uacute;squeda que contienen la palabra clave <b>$buscar</b>";
					  }else{
						  $lista_insumos = $db->consulta("SELECT * FROM `insumo` ORDER BY id_insumo DESC LIMIT 20;");
						  $mensaje = "Se muestran los ultimos 20 registros, para localizar un dato en especifico, use el campo de busqueda.";
					  }
					  
					echo "<span><i>$mensaje<i></span>";
					
					
					/**********************/
                    while ($row = $db->fetch_array($lista_insumos))
                        {
                        $clave = $row['id_insumo'];
                        $nombre = $row['nombre'];
                        //$contacto = $row['contacto'];
						$descripcion = $row['descripcion'];
						
						
						$max = $row['maximo'];
						$min = $row['minimo'];
						
						$id_moneda = $row['id_moneda'];
						//consulto la moneda
						$mon = $db->consulta("SELECT *  FROM `moneda` WHERE `id_moneda` = '".$id_moneda."';");
						while ($row2 = $db->fetch_array($mon))
						{
							$moneda = $row2['moneda'];
						}
						
						$id_linea = $row['id_linea'];
						//consulto la linea del insumo
						$lin = $db->consulta("SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';");
						while ($row3 = $db->fetch_array($lin))
						{
							$linea = $row3['linea'];
						}
					
						
						
						//consulto la existencia por insumo
							$existencia =0;
							$saldo =0;
							
							$cons8 ="SELECT * FROM `insumo_inventario` where `id_insumo`=$clave;";
							
							$cons4= $db->consulta($cons8);
							
							while ($row2_f = $db->fetch_array($cons4))
							{
								$tipo_mov_f  = $row2_f['id_tipo_movimiento']; 
								$cantidad_f = $row2_f['unidades'];
								$unitario = $row2_f['unitario'];
								$total_mov = $cantidad_f * $unitario;

						
								 if($tipo_mov_f==1 || $tipo_mov_f==3){
									  $existencia = $existencia + $cantidad_f;
									  $saldo = $saldo + $total_mov;
								 }else{
									  $existencia = $existencia-$cantidad_f;
									  $saldo = $saldo - $total_mov;
								 }								
							}
							
                        //calculo el promedio
						if($existencia==0)
						{
							$promedio = 0;
						}else{
							$promedio = $saldo/$existencia;
						}
						
						$promedio = number_format($promedio,2);
                        
						 echo "<tr><td class=\"listado\">".$clave."</td>
                         <td>".$nombre."</td>
						 <td>".$linea."</td>
                         <td class=\"listado\"><a class=\"icono\" href=\"ficha_insumo.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" align=\"center\"></a></td>
						 
						 <td class=\"listado\" align=\"center\"><a class=\"icono\" href=\"ficha_inventario.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_14.png\" width=\"24px\" align=\"center\"></a></td>
						 <td class=\"listado\"  align=\"right\">".$existencia."</td>";
						 
						 //<td class=\"listado\"  align=\"center\">&nbsp;</td>
						 
						 //voy a imprimir un indicador de la existencia respecto al maximo y minimo
						 if($min==0||$max==0){
							 //si estan en blanco los maximos o minimos, entonces estara en blanco
							 echo "<td class=\"listado\"  align=\"center\"><img src=\"../images/iconos/onebit_55.png\" width=\"24px\" align=\"center\"></td>";
						}else{
							 if($existencia-$min<=3&&$existencia-$min>0){
								 //aqui va amarillo se acerca al punto de reorden
								 echo "<td class=\"listado\"  align=\"center\"><img src=\"../images/iconos/onebit_53.png\" width=\"24px\" align=\"center\"></td>";
							}else{
								if($existencia-$min<=0){
									//aqui va rojo estamos por debajo del minimo o ya no hay
									echo "<td class=\"listado\"  align=\"center\"><img src=\"../images/iconos/onebit_56.png\" width=\"24px\" align=\"center\"></td>";
								}else{
									if($max-$existencia<=3&&$max-$existencia>0){
										//aqui va azul por que se acerca al maximo
										echo "<td class=\"listado\"  align=\"center\"><img src=\"../images/iconos/onebit_71.png\" width=\"24px\" align=\"center\"></td>";
									}else{
										if($max-$existencia<=0){
											//aqui va morado, estamos sobrepasando el inventario
											echo "<td class=\"listado\"  align=\"center\"><img src=\"../images/iconos/onebit_81.png\" width=\"24px\" align=\"center\"></td>";
										}else{
											//por ultimo si no entra en ninguno de los anteriores aqui va verde
											echo "<td class=\"listado\"  align=\"center\"><img src=\"../images/iconos/onebit_52.png\" width=\"24px\" align=\"center\"></td>";
										}
									}
								}
							}
						}
						 
						 echo "<td class=\"listado\"  align=\"right\">$ ".$promedio." $moneda</td>
						 
						 <td class=\"listado\"><a class=\"icono\" href=\"editar_insumo.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>
						 
                         <td class=\"listado\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/compras/borrar_insumo.php?numero=$clave\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
                         
                         </tr>";
                        
                        }
                ?>
            </tbody>
            </table>
        <br /> <br />
         <div id="pager" class="tablesorterPager" align="center">
        <form>
            <img src="../images/first.png" class="first"/>
            <img src="../images/prev.png" class="prev"/>
            <input type="text" class="pagedisplay"/>
            <img src="../images/next.png" class="next"/>
            <img src="../images/last.png" class="last"/>
            <select class="pagesize">
                <option selected="selected"  value="10">10</option>
                <option value="20">20</option>
                <option value="30">30</option>
                <option  value="40">40</option>
            </select>
        </form>
       </div>
        
        
            
            
       	  <div class="cleaner_h20"></div>
            
            
		</div>
        
        <div class="col_260 col_last">
        
            <h2>Opciones</h2>
            
        	<div class="sb_news_box">
               <ul class="tmo_list col_260">
                     <li><span>&raquo;</span><a href="lista_insumos.php">Lista de Insumos</a></li>
                      <li><span>&raquo;</span><a href="lista_existencia.php ">Lista de existencias</a></li>
               </ul>
            </div>
            
            <h2>Anotaciones</h2>
            
        	<div class="sb_news_box">
               <ul class="tmo_list col_260">
                     <li><span>&raquo;</span><img src="../images/iconos/onebit_55.png" width="24px" /> Faltan datos</li>
                     <li><span>&raquo;</span><img src="../images/iconos/onebit_52.png" width="24px" /> Inventario Suficiente</li>
                     <li><span>&raquo;</span><img src="../images/iconos/onebit_53.png" width="24px" /> Se acerca al minimo.</li>
                     <li><span>&raquo;</span><img src="../images/iconos/onebit_56.png" width="24px" /> Ordenar Insumo</li>
                     <li><span>&raquo;</span><img src="../images/iconos/onebit_71.png" width="24px" /> Se acerca al maximo</li>
                     <li><span>&raquo;</span><img src="../images/iconos/onebit_81.png" width="24px" /> Sobreinventariado</li>


               </ul>
            </div>
            
            
            
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