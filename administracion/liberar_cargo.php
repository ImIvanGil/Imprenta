<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
</head>
<body>


            
            
<?php
	$db = new MySQL();
	$numero = $_GET['numero'];
	echo "el numero es $numero <br>";
	
		//selecciono las cargos que se han asignado para cambiarles el status y que vuelvan a estar disponibles para pagarse
		
		$facts = "SELECT * FROM detalle_pago_proveedor WHERE id_detalle='".$numero."';";
		$asignaciones = $db->consulta($facts);
		
		while ($rowf = $db->fetch_array($asignaciones))
			{
				$pago = $rowf['id_pago_proveedor'];
				$cargo = $rowf['id_cargo'];
				$monto = $rowf['monto'];
				$parcial = $rowf['parcial'];
				
				$id_detalle = $rowf['id_detalle'];
				//si parcial es 1 quiere decir que se saldo por completo y al liberarla vamos a ponerla por cobrar
				if($parcial==1){
					$f1 = "UPDATE cargo SET id_status_cobranza='2' WHERE id_cargo='".$cargo."';";
					$db->consulta($f1);
					$del2 = "DELETE FROM detalle_pago_proveedor WHERE id_detalle='".$id_detalle."';";
					$db->consulta($del2);
					
				}else{
						//si parcial es cero vamos a ver cuanto se adeuda y ver si es lo mismo que el monto porlo que seria por completo por cobrar
						//consultare los abonos que se han hecho a la cargo aparte de este
						$cons_abonos = $db->consulta("SELECT sum(`monto`) as suma FROM `detalle_pago_proveedor` WHERE `id_cargo` = '".$cargo."' and `id_detalle`!=".$id_detalle.";");
						while ($row2 = $db->fetch_array($cons_abonos)){
						   $abonos = $row2['suma'];
						   //si hay otros abonos entonces la dejo en pago parcial, si este es el unico entonces la dejo en por pagar
							if($abonos!=0){
								$f1 = "UPDATE cargo SET id_status_cobranza='3' WHERE id_cargo='".$cargo."';";
								$db->consulta($f1);
								$del2 = "DELETE FROM detalle_pago_proveedor WHERE id_detalle='".$id_detalle."';";
								$db->consulta($del2);
							}else{
								$f1 = "UPDATE cargo SET id_status_cobranza='2' WHERE id_cargo='".$cargo."';";
								$db->consulta($f1);
								$del2 = "DELETE FROM detalle_pago_proveedor WHERE id_detalle='".$id_detalle."';";
								$db->consulta($del2);
							}
						   
						}
						
					
				}
				
		}
		//verificare si hay mas detalle de pagos si no hay, entonces pongo el pago por aplicar, si no lo dejo como aplicado
		$cons_pagos = $db->consulta("SELECT sum(`monto`) as sum FROM `detalle_pago_proveedor` WHERE `id_pago_proveedor` = '".$pago."';");
		while ($row5 = $db->fetch_array($cons_pagos)){
			$resto = $row5['sum'];
			if($resto>0){
				$del1 = "UPDATE pago_proveedor SET aplicado='2' WHERE id_pago='".$pago."';";
				$db->consulta($del1);
			}else{
				$del1 = "UPDATE pago_proveedor SET aplicado='1' WHERE id_pago='".$pago."';";
				$db->consulta($del1);
			}
			
		}
		$link = "Location: asignar_pago.php?numero=$pago";
		header($link);
	
		

?>
           
            

</body>
</html>
<?php
}
ob_end_flush();
?>