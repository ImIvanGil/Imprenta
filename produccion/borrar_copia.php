<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
	$curUser=$prot->getUser();
	include("../lib/mysql.php");
	$db = new MySQL();
	
	$db = new MySQL();
	$id_registro = $_GET['id_registro'];
	$producto = $_GET['producto'];
	
	$db->consulta("DELETE FROM copias_producto WHERE id_registro='".$id_registro."';");
	
	$link = "Location: ficha_producto.php?numero=$producto";
	header($link);
	
}
ob_end_flush();
?>
