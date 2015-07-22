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
				$producto = $_GET['numero'];
				$registro = $_GET['key'];
				
			
				$db->consulta("DELETE FROM producto_insumo WHERE id_prod_insumo='".$registro."';");
				
				$link = "Location: ficha_producto.php?numero=$producto";
				header($link);
				
				
			?>
 

</body>
</html>
<?php
}
ob_end_flush();
?>