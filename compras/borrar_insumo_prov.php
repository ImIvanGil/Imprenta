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
				$insumo = $_GET['numero'];
				$proveedor = $_GET['prov'];
				
			
				$db->consulta("DELETE FROM insumo_proveedor WHERE id_insumo='".$insumo."' AND id_proveedor='".$proveedor."';");
				
				$link = "Location: ficha_insumo.php?numero=$insumo";
				header($link);
				
				
			?>
 

</body>
</html>
<?php
}
ob_end_flush();
?>