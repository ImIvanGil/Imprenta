<?php 

/* MiniClase paso Objs a PAX.Net   */
class DatosPAX {
   public $sListaUUID;
   public $psRFC;
   public $pnId_Estructura;
   public $sNombre;
   public $sContraseña;
}


function cancelaUUID( $listaCFDI, $rfc ) {
	//echo "entre a cancelar_pax <br>";
      //
      $wsurl = 'https://www.paxfacturacion.com.mx:453/webservices/wcfCancelaasmx.asmx?wsdl';
      $wsusr = trim('igraficos'); 
      $wspwd = trim('w4TDjMOTw7rEgsS2w6jElMSrxLRrTMKBG8KAczBuwqVU77+G77+iFe+/j+++pu+9nu+/qO+/qu++sg==');
      
     /* $wsurl = 'https://test.paxfacturacion.com.mx/webservices/wcfCancelaasmx.asmx?wsdl';
      $wsusr = trim('WSDL_PAX'); 
      $wspwd = trim('wqfCr8O3xLfEhMOHw4nEjMSrxJnvv7bvvr4cVcKuKkBEM++/ke+/gCPvv4nvvrfvvaDvvb/vvqTvvoA=');*/
      
      if( $wsusr == "" or $wspwd == "" ) {
         halt('No se ha definido usuario y contraseña para servicio de timbrado (CFDI).');
      }
      //
      $ini = ini_set("soap.wsdl_cache_enabled","0");
      //
      $parameters = new DatosPAX;
      $parameters->sListaUUID = $listaCFDI;
      $parameters->psRFC = $rfc;
      $parameters->pnId_Estructura = 0;
      $parameters->sNombre = $wsusr;
      $parameters->sContraseña = $wspwd;  // ojo, es eñe en utf8: Ã±
      //
      try {
         $options = array('classmap' => array('fnCancelarXML'=>'DatosPAX'),
                          'cache_wsdl' => WSDL_CACHE_NONE,
                          'trace' => 1,
                          'exceptions' => 1 );
         $client = new SoapClient( $wsurl , $options );
		 //echo "*********Se enviara el xml: <br>";
		 //echo htmlspecialchars($cfdxml)."<br>";
         $objectresult = $client->fnCancelarXML( $parameters );
         $simpleresult = $objectresult->fnCancelarXMLResult;
		 //$simpleresult = $client->__getLastResponse();
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
        echo "$simpleresult<br>Por favor revise el documento o intente más tarde si es problema de comunicación.";
      }else{
			//echo "respuesta exitosa<br>";
			//echo htmlspecialchars($simpleresult)."<br>";
		}
      //
      return $simpleresult;
   }



?>