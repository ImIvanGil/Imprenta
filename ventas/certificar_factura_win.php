<?php include("../adminuser/adminpro_class.php");
ob_start();
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();

function leer_fichero_completo($nombre_fichero){
   //abrimos el archivo de texto y obtenemos el identificador
   $fichero_texto = fopen ($nombre_fichero, "r");
   //obtenemos de una sola vez todo el contenido del fichero
   //OJO! Debido a filesize(), sólo funcionará con archivos de texto
   $contenido_fichero = fread($fichero_texto, filesize($nombre_fichero));
   return $contenido_fichero;
} 


function isUTF8($string)
  {
    for ($idx = 0, $strlen = strlen($string); $idx < $strlen; $idx++)
    {
      $byte = ord($string[$idx]);
 
      if ($byte & 0x80)
      {
        if (($byte & 0xE0) == 0xC0)
        {
          // 2 byte char
          $bytes_remaining = 1;
        }
        else if (($byte & 0xF0) == 0xE0)
        {
          // 3 byte char
          $bytes_remaining = 2;
        }
        else if (($byte & 0xF8) == 0xF0)
        {
          // 4 byte char
          $bytes_remaining = 3;
        }
        else
        {
          return false;
        }
 
        if ($idx + $bytes_remaining >= $strlen)
        {
          return false;
        }
 
        while ($bytes_remaining--)
        {
          if ((ord($string[++$idx]) & 0xC0) != 0x80)
          {
            return false;
          }
        }
      }
    }
 
    return true;
  }

   
//obtengo el id de la factura para consultar sus datos
$clave = $_GET['numero'];	
//variables que acumularan los totales
$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_factura = 0;

