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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
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
    
            <h2>Cancelar Nota de Credito</h2>
            
            
           	<?php
				$db = new MySQL();
				$numero = $_GET['numero'];
				
				
				//consulta el id_cliente y el id_status  de la factura
				$sql_fac = "SELECT * FROM nota_CREDITO WHERE id_nota='".$numero."'";
				$consulta_fac = $db->consulta($sql_fac);
				$row_fac = mysql_fetch_array($consulta_fac);
				$id_cliente=$row_fac['id_cliente'];
				$id_status=$row_fac['id_status_factura'];
				$uuid=$row_fac['uuid'];
				
				//consulto el nombre del cliente
				$sql_nombre = "SELECT nombre FROM cliente where `id_cliente`='".$id_cliente."'";
				$consulta_nombre = $db->consulta($sql_nombre);
				$row_nombre = mysql_fetch_array($consulta_nombre);
				$nombre_cliente=$row_nombre['nombre'];
				$nombre_cliente = utf8_decode($nombre_cliente);
				
				//consulto el rfc de la empresa
				$sql_emp = "SELECT * FROM empresa WHERE id_empresa='1'";
				$consulta_emp = $db->consulta($sql_emp);
				$row_emp = mysql_fetch_array($consulta_emp);
				$rfc_emp=$row_emp['rfc'];
				
				//no la podre borrar si esta activa o cancelada
				if($id_status==1||$id_status==3){
					echo"No es posible cancelar la nota de credito a nombre del cliente <b>".$nombre_cliente."</b> por que su estado es activo o ya est&aacute; cancelada, s&oacute;lo es posible cancelar notas certificadas, una vez que han sido certificadas o canceladas ante el SAT, deben permanecer almacenadas por lo menos 3 a&ntilde;os<br><br>";
					echo"<p align=\"right\"><a href=\"notas_credito.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
				}
				else{
						
						//echo "entre a la *************************CANCELACION**************** <br>";
						include("cancelar_pax.php");
						$listaCFDI=array( 'string' => $uuid);
						$XMLS =  cancelaUUID( $listaCFDI, $rfc_emp);
						
						
						  //$XMLS = utf8_decode($XMLS);
						 // $file_canc = "notas/cancelados/".$rfc_emp."_".$numero.".xml";
						 // file_put_contents($file_canc, "$XMLS");
								
						  require_once('../lib/simpleXML/IsterXmlSimpleXMLImpl.php');
						  
						  $Lectura = utf8_encode($XMLS);
						  $Lectura = trim($Lectura);
						  //echo "LA LECTURA ES: ".$Lectura. "<br>";
						  
						  
						  $dom = new DOMDocument;
						  $dom->preserveWhiteSpace = FALSE;
						  $dom->loadXML($Lectura);
						  $dom->formatOutput = TRUE;
						  $cosa =  $dom->saveXml();
						  $file_canc = "notas/cancelados/".$rfc_emp."_".$numero.".xml";
						  file_put_contents($file_canc, "$cosa");
						  
						  	$objDOM = new DOMDocument();
  							$objDOM->load($file_canc); // Cargar el fichero XML
							$entrada = $objDOM->getElementsByTagName("Folios");
							foreach ($entrada as $value) {
								//echo "**********VOY A RECUPERAR LOS DATOS RECIBIDOS <BR>";
								$uuid_cancelado = $value->getElementsByTagName("UUID")->item(0)->nodeValue;
								$codigo = $value->getElementsByTagName("UUIDEstatus")->item(0)->nodeValue;
								$mensaje = $value->getElementsByTagName("UUIDdescripcion")->item(0)->nodeValue;
								$mensaje =utf8_decode($mensaje);
							  }
						  
							
						  // ahora accedo a sus atributos para  poder verificar si se cancelo
						  
							/*echo "<br>*** UUID:<br>";
							echo $uuid_cancelado;
							echo "<br>";
							echo "<br>*** ESTADO:<br>";
							echo $codigo;
							echo "<br>";
							echo "<br>*** DESCRIPCION:<br>";
							echo utf8_decode($mensaje);
							echo "<br>";*/
							
							if($codigo==201){
								//si es 201 quiere decir que se cancelo
								//echo "*******SE CANCELO <BR>";
								$del1 = "UPDATE nota_credito SET id_status_factura='3' WHERE id_nota='".$numero."';";
								$db->consulta($del1);
								
								//hago el update del numero de folios usados para cancelar
								$cons_cert = $db->consulta("SELECT canceladas FROM empresa WHERE id_empresa=1;");
								$row_cert = $db->fetch_array($cons_cert);
								$canceladas = $row_cert['canceladas'];
								$canceladas = $canceladas+1;
								$query = $db->consulta("UPDATE empresa SET canceladas=$canceladas WHERE id_empresa=1");
								
								
								//echo "SE ACTUALIZO LA BD ********* <BR>";
								echo "La nota de credito a nombre del cliente <b> ".$nombre_cliente."</b> con la clave <b>".$numero."</b> y el Folio Fiscal <b>".$uuid_cancelado."</b> ha sido cancelada, consulte la ficha de nota pada descargar el acuse ante el SAT<br>";
								echo "<p align=\"right\"><a href=\"notas_credito.php\">Regresar</a></p>";
							}else{
								//si no es 201 muestro el error
								//echo "NO SE CANCELO <BR>";
								echo "Error en la cancelacion de nota de credito con folio fiscal <b>$uuid_cancelado</b><br>";
								echo "<b>Codigo error:</b> $codigo<br>";
								echo "<b>Mensaje error: </b>$mensaje<br>";
								echo "Consulte a su administrador de sistemas<br>";
								echo "<p align=\"right\"><a href=\"notas_credito.php\">Regresar</a></p>";
								
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