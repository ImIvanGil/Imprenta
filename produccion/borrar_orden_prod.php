<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
	$curUser=$prot->getUser();
	include("../lib/mysql.php");
	$db = new MySQL();
	
	$db = new MySQL();
	$clave = $_GET['numero'];
	
	
	$db->consulta("DELETE FROM detalle_orden_produccion WHERE id_orden='".$clave."';");
	$db->consulta("DELETE FROM orden_produccion WHERE id_orden='".$clave."';");
	
	$link = "Location: ordenes_produccion.php";
	header($link);
	
}
ob_end_flush();
?>
