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
<a href="#">Sistema de ERP<span>

</head>
<body>


            
           	<?php
				$db = new MySQL();
				$registro = $_GET['numero'];
				$orden = $_GET['orden'];
				
			
				$db->consulta("DELETE FROM detalle_orden_produccion WHERE id_detalle='".$registro."';");
				
				$link = "Location: ficha_orden_prod.php?numero=$orden";
				header($link);
				
				
			?>
 

</body>
</html>
<?php
}
ob_end_flush();
?>