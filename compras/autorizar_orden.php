<?php include("../adminuser/adminpro_class.php");
ob_start();
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
$user_id=$prot->getId();
include("../lib/mysql.php");
$db = new MySQL();

																	
$numero = $_GET['numero'];
$db = new MySQL(); 
	
//actualizamos el status de la orden

$query = "UPDATE orden_compra SET id_status='2',id_autoriza='".$user_id."' WHERE id_orden ='$numero';";
//echo "la consulta sera :".$query;
$consulta = $db->consulta($query);
$link = "Location: ficha_orden.php?numero=$numero";
header($link);
				
	

}
ob_end_flush();
?>