<?php 


/* MiniClase paso Objs a PAX.Net   */
class DatosPAX {
   public $psComprobante;
   public $psTipoDocumento;
   public $pnId_Estructura;
   public $sNombre;
   public $sContraseña;
   public $sVersion;
}


function getTimbre( $cfdxml, $numFac, $tipo ) {
      //
      $wsurl = 'https://www.paxfacturacion.com.mx:453/webservices/wcfRecepcionasmx.asmx?wsdl';
      $wsusr = trim('igraficos'); 
      $wspwd = trim('w4TDjMOTw7rEgsS2w6jElMSrxLRrTMKBG8KAczBuwqVU77+G77+iFe+/j+++pu+9nu+/qO+/qu++sg==');
      if( $wsusr == "" or $wspwd == "" ) {
         halt('No se ha definido usuario y contraseña para servicio de timbrado (CFDI).');
      }
      //
      $ini = ini_set("soap.wsdl_cache_enabled","0");
      //
      $cfdxml = str_replace( '<?xml version="1.0"?>', '', $cfdxml );
      $cfdxml = str_replace( '<?xml version="1.0" encoding="UTF-8"?>', '', $cfdxml );
      //
      $parameters = new DatosPAX;
      $parameters->psComprobante = $cfdxml;
      $parameters->psTipoDocumento = $tipo;
      $parameters->pnId_Estructura = 0;
      $parameters->sNombre = $wsusr;
      $parameters->sContraseña = $wspwd;  // ojo, es eñe en utf8: Ã±
	  $parameters->sVersion = '3.2';
      //
      try {
         $options = array('classmap' => array('fnEnviarXML'=>'DatosPAX'),
                          'cache_wsdl' => WSDL_CACHE_NONE,
                          'trace' => 1,
                          'exceptions' => 1 );
         $client = new SoapClient( $wsurl , $options );
		 //echo "*********Se enviara el xml: <br>";
		 //echo htmlspecialchars($cfdxml)."<br>";
         $objectresult = $client->fnEnviarXML( $parameters );
         $simpleresult = $objectresult->fnEnviarXMLResult;
      } catch( Exception $e ){
         echo "Error!<br />";
         echo $e->getMessage();
		 echo "<pre>";
      	 echo "Request :\n".htmlspecialchars( $client->__getLastRequest() ) ."\n";
      	 echo "Response:\n".htmlspecialchars( $client->__getLastResponse() )."\n";
      	 echo "</pre>";
         exit();
      }
      //echo "<pre>";
      //echo "Request :\n".htmlspecialchars( $client->__getLastRequest() ) ."\n";
      //echo "Response:\n".htmlspecialchars( $client->__getLastResponse() )."\n";
      //echo "</pre>";
      //
      list($cr) = explode( ' ', $simpleresult );
      $cr = intval( $cr );
      if( $cr > 0 ) {
		  $mensaje = "Error al certificar<br>$simpleresult<br>Por favor revise sus datos o intente más tarde si es problema de comunicación.";
		  $link = "Location: error_cert.php?numero=$numFac&mensaje=$mensaje&tipo=$tipo";
		  header($link);
      }else{
			echo "Timbrado exitoso";
		}
      //
      return $simpleresult;
   }



?>