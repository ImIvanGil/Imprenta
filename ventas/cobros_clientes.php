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
			$("#tablesorter-test").tablesorter({sortList:[[0,1]], widthFixed: true, widgets: ['zebra'], headers: { 7:{sorter: false},5:{sorter: false},6:{sorter: false}}});
			 $("#tablesorter-test").tablesorterPager({container: $("#pager")});
		} 
	);
</script>
<!--Script que confirma el borrado de un registro -->
<script language="JavaScript">
	function confirma (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea cancelar el pago n\u00famero " + numero +"?\n El pago ya no podra ser asignado y solo sera visible en reportes")) location.replace(url);
	}
	
	function confirma2 (url,numero) {
	if (confirm("CUIDADO!!!\nEst\u00e1 seguro que desea liberar el pago n\u00famero " + numero +"?\nTodos los registros de asigacion a facturas ser\u00e1n eliminados y la operaci\u00f3n no podr\u00e1 ser revertida")) location.replace(url);
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
            <li><a href="ventas.php">Ventas</a></li>
            <li><a href="cobros_clientes.php" class="current">Cobranza</a></li>
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
            <a href="nuevo_pago_cliente.php">Registro de pago</a>
            
        </div>

         <div class="sb_box">
            <a href="#">Buscar</a>
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <form method="get" action="cobros_clientes.php" name="busqueda" id="busqueda" onSubmit="return validarBusqueda()"> 
            <input type="text" class="texto" width="30" name="parametro" id="parametro" />
            <input class="submit_btn reset" type="submit" value="Ir"/>
            </form>
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
    
            <h2>Lista de Cobros</h2>
            
            
            <table id="tablesorter-test" class="tablesorter" cellspacing="0">
            <thead>
                <tr align="center">
                    <th class="header">No.</th>
                    <th class="header">Cliente</th>
                    <th class="header">Fecha</th>
                    <th class="header">Tipo</th>
                    <th class="header">Monto</th>
                    <th class="header">Disponible</th>
                    <th>Asignaci&oacute;n</th>
                    <th>Editar</th>
                    <th>Liberar</th>
                    <th>Cancelar</th>
                </tr>
            </thead>
            <tbody>
            
				<?php 
                    $db = new MySQL();
    				$i = 1;
					//selecciono los pagos que no estan cancelados 
					if (isset($_GET['parametro'])) {
						  $buscar = $_GET['parametro'];
						  $cons ="SELECT * FROM `pago_cliente` WHERE `id_cliente` in (SELECT `id_cliente` FROM `cliente` WHERE `nombre` like '%$buscar%') OR `fecha` like '%$buscar%' OR `referencia` like '%$buscar%' AND aplicado!='3' ORDER BY id_pago DESC;";
						  $lista_cobros = $db->consulta($cons);
						  
						  $mensaje = "Resultados de la b&uacute;squeda que contienen la palabra clave <b>$buscar</b>";
					  }else{
						  $lista_cobros = $db->consulta("SELECT * FROM `pago_cliente` where aplicado!='3' ORDER BY id_pago DESC LIMIT 20;");
						  $mensaje = "Se muestran los ultimos 20 pagos registrados, para localizar una registro en especifico, use el campo de busqueda.";
					  }
					  
					echo "<span><i>$mensaje<i></span>";
					
					
                    //$lista_cobros = $db->consulta("SELECT * FROM `pago_cliente` where aplicado!='3' ORDER BY id_pago DESC;");
                    while ($rowf = $db->fetch_array($lista_cobros))
                        {
							$clave = $rowf['id_pago'];
							
							$fecha = $rowf['fecha'];
							$monto = $rowf['monto'];
							$fmonto = number_format($monto,2);
							//reviso cuanto se ha asignado ya del pago
							$cons_asignacion = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_cliente` WHERE `id_pago_cliente` = '".$clave."';");
							while ($row2 = $db->fetch_array($cons_asignacion)){
								$asignado = $row2['suma'];
							}
							
							$asignar = $monto - $asignado;
							$fasignar = number_format($asignar,2);
							
							$id_cli= $rowf['id_cliente'];
							//buscar el nombre del cliente
							$sql_cli = "SELECT * FROM cliente where `id_cliente`='".$id_cli."'";
							$consulta_cli = $db->consulta($sql_cli);
							while($row_cli = mysql_fetch_array($consulta_cli)){
								$nom_cliente=$row_cli['nombre'];
							}
							
							$id_tipo = $rowf['id_tipo_pago'];
							//buscar el tipo
							$consulta_tipo = $db->consulta("SELECT *  FROM `tipo_pago_cliente` WHERE `id_tipo_pago` = '".$id_tipo."';");
							while ($row3 = $db->fetch_array($consulta_tipo)){
								$tipo = $row3['tipo_pago'];
							}
							
							
							 echo "<tr align=\"center\">
							 <td class=\"listado\">".$clave."</td>
							 <td align=\"left\">".utf8_decode($nom_cliente)."</td>
							 <td class=\"listado\">".$fecha."</td>
							 <td class=\"listado\">".$tipo."</td>
							 <td class=\"listado\">$".$fmonto."</td>
							 <td class=\"listado\">$".$fasignar."</td>
							 <td class=\"listado\"><a class=\"icono\" href=\"asignar_pago.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_39.png\" width=\"24px\" align=\"center\"></a></td>";
							if($id_tipo==6){
								//los pagos con nota de credito no se podran editar directamente
								echo "<td class=\"listado\">Operaci&oacute;n no Permitida</td>";
							}else{
								echo "<td class=\"listado\"><a class=\"icono\" href=\"editar_pago_cliente.php?numero=".$clave."\"><img src=\"../images/iconos/onebit_20.png\" width=\"24px\" align=\"center\"></a></td>";
								
							}
							 
							 
						 echo "<td class=\"listado\"><a class=\"icono\" href=JavaScript:confirma2(\"http://localhost/imprenta/ventas/liberar_pago_cliente.php?numero=$clave\",\"$clave\");><img src=\"../images/iconos/onebit_24.png\" width=\"24px\" align=\"center\"></a></td>
						 <td class=\"listado\"><a class=\"icono\" href=JavaScript:confirma(\"http://localhost/imprenta/ventas/cancelar_pago_cliente.php?numero=$clave\",\"$clave\");><img src=\"../images/iconos/onebit_33.png\" width=\"24px\" align=\"center\"></a></td>
						 </tr>";
							 
							 
							 
							$i++;
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
                     <li><span>&raquo;</span><a href="lista_pagos_clientes.php">Exportar a Excel</a></li>
                     <!--li><span>&raquo;</span><a href="lista_comisiones_ventas.php">Lista de Comisiones</a></li-->
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