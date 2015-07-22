<?php
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect();
if ($prot->showPage) {
$curUser=$prot->getUser();
$user_id=$prot->getId();

	
include("../lib/mysql.php");
	$db = new MySQL();	
	
	$clave = $_GET['clave'];
	$estado = $_GET['estado'];
	if($estado==2){
		$fecha_aut = date('Y-m-d');
		$cons = "UPDATE cargo SET id_status_cobranza='$estado',id_autoriza='".$user_id."',fecha_autoriza='".$fecha_aut."' WHERE id_cargo='$clave';";
	}else{
		$cons = "UPDATE cargo SET id_status_cobranza='$estado',id_autoriza='".$user_id."' WHERE id_cargo='$clave';";
	}												
	
	
	$consultaOrd = $db->consulta($cons);
	
	$link = "Location: ficha_cargo.php?numero=$clave";
	header($link);
	
}
ob_end_flush();
?> 