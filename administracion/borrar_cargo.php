<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();

$numero = $_GET['numero'];


//consulta el id_proveedor y el id_status  de la cargo
$sql_cte = "SELECT id_proveedor,id_status_cargo FROM cargo WHERE id_cargo='".$numero."'";
$consulta_cte = $db->consulta($sql_cte);
$row_cte = mysql_fetch_array($consulta_cte);
$id_proveedor=$row_cte['id_proveedor'];
$id_status=$row_cte['id_status_cargo'];

//consulto el nombre del proveedor
$sql_nombre = "SELECT nombre FROM proveedor where `id_proveedor`='".$id_proveedor."'";
$consulta_nombre = $db->consulta($sql_nombre);
$row_nombre = mysql_fetch_array($consulta_nombre);
$nombre_proveedor=$row_nombre['nombre'];
$nombre_proveedor = utf8_decode($nombre_proveedor);

$del1 = "DELETE FROM cargo WHERE id_cargo='".$numero."';";
$db->consulta($del1);
	
$link = "Location: cargos.php";
header($link);	
}
ob_end_flush();
?>