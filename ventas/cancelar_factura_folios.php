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
            <li><a href="facturas.php" class="current">Facturaci&oacute;n</a></li>
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
                
            <img src="../images/iconos/onebit_78.png" alt="image 3" />
            <a href="nueva_factura.php">Nueva Factura</a>
            
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
    
            <h2>Cancelar Factura</h2>
            
            
           	<?php
				$db = new MySQL();
				$numero = $_GET['numero'];
				
				
				//consulta el id_cliente y el id_status  de la factura
				$sql_fac = "SELECT * FROM factura WHERE id_factura='".$numero."'";
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
					echo"No es posible cancelar la factura a nombre del cliente <b>".$nombre_cliente."</b> por que su estado es activo o ya est&aacute; cancelada, s&oacute;lo es posible cancelar facturas certificadas, una vez que han sido certificadas o canceladas ante el SAT, deben permanecer almacenadas por lo menos 3 a&ntilde;os<br><br>";
					echo"<p align=\"right\"><a href=\"facturas.php\"><input class=\"boton\" type=\"button\" value=\"Regresar\"/></a></p>";
				}
				else{
						//hare el llamado a al metodo de cancelar
						$listaCFDI=array( 'string' => $uuid);
						$certificadoPKCS12_Base64 = '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
						COAe/CilVjDSAgIIAA==';//file_get_contents("config/CFDI/eleon/Leonme71.pfx.pem"); 
						/*************************CANCELACION****************/
						/*****************Llamamos al Timbrado************************/
						
						require_once('../lib/nusoap.php');
						
						$datos=array
						(
						'usuario' =>'IGC061204E47',
						'password' =>'R@6Tdn11KX=',
						'RFCEmisor' => 'IGC061204E47',
						'listaCFDI' => $listaCFDI,
						'certificadoPKCS12_Base64' => $certificadoPKCS12_Base64,
						'passwordPKCS12' => 'Cpimpresos2011'
						);
						
						$oSoapClient = new nusoap_client('https://www.foliosdigitalespac.com/ws-folios/WS-TFD.asmx?WSDL',$datos);
						
						$Cdatos=array
						(
						
						'usuario' =>'IGC061204E47',
						'password' =>'R@6Tdn11KX=',
						'RFCEmisor' => 'IGC061204E47',
						'listaCFDI' => $listaCFDI,
						'certificadoPKCS12_Base64' => $certificadoPKCS12_Base64,
						'passwordPKCS12' => 'Cpimpresos2011'
						);
						$funcion = 'CancelarCFDI';
						$respuesta = $oSoapClient->call($funcion, $Cdatos); 
						
						if (!$error = $oSoapClient->getError())
						{
							$estatus=$respuesta['CancelarCFDIResult']['string'][1];
							//$sta=$respuesta['CancelarCFDIResult']['string'][1];
							$AcuseXML= end($respuesta['CancelarCFDIResult']['string']);
							echo "la respuesta es: ".$estatus."<br>";
							//voy a separar la respuesta
							$datos = explode("|", $estatus);
							$uuid_cancelado = $datos[0];
							$codigo = $datos[1];
							$mensaje = $datos[2];
							if($codigo=='201'){
								//si es 201 quiere decir que se cancelo
								$AcuseXML= end($respuesta['CancelarCFDIResult']['string']);
								$file_acus = "cfdi/cancelados/".$rfc_emp."_".$numero.".xml";
								file_put_contents($file_acus, "$AcuseXML");
								$del1 = "UPDATE factura SET id_status_factura='3' WHERE id_factura='".$numero."';";
								$db->consulta($del1);
								echo "La factura a nombre del cliente <b> ".$nombre_cliente."</b> con la clave <b>".$numero."</b> y el Folio Fiscal <b>".$uuid_cancelado."</b> ha sido cancelada, consulte la ficha de factura pada descargar el acuse ante el SAT<br>";
								echo "<p align=\"right\"><a href=\"facturas.php\">Regresar</a></p>";
							}else{
								//si no es 201 muestro el error
								echo "Error en la cancelacion de factura con folio fiscal <b>$uuid_cancelado</b><br>";
								echo "<b>Codigo error:</b> $codigo<br>";
								echo "<b>Mensaje error: </b>$mensaje<br>";
								echo "Consulte a su administrador de sistemas<br>";
								echo "<p align=\"right\"><a href=\"facturas.php\">Regresar</a></p>";
								
							}
						}
						else{
							echo "ERROR:".print_r ($error);
							echo "Error en la conexion con PAC, consulte a su administrador de sistemas<br>";
							echo "<p align=\"right\"><a href=\"facturas.php\">Regresar</a></p>";
							exit;
						}
						//print_r ($respuesta);
						//echo "estatus".$estatus;
						//echo "acuse".$AcuseXML;
						/*****************************************/
						/*****************************************/
						/*
						$AcuseXML=simplexml_load_string($AcuseXML);//utf8_encode(
						$AcXML=dom_import_simplexml($AcuseXML);
						$ac = new DOMDocument('1.0', 'utf-8');;
						$AcXML = $ac -> importNode ( $AcXML , true ); 
						$AcXML = $ac -> appendChild ( $AcXML );
						//echo $dom2->saveXML();
						$ac->save("config/CFDI/".$LIBELE."/PDFyXML/Cancelacion_".$UUID.".xml");
						*/
						
					
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