<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();

$movimiento = $_GET['numero'];
$producto = $_GET['producto'];

$db->consulta("DELETE FROM producto_almacen WHERE id_movimiento='".$movimiento."';");
$link = "Location: ficha_almacen.php?numero=$producto";
header($link);
				
	
}
ob_end_flush();
?>