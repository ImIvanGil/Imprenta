<?php
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect();
if ($prot->showPage) {
$curUser=$prot->getUser();

	
	include("../lib/mysql.php"); 
	$db = new MySQL();	
	$tipo = $_GET['tipo_pago'];
	$proveedor = $_GET['proveedor'];
	
	
	if ($tipo == '6'){
		//es nota de credito
		$link = "Location: nuevo_pago_nota.php?tipo=$tipo&proveedor=$proveedor";
		header($link);
	}else{
		//es otro tipo
		$link = "Location: nuevo_pago_otro.php?tipo=$tipo&proveedor=$proveedor";
		header($link);
		
	}
	

}

ob_end_flush();
?> 
