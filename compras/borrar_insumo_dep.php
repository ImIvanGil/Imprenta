<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
	$curUser=$prot->getUser();
	include("../lib/mysql.php");
	$db = new MySQL();
	
	$insumo = $_GET['numero'];
	$dependencia = $_GET['pres'];
	
	$db->consulta("DELETE FROM insumo_dependencias WHERE id_primario='".$insumo."' AND id_accesorio='".$dependencia."';");
	$link = "Location: ficha_insumo.php?numero=$insumo";
	header($link);
					
		
}
ob_end_flush();
?>