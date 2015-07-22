<?php
include("clases/class.mysql.php");
include("clases/class.combos.php");
$ciudades = new selects();
$ciudades->code = $_GET["code"];
$ciudades = $ciudades->cargarCiudades();
foreach($ciudades as $key=>$value)
{
		$key = utf8_encode($key);
		$value = utf8_encode($value);
		echo "<option value=\"$key\">$value</option>";
}
?>