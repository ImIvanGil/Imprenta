<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();

$movimiento = $_GET['numero'];
$insumo = $_GET['insumo'];
$presentacion = $_GET['presentacion'];

$db->consulta("DELETE FROM insumo_inventario WHERE id_movimiento='".$movimiento."';");
$link = "Location: ficha_inventario.php?numero=$insumo";
header($link);
				
	
}
ob_end_flush();
?>