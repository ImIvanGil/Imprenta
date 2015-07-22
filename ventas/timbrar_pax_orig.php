<?php 
class DatosPAX {
   public $psComprobante;
   public $psTipoDocumento;
   public $pnId_Estructura;
   public $sNombre;
   public $sContraseña;
   public $sVersion;
}
function getTimbre( $cfdxml) {
      $ini = ini_set("soap.wsdl_cache_enabled","0");
      //
      $wsurl =
"https://www.paxfacturacion.com.mx:453/webservices/wcfRecepcionasmx.asmx?wsdl";
      $wsusr = "igraficos";
      $wspwd =
"w4TDjMOTw7rEgsS2w6jElMSrxLRrTMKBG8KAczBuwqVU77+G77+iFe+/j+++pu+9nu+/qO+/qu++sg==";
      //
	  //$cfdxml = str_replace("> <", "><", $cfdxml);
	  //$cfdxml1 = preg_replace('/\s\s+/', ' ', $cfdxml);
	  //$str = preg_replace('/\n\n+/', "", $cfdxml);
	  
	  $sustituye = array("\r\n", "\n\r", "\n", "\r", "\u003E\u00A0\u003C");
      $cfdxml1 = str_replace($sustituye, "", $cfdxml); 
	  //$cfdxml = str_replace(array("\n", "\r"), '', $cfdxml); 
	  $cfdxml = '<?xml version="1.0" encoding="UTF-8"?><cfdi:Comprobante xmlns:cfdi="http://www.sat.gob.mx/cfd/3" xsi:schemaLocation="http://www.sat.gob.mx/cfd/3 http://www.sat.gob.mx/sitio_internet/cfd/3/cfdv32.xsd" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="3.2" folio="1626" fecha="2012-05-09T08:44:15" sello="CuOO6nhVbQkYuZI4CwwQUpBx5gRKdm71oXKWX6K7YggT2RB40QKcMrrjc43XmjEqaMiI7ei+lsS/yCj8xu4JbqoHo4QmEoUeOa3K27uFvIhBNPzalaNip4HYBlynw3iwDN5xO43Jx7AvhqA1iDUaxy6E3pTRNacC8mQjl+9LYyM=" tipoDeComprobante="ingreso" formaDePago="Pago en una sola exhibicion" metodoDePago="Efectivo" LugarExpedicion="Chihuahua, Mexico" noCertificado="00001000000104424488" certificado="MIIEUTCCAzmgAwIBAgIUMDAwMDEwMDAwMDAxMDQ0MjQ0ODgwDQYJKoZIhvcNAQEFBQAwggE2MTgwNgYDVQQDDC9BLkMuIGRlbCBTZXJ2aWNpbyBkZSBBZG1pbmlzdHJhY2nDs24gVHJpYnV0YXJpYTEvMC0GA1UECgwmU2VydmljaW8gZGUgQWRtaW5pc3RyYWNpw7NuIFRyaWJ1dGFyaWExHzAdBgkqhkiG9w0BCQEWEGFjb2RzQHNhdC5nb2IubXgxJjAkBgNVBAkMHUF2LiBIaWRhbGdvIDc3LCBDb2wuIEd1ZXJyZXJvMQ4wDAYDVQQRDAUwNjMwMDELMAkGA1UEBhMCTVgxGTAXBgNVBAgMEERpc3RyaXRvIEZlZGVyYWwxEzARBgNVBAcMCkN1YXVodGVtb2MxMzAxBgkqhkiG9w0BCQIMJFJlc3BvbnNhYmxlOiBGZXJuYW5kbyBNYXJ0w61uZXogQ29zczAeFw0xMTA5MjcxNjM4MDVaFw0xMzA5MjYxNjM4MDVaMIHxMTAwLgYDVQQDEydJTVBSRVNPUyBHUkFGSUNPUyBERSBDSElIVUFIVUEgU0EgREUgQ1YxMDAuBgNVBCkTJ0lNUFJFU09TIEdSQUZJQ09TIERFIENISUhVQUhVQSBTQSBERSBDVjEwMC4GA1UEChMnSU1QUkVTT1MgR1JBRklDT1MgREUgQ0hJSFVBSFVBIFNBIERFIENWMSUwIwYDVQQtExxJR0MwNjEyMDRFNDcgLyBQT01TNjIwOTA4OUhBMR4wHAYDVQQFExUgLyBQT01TNjIwOTA4SENIUk5SMDExEjAQBgNVBAsTCUNoaWh1YWh1YTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEA23j5p62XnNR/nHZy/ulkLsyQOhZLo6FMad0z0yqHRyvSKeV0909YoaTmNs8/YUPzDjisSqeMPM1lXJw7nvzioh4qRuQxINghBHsgeUydmvnXJqnmqqH1akdEWEI14EN3kgnjfFzznHzuaxOrHJcbp5jru3UXSzZe2r9k5R1gdtUCAwEAAaMdMBswDAYDVR0TAQH/BAIwADALBgNVHQ8EBAMCBsAwDQYJKoZIhvcNAQEFBQADggEBALe7Wd7Wj8Vy00uM8NRuD8Zad+DYBUj3uTlddch0oCOrBIqCYbjKAoWLOxoAwFtPxzq7wJCwAv235rG1luH4JmI+rfM3rDKSdtU8w7utH9xV1XihwxZFz+9gYmV057riwzTNyMAt5CVVTuAoZ/ZZJ4xs7WDDm9XpD+TQUqGrmJUVxPZTDDNdA8Vndm87bOcWh65+kJ89ZU0s7lq65dZwpCCIMGWHikiQ2oDh35M6VEr8y20suSYzWOQL9ymXnfeUvg0aIhnCq1n/UGOrnZgIQ8vCRzIL+x5i+X5ZtwP+0BOVE8Q/WP+pTZD8gt/9G68HvFo2LDIix6iudfFEgoYWEs4=" subTotal="2400.00" TipoCambio="1" Moneda="MXN" total="2784.00"><cfdi:Emisor rfc="IGC061204E47" nombre="Impresos Graficos de Chihuahua S.A. de C.V."><cfdi:DomicilioFiscal calle="Juan Bernardo" noExterior="4109" colonia="Fraccionamiento La Cañada" municipio="Chihuahua" estado="Chihuahua" pais="Mexico" codigoPostal="31410"/><cfdi:ExpedidoEn calle="Juan Bernardo" noExterior="4109" colonia="Fraccionamiento La Cañada" municipio="Chihuahua" estado="Chihuahua" pais="Mexico" codigoPostal="31410"/><cfdi:RegimenFiscal Regimen="Régimen General de Ley Personas Morales"/></cfdi:Emisor><cfdi:Receptor rfc="UGL870209DL0" nombre="UNION DE GANADEROS LECHEROS DE JUAREZ, S.A. DE C.V."><cfdi:Domicilio calle="RAMON RAYON" noExterior="1351" colonia="WATERFILL RIO BRAVO" municipio="Juárez" estado="Chihuahua" pais="Mexico" codigoPostal="32553"/></cfdi:Receptor><cfdi:Conceptos><cfdi:Concepto cantidad="100" unidad="Pieza" descripcion="ETIQUETA CARNE SECA 35 GRS" valorUnitario="4.0000" importe="400.00"/><cfdi:Concepto cantidad="400" unidad="Pieza" descripcion="BOLETOS PARA LA MINA OCAMPO" valorUnitario="5.0000" importe="2000.00"/></cfdi:Conceptos><cfdi:Impuestos><cfdi:Traslados><cfdi:Traslado impuesto="IVA" tasa="16" importe="384.00"/></cfdi:Traslados></cfdi:Impuestos></cfdi:Comprobante>';
      
	  echo "estoy enviando el xml: ".htmlspecialchars($cfdxml)."<br>";
	  $parameters = new DatosPAX;
      $parameters->psComprobante = "$cfdxml1";
      $parameters->psTipoDocumento = "factura";
      $parameters->pnId_Estructura = 0;
      $parameters->sNombre = $wsusr;
      $parameters->sContraseÃ±a = $wspwd;  // ojo, es eñe en utf8: Ã±
      $parameters->sVersion = "3.2";
      //
      try {
         $client = new SoapClient( $wsurl, array('classmap' =>
array('fnEnviarXML'=>'DatosPAX'), 'trace' => TRUE, 'exceptions' => 1) );
			 try{
				 $objectresult = $client->__soapCall('fnEnviarXML', array(new SoapVar($parameters, XSD_ANYTYPE, 'parameters'))
  ); 
			}catch(SoapFault $fault){
			  // <xmp> tag displays xml output in html
			  echo 'Request 1 : <br/><xmp>';
			  $req = htmlspecialchars($client->__getLastRequest());
			  echo $req;
			  echo '</xmp><br/>';
			  echo 'Response 1 :<br/><xmp>';
			  htmlspecialchars($client->__getLastResponse());
			  echo '</xmp><br/> ERROR AL ENVIAR DATOS: <br/>',
			  htmlspecialchars($fault->getMessage());
			} 
         	 $simpleresult = $objectresult->fnEnviarXMLResult;
         	 print_r( $simpleresult );
        	 // return $simpleresult;
      } catch( Exception $e ){
		  echo "<pre>";    // para debug
		  echo "Request :\n".htmlspecialchars($client->__getLastRequest() ) ."\n";
		  echo "Response:\n".htmlspecialchars($client->__getLastResponse() )."\n";
		  echo "</pre>";
		  echo "Error!<br />";
          echo $e->getMessage();
      }
      
      
      exit();
}
?>