<?php
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect();
if ($prot->showPage) {
$curUser=$prot->getUser();

	
	include("../lib/mysql.php"); 
	$db = new MySQL();	
	$tipo = $_GET['tipo_pago'];
	$cliente = $_GET['cliente'];
	
	
	if ($tipo == '6'){
		//es nota de credito
		$link = "Location: nuevo_pago_nota.php?tipo=$tipo&cliente=$cliente";
		header($link);
	}else{
		//es otro tipo
		$link = "Location: nuevo_pago_otro.php?tipo=$tipo&cliente=$cliente";
		header($link);
		
	}
	

}

ob_end_flush();
?> 
