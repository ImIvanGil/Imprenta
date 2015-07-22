<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
	$curUser=$prot->getUser();
	include("../lib/mysql.php");
	$db = new MySQL();
	
	$db = new MySQL();
	$id_archivo = $_GET['id_archivo'];
	$ruta = $_GET['ruta'];
	$numero = $_GET['id_orden'];
	
	unlink($ruta);
	
	$db->consulta("DELETE FROM archivos_diseno WHERE id_archivo='".$id_archivo."';");
	
	$link = "Location: ficha_orden_diseno.php?numero=$numero";
	header($link);
	
}
ob_end_flush();
?>
