<?php
ob_start();
include("adminuser/adminpro_class.php");
$prot=new protect();
if ($prot->showPage) {
$curUser=$prot->getUser();

	
	include("lib/mysql.php"); 
	$db = new MySQL();														
	
	$sqlUser = "SELECT userGroup FROM `myuser` WHERE `userName`='$curUser'";
	$consultaUser = $db->consulta($sqlUser);
	$rowUser = mysql_fetch_array($consultaUser);
	$dato=$rowUser['userGroup'];
	
	switch($dato){
		case 1:
		$link = "Location: inicio.php";
		header($link);
		break;
		
		case 2:
		$link = "Location: inicio_2_ventas.php";
		header($link);
		break;
	
	}
	

}

ob_end_flush();
?> 