$factura = $db->consulta("SELECT * FROM `factura` WHERE id_factura='".$clave."'");
$existe = $db->num_rows($factura);
if($existe<=0){
	echo "No hay informaci&oacute;n de la factura con la clave <b>$clave</b>, verifique sus datos";

}else{

while ($row = $db->fetch_array($factura))
{
		$fecha = $row['fecha'];
		$cuenta = $row['num_cuenta'];
	
		//tipo de comprobante
		$id_tipo_comp = $row['id_tipo_comprobante'];
		$sql_tipo = "SELECT tipo_comprobante FROM tipo_comprobante WHERE id_tipo_comprobante='".$id_tipo_comp."'";
		$consulta_tipo = $db->consulta($sql_tipo);
		$row_tipo = mysql_fetch_array($consulta_tipo);
		$tipo = $row_tipo['tipo_comprobante'];
		
		//forma de pago
		$id_forma = $row['id_forma_pago'];
		$sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
		$consulta_forma = $db->consulta($sql_forma);
		$row_forma = mysql_fetch_array($consulta_forma);
		$forma = $row_forma['forma_pago'];
		
		$id_empresa = $row['id_empresa'];
		//datos de la empresa
		$sql_empresa = "SELECT * FROM empresa where `id_empresa`='".$id_empresa."'";
		$consulta_empresa = $db->consulta($sql_empresa);
		while($row_emp = mysql_fetch_array($consulta_empresa)){
			$nom_emp=$row_emp['nombre'];
			$rfc_emp=$row_emp['rfc'];
			$calle_emp=$row_emp['calle'];
			$numero_emp=$row_emp['numero'];
			$colonia_emp=$row_emp['colonia'];
			$ciudad_emp=$row_emp['ciudad'];
			$estado_emp=$row_emp['estado'];
			$cp_emp=$row_emp['codigo_postal'];
			$regimen=$row_emp['regimen'];
			
			
			$cve_pais_emp=$row_emp['pais'];
			//buscare el nombre del pais
			$sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_emp."'";
			$consulta_pais = $db->consulta($sql_pais);
			while($row_pa = mysql_fetch_array($consulta_pais)){
				$pais_emp=$row_pa['Name'];
			}
			
		}
		
		$id_cliente = $row['id_cliente'];
		//datos del cliente
		$sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
		$consulta_cliente = $db->consulta($sql_cliente);
		while($row_cliente = mysql_fetch_array($consulta_cliente)){
			$nom_cliente=$row_cliente['nombre'];
			$rfc_cliente=$row_cliente['rfc'];
			$calle_cliente=$row_cliente['calle'];
			$numero_cliente=$row_cliente['numero'];
			$numInt_cliente=$row_cliente['no_interior'];
			$colonia_cliente=$row_cliente['colonia'];
			$ciudad_cliente=$row_cliente['ciudad'];
			$estado_cliente=$row_cliente['estado'];
			$cp_cliente=$row_cliente['codigo_postal'];
			$cve_pais_cliente=$row_cliente['pais'];
			
			$cve_pais_emp=$row_emp['pais'];
			//buscare el nombre del pais
			$sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_cliente."'";
			$consulta_pais = $db->consulta($sql_pais);
			while($row_pa = mysql_fetch_array($consulta_pais)){
				$pais_cliente=$row_pa['Name'];
			}
			
		}
		
		//metodo de pago
		$id_met = $row['id_metodo_pago'];
		$sql_met = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo_pago='".$id_met."'";
		$consulta_met = $db->consulta($sql_met);
		$row_met = mysql_fetch_array($consulta_met);
		$metodo = $row_met['metodo_pago'];
		
		//moneda
		$id_mon = $row['id_moneda'];
		$sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
		$consulta_mon = $db->consulta($sql_mon);
		$row_mon = mysql_fetch_array($consulta_mon);
		$moneda = $row_mon['moneda'];
		
		$t_cambio = $row['tipo_cambio'];
		
		//voy a buscar la tasa del iva que vamos a usar
		$id_iva = $row['id_iva'];
		$sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
		$consulta_tas = $db->consulta($sql_tas);
		$row_tas = mysql_fetch_array($consulta_tas);
		$tasa_iva = $row_tas['tasa'];

		//voy a consultar las lineas de detalle de la factura
		$detalle = $db->consulta("SELECT * FROM `detalle_factura` WHERE id_factura='".$clave."'");
		$existe = $db->num_rows($detalle);
		if($existe<=0){
			echo "<p align=\"center\">No se han agregado productos a la factura</p>";
		
		}else{
			//variable contador para llevar el control de las lineas de detalle
			$i = 0;
			while ($row = $db->fetch_array($detalle))
			{
				$id_detalle = $row['id_detalle_fact'];
				$id_producto = $row['id_producto'];
				$cantidad = $row['cantidad'];
				$unitario = $row['unitario'];
				$clase_iva = $row['id_clase_iva'];
				
				//consultare el nombre del tipo de iva
				$consulta_cl = $db->consulta("SELECT *  FROM `clase_iva` WHERE `id_clase` = '".$clase_iva."';");
				while ($row2 = $db->fetch_array($consulta_cl)){
					 $nom_clase = $row2['clase_iva'];
				}
				
				
				$precio = $unitario * $cantidad;
				$sub_total = $sub_total + $precio;
				
				//consultare el nombre del producto
				$consulta_prod = $db->consulta("SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';");
				 while ($row2 = $db->fetch_array($consulta_prod)){
					 $nom_producto = $row2['nombre'];
					 $desc_producto = $row2['descripcion'];
   					 $id_unidad=$row2['id_unidad'];
					 	//aqui dentro consulto el nombre de la unidad
						$cons_uni = $db->consulta("SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';");
						 while ($row15 = $db->fetch_array($cons_uni)){
							 $unidad = $row15['unidad'];
						}
					 }
				//voy a acumular las cantidades para calcular el iva segun como sea la clase
				switch($clase_iva){
					case 1:
						$graba_normal = $graba_normal+$precio;
					break;
					case 2:
						$graba_cero = $graba_cero+$precio;
					break;
					case 3:
						$exento = $exento + $precio;
					break;
				}
				
					$fprecio = number_format($precio, 2, '.', '');
					$funitario = number_format($unitario, 4, '.', '');
					//meto los datos de la linea de detalle en un arreglo que formará el xml para timbrar
					$array['Concepto'][$i]['cantidad'] = $cantidad;
					$array['Concepto'][$i]['unidad'] = $unidad;
					$array['Concepto'][$i]['descripcion'] = $nom_producto;
					$array['Concepto'][$i]['valorUnitario'] = $funitario;
					$array['Concepto'][$i]['importe'] = $fprecio;
					$i++;

				  }
			  }
		 
		  //calculo de los valores totalizados
		  $iva = $graba_normal * ($tasa_iva/100);
		  $total_factura = $sub_total + $iva;
		  $sub_total = number_format($sub_total, 2, '.', '');
		  $iva = number_format($iva, 2, '.', '');
		  $ftotal_factura = number_format($total_factura, 2, '.', '');
		  
		  // ************  AHORA VOY A ASIGNAR LOS VALORES A UN ARREGLO PARA LLAMAR AL LOS METODOS Y GENERAR EL XML Y LA CADENA
			// arreglo del cfd
			$array['version'] = '3.2';
			//$array['serie'] = 'AAAAAAAAAA';
			//$array['noAprobacion'] = "00000000000000";
			//$array['anoAprobacion'] = "2010";
			$array['folio'] = $clave;
			$array['fecha'] = $fecha; // ISO 8601 aaaa-mm-ddThh:mm:ss
			$array['formaDePago'] = $forma; // Pago en una sola exibiciÃ³n | Parcialidad 1 de X.
			$array['tipoDeComprobante'] = $tipo; // ingreso | egreso | traslado
			//$array['condicionesDePago'] = $metodo;
			$array['metodoDePago'] = $metodo; // efectivo, tarjeta de credito, traspaso
			$array['NumCtaPago'] = $cuenta;
			$array['LugarExpedicion'] = $ciudad_emp.", ".$pais_emp;
			$array['tipoCambio'] = $t_cambio;
			$array['moneda'] = $moneda;
			$array['Emisor']['rfc'] = $rfc_emp;
			$array['Emisor']['nombre'] = $nom_emp;
			$array['DomicilioFiscal']['calle'] = $calle_emp;
			$array['DomicilioFiscal']['noExterior'] = $numero_emp;
			//$array['DomicilioFiscal']['noInterior'] = 'A';
			$array['DomicilioFiscal']['colonia'] = $colonia_emp;
			$array['DomicilioFiscal']['municipio'] = $ciudad_emp;
			$array['DomicilioFiscal']['estado'] = $estado_emp;
			$array['DomicilioFiscal']['pais'] = $pais_emp;
			$array['DomicilioFiscal']['codigoPostal'] = $cp_emp;
			
			$array['RegimenFiscal']['Regimen'] = $regimen;
			$array['ExpedidoEn'] = $array['DomicilioFiscal'];
			
			$array['Receptor']['rfc'] = $rfc_cliente;
			$array['Receptor']['nombre'] = $nom_cliente;
			$array['Domicilio']['calle'] = $calle_cliente;
			$array['Domicilio']['noExterior'] = $numero_cliente;
			//$array['Domicilio']['noInterior'] = 'A';
			if($numInt_cliente!=""){
				$array['Domicilio']['noInterior'] = $numInt_cliente;
			}
			$array['Domicilio']['colonia'] = $colonia_cliente;
			$array['Domicilio']['municipio'] = $ciudad_cliente;
			$array['Domicilio']['estado'] = $estado_cliente;
			$array['Domicilio']['pais'] = $pais_cliente;
			$array['Domicilio']['codigoPostal'] = $cp_cliente;
			
			$array['subTotal'] = $sub_total;
			
			//$array['Retencion'][0]['impuesto'] = 'IVA';
			//$array['Retencion'][0]['importe'] = 112;
			//$array['Retencion'][1]['impuesto'] = 'ISR';
			//$array['Retencion'][1]['importe'] = 70;
			
			//si el iva es diferente de extranjero se van a agregar al arreglo los valores
			if($id_iva!=3){
				$array['Traslado'][0]['impuesto'] = 'IVA';
				$array['Traslado'][0]['tasa'] = $tasa_iva;
				$array['Traslado'][0]['importe'] = $iva;
			}
			
			//$array['TTraslados']['totalImpuestosTraslados']  = $iva;
			//$array['descuento'] = '';
			$array['total'] = $ftotal_factura;
			
			
			// llamada a los metodos para generar xml y sello y cadena 
			require_once 'simplecfd_prod.php';
			
			// Variables que indican donde esta el CSD
			$password = 'Cpimpresos2011';
			$cer_path = 'firmas/igc061204e47.cer';
			$key_path = 'firmas/igc061204e47_1109271035s.key';
			
			//llamada a los metodos de obtener llave privada, certificado y cadena original
			$array['sello'] = SimpleCFD::signData( SimpleCFD::getPrivateKey( $key_path, $password ),SimpleCFD::getOriginalString( $array ));
			$array['noCertificado'] = SimpleCFD::getSerialFromCertificate( $cer_path );
			$array['certificado'] = SimpleCFD::getCertificate( $cer_path, false );
			$array['cadenaOriginal'] = SimpleCFD::getOriginalString( $array );
			
			//crear el xml enviando el arreglo de los datos
			$cfdi = SimpleCFD::getXML( $array );
			//ruta donde se almacenaran los datos
			$file = "cfdi/xml/".$rfc_emp."_".$clave.".xml";
			file_put_contents($file, "$cfdi");
			//echo "****CADENA ORIGINAL****<br>";
			//echo $array['cadenaOriginal']."<br>";
			//echo "***SELLO****<br>";
			//secho $array['sello']."<br>";
			//echo "****CERTIFICADO****<br>";
			//echo $array['certificado']."<br>";
			
			 
			//$Lectura = $cfdi;
			$xmlToText = leer_fichero_completo($file);
			//echo "LECTURA:" .htmlspecialchars($xmlToText)."<br>";
			//$xmlToText = trim($Lectura);
			//$xmlToText = utf8_decode($xmlToText);
			$ref = $rfc_emp."_".$clave;
			//echo "*************Cadena del XML***********<br>";
			//echo $xmlToText;
			//echo "<br><br>";
			
			//ahora voy a enviar el cfdi a PAX para obtener el sello 
			
			//++++++++++++++++ PARTE DONDE HACE LA CONEXION PARA EL TIMBRADO
			
			  include("timbrar_pax.php");
			  
			  //$xmlToText = utf8_decode($xmlToText);
			  $tipo_doc = 'factura';
			  //se llama a la conexion con pax 
			  $XMLS =  getTimbre( $xmlToText , $clave,$tipo_doc);
			  
			  //$XMLS = utf8_encode($XMLS);
			  $file_cer = "cfdi/xml_cer/".$rfc_emp."_".$clave.".xml";
			  
			  /*compruebo la codificacion
			  if(!isUTF8($XMLS)){
				   $esUTF = "<br>********La cadena NO es UTF8<br>";
			   }else{
				   $esUTF = "<br>********La cadena SI es UTF8<br>";
			   }
			   echo $esUTF;*/
			   
			  file_put_contents($file_cer, "$XMLS");
			  
			  
					
			  require_once('../lib/simpleXML/IsterXmlSimpleXMLImpl.php');
					
			  
					//Voy a leer el documento certificado
					$impl = new IsterXmlSimpleXMLImpl;
					$doc  = $impl->load_file($file_cer);
					  
					// ahora accedo a sus atributos para poderlos enviar a la base de datos y a generar el QR
					$attr = $doc->Comprobante->Complemento->TimbreFiscalDigital->attributes();
					/*echo "<br>*** UUID:<br>";
					print $attr['UUID'];
					print "\n";
					echo "<br>*** SELLO CFD:<br>";
					print $attr['selloCFD'];
					print "\n";
					echo "<br>*** FECHA TIMBRADO:<br>";
					print $attr['FechaTimbrado'];
					print "\n";
					echo "<br>*** NO CERT SAT:<br>";
					print $attr['noCertificadoSAT'];
					print "\n";
					echo "<br>*** SELLO SAT:<br>";
					print $attr['selloSAT'];
					print "\n";*/
					
					$attr2 = $doc->Comprobante->attributes();
					echo "<br>*** SELLO COMPROBANTE:<br>";
					print $attr2['sello'];
					print "\n";
					
					  
					//obtenemos los datos recibidos en el xml certificado
					$no_serie_cert = $attr['noCertificadoSAT'];
					$fecha_cert = $attr['FechaTimbrado'];
					$sello_sat = $attr['selloSAT'];
					//$cadena_cert = $attr['selloCFD'];
					$uuid= $attr['UUID'];
					$sello = $attr2['sello'];
					$cert = $attr2['certificado'];
					$no_cert = $attr2['noCertificado'];
					$cadena_cert = "||1.0|$uuid|$fecha_cert|$sello|$no_serie_cert||";
					
					//ahora generaremos el codigo QR
					$totalFomat=number_format($total_factura,6, '.', '');
					$cadenaQR = "?re=".$rfc_emp."&rr=".$rfc_cliente."&tt=".$totalFomat."&id=".$uuid;
					//echo $cadenaQR."<br>";
					
					require('../lib/phpqrcode/phpqrcode.php');
					$rutaQR="cfdi/QR/".$rfc_emp."_".$clave.".png";
					QRcode::png($cadenaQR, $rutaQR);
					
					//estado de cobro va a a ser por cobrar para que aparezca en los pagos
					$estado_cobro = 2;
					
					//hago la consulta de update
					$query = "UPDATE factura SET no_serie_cert='$no_serie_cert',fecha_certificacion='$fecha_cert',sello_sat='$sello_sat',cadena_cert='$cadena_cert',uuid='$uuid',id_status_factura='2',sello='$sello',id_status_cobranza='$estado_cobro',no_certificado ='$no_cert' WHERE id_factura ='$clave';";
					echo "La consulta es ". $query."<br>";
					$consulta = $db->consulta($query);
					
					//hago el update del numero de folios usados para certificar
					$cons_cert = $db->consulta("SELECT certificadas FROM empresa WHERE id_empresa=1;");
					$row_cert = $db->fetch_array($cons_cert);
					$certificadas = $row_cert['certificadas'];
					$certificadas = $certificadas+1;
					$query = $db->consulta("UPDATE empresa SET certificadas=$certificadas WHERE id_empresa=1");
					
					//finalmente volveremos a la ficha de la factura
					$link = "Location: ficha_factura.php?numero=$clave";
					header($link);
					
					
				/*}else{
					$mensaje = "Error al certificar<br>";
					$mensaje = "Error al certificar<br> $cod_error <br> $desc_error <br> $comp_error<br>";
					$link = "Location: error_cert.php?numero=$clave&mensaje=$mensaje";
					header($link);
					
				}*/
			
}

}

}
ob_end_flush();
?>