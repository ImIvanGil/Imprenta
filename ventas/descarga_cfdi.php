<?php include("../adminuser/adminpro_class.php");
ob_start();
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();


//obtengo el nombre del archivo a descargar
$file = $_GET['file'];
$name = $file.".xml";	
$ruta = "cfdi/xml_cer/".$file.".xml";

	function force_download($ruta,$name) {  
		if (isset($ruta)) {  
		   header("Content-length: ".filesize($ruta));  
		   header('Content-Type: application/octet-stream');
		   header("Content-type: application/xml");
		   header('Content-Disposition: attachment; filename="' . $name . '"');  
		   readfile("$ruta");  
		} else {  
		   echo "No se ha seleccionado ningún fichero";  
		}  
	} 
	
	force_download($ruta,$name);

}
ob_end_flush();
?>