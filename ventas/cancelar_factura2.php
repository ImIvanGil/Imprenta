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
				$sql_cte = "SELECT * FROM factura WHERE id_factura='".$numero."'";
				$consulta_cte = $db->consulta($sql_cte);
				$row_cte = mysql_fetch_array($consulta_cte);
				$id_cliente=$row_cte['id_cliente'];
				$id_status=$row_cte['id_status_factura'];
				$uuid=$row_cte['uuid'];
				$uuid_list[0]=$uuid;
				
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
					
						
						// Variables que indican donde estan las claves
						$password = 'Cpimpresos2011';
						$cer_path = 'firmas/igc061204e47.cer';
						$key_path = 'firmas/igc061204e47_1109271035s.key';
						
						$pem_path = 'firmas/pem_igc061204e47.pem';
						$pem_key_path = 'firmas/key_igc061204e47.pem';
						$pfx_path = 'firmas/pfx_igc061204e47.pfx';
						$pem64_path = 'firmas/pem64_igc061204e47.pem';
						
						
						/*
						//CONVERTIR CERTIFICADO A .PEM
						//$cmd = 'openssl x509 -inform DER -in '.$cer_path.' -out '.$pem_path;
						$cmd = '"C:\wamp\openssl\bin\openssl" x509 -inform DER -in '.$cer_path.' -out '.$pem_path;
						echo "el cmd es: ".$cmd."<br>";
						$result = shell_exec( $cmd );
						echo "el resultado del CER a PEM es : ".$result."<br>";
						unset( $cmd );
						unset( $result );
						
						//CONVERTIR LLAVE A .PEM
						//$cmd = 'openssl pkcs8 -inform DER -in '.$key_path.' -passin pass:'.$password.' -out '.$pem_key_path;
						$cmd = '"C:\wamp\openssl\bin\openssl" pkcs8 -inform DER -in '.$key_path.' -passin pass:'.$password.' -out '.$pem_key_path;
						echo "el cmd es: ".$cmd."<br>";
						$result = shell_exec( $cmd );
						echo "el resultado de lallave a PEM es : ".$result."<br>";
						unset( $cmd );
						unset( $result );
						
						//GENERAR PFX
						//$cmd = 'openssl pkcs12 -export -out '.$pfx_path.' -inkey '.$pem_key_path.' -in '.$pem_path.' -passout pass:'.$password;
						$cmd = '"C:\wamp\openssl\bin\openssl" pkcs12 -export -out '.$pfx_path.' -inkey '.$pem_key_path.' -in '.$pem_path.' -passout pass:'.$password;
						echo "el cmd es: ".$cmd."<br>";
						
						$result = shell_exec( $cmd );
						echo "el resultado del PFX es : ".$result."<br>";
						unset( $cmd );
						unset( $result );
						
						//CONVERTIR PFX A PEM 64
						//$cmd = 'openssl base64 -in '.$pfx_path.' -out '.$pem64_path;
						$cmd = '"C:\wamp\openssl\bin\openssl" base64 -in '.$pfx_path.' -out '.$pem64_path;
						echo "el cmd es: ".$cmd."<br>";
						$result = shell_exec( $cmd );
						echo "el resultado es : ".$result."<br>";
						unset( $cmd );
						unset( $result );
						*/
						
						//SACO EL CONTENIDO DEL PEM64 EN UNA CADENA
						$cadena = file_get_contents($pem64_path);
						echo "la cadena a enviar es: <br>";
						echo $cadena."<br>";
						echo "el UUID a enviar es: <br>";
						echo $uuid_list[0]."<br>";
						
					
						//le cambio el tiempo del timeout aver si asi se recibe respuesta 
						set_time_limit(60); 
						
						//***********LLAMADO AL SERVICIO DE FOLIOS
						require_once('../lib/nusoap.php'); 
				
						$datos=array
						(
						'usuario' =>'IGC061204E47',
						'password' =>'R@6Tdn11KX=',
						'RFCEmisor' =>$rfc_emp,
						'listaCFDI' =>$uuid_list,
						'certificadoPKCS12_Base64' =>$cadena,
						'passwordPKCS12' =>$password
						);
						
						$oSoapClient = new nusoap_client('https://www.foliosdigitalespac.com/ws-folios/WS-TFD.asmx?WSDL', $datos);
						
						$function = 'CancelarCFDI';
						
						$respuesta = $oSoapClient->call($function, $datos); 
						
						if (!$error = $oSoapClient->getError())
						{
							echo "entre al if ";
							$cancelado=$respuesta["CancelarCFDIResult"]["string"][0];
							//voy a separar la respuesta
							$datos = explode("|", $cancelado);
							$uuid_cancelado = $datos[0];
							$codigo = $datos[1];
							$mensaje = $datos[2];
							if($codigo=='201'){
								//si es 201 quiere decir que se cancelo
								$acuse=$respuesta["CancelarCFDIResult"]["string"][1];
								$file_acus = "cfdi/cancelados/".$rfc_emp."_".$clave.".xml";
								file_put_contents($file_acus, "$acuse");
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
							echo "entre al else <br>";
							echo "ERROR:".print_r ($error)."<br>";
							
							echo "Error en la conexion con PAC, consulte a su administrador de sistemas<br>";
							echo "<p align=\"right\"><a href=\"facturas.php\">Regresar</a></p>";
							
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