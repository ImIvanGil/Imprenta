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
				$nota = $_GET['numero'];
				$registro = $_GET['key'];
				
			
				$db->consulta("DELETE FROM detalle_nota_credito WHERE id_detalle_nota='".$registro."';");
				
				$link = "Location: ficha_nota.php?numero=$nota";
				header($link);
				
				
			?>
 

</body>
</html>
<?php
}
ob_end_flush();
?>