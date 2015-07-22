<?php
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect();
if ($prot->showPage) {
$curUser=$prot->getUser();
$user_id=$prot->getId();

	
include("../lib/mysql.php");
	$db = new MySQL();	
	
	$clave = $_GET['numero'];
	$estado = $_GET['estado'];
	if($estado==2){
		$fecha_aut = date('Y-m-d');
		$cons = "UPDATE orden_produccion SET id_estado='$estado',id_autoriza='".$user_id."',fecha_autoriza='".$fecha_aut."' WHERE id_orden='$clave';";
	}else{
		$cons = "UPDATE orden_produccion SET id_estado='$estado',id_autoriza='".$user_id."' WHERE id_orden='$clave';";
	}												
	
	
	$consultaOrd = $db->consulta($cons);
	
	$link = "Location: ficha_orden_prod.php?numero=$clave";
	header($link);
	
}
ob_end_flush();
?> 
