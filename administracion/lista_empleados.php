<?php

session_start();

ob_start();

?>

<?php include("../adminuser/adminpro_class.php");

$prot=new protect("1");

if ($prot->showPage) {

$curUser=$prot->getUser();

?> 



<?php

			header("Pragma: ");

			header('Cache-control: ');

			header("Expires: Mon, 26 Nov 2010 05:00:00 GMT");

			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");

			header("Cache-Control: no-store, no-cache, must-revalidate");

			header("Cache-Control: post-check=0, pre-check=0", false);

			header("Content-type: application/vnd.ms-excel");

			header("Content-disposition: attachment; filename=empleados.xls");

		

			

?>

<html>

<head>

<title>

</title>

</head>

<body>			

			<table>

			<tr><td align="center" colspan="15"><b>Lista de Empleados</b></td></tr>

            <tr><td align="center" colspan="15">&nbsp;</td></tr>

			</table>

			

			<table border="1px" border-color="#AFD89B">

			

			<tr align="center" bgcolor="#AFF4C9"><td><b>No.</b></td>
            <td><b>Clave</b></td>

			<td><b>Nombre</b></td>
            <td><b>R.F.C.</b></td>

			<td><b>Direcci&oacute;n</b></td>

            <td><b>Tel&eacute;fono</b></td>

			<td><b>Celular</b></td>

			<td><b>Puesto</b></td>

            <td><b>Salario Diario</b></td>
            <td><b>Comisi&oacute;n</b></td>

            <td><b>Hora Entrada</b></td>

			<td><b>Fecha Alta</b></td>

            <td><b>IMSS</b></td>

			<td><b>Status</b></td>

			<td><b>Fecha Baja</b></td>

			</tr>

			

			<?php 

				include("../lib/mysql.php");

				$db = new MySQL();



				$lista_empleados = $db->consulta("SELECT *  FROM `empleado` ORDER BY id_empleado;");

				while ($row = $db->fetch_array($lista_empleados))

					{

					$numero = $row['id_empleado'];
					$cve = $row['clave'];
					$comi = number_format($row['comision']*100,2);
					$rfc = $row['rfc'];

					$nombre = $row['nombre'];

					$direccion = $row['direccion'];

					$telefono = $row['telefono'];

					$celular = $row['celular'];

					$salario = $row['salario_diario'];

					$salario = number_format($salario,2);

					$entrada = $row['hora_entrada'];					

					$alta = $row['fecha_contratacion'];

					$id_status = $row['id_status'];

					$id_puesto = $row['id_puesto'];

					$imss = $row['imss'];

					

					

					

					if($id_status==1){

						$baja="";

					}else{

						if($id_status==-1){

							$baja="";

						}else{

							if($id_status==""){

								$baja="";

							}else{

									$baja = $row['fecha_baja'];

							}

						}

					}

					

					$consulta_status = $db->consulta("SELECT * FROM `status_empleado` WHERE id_status='".$id_status."'");

					while ($row1 = $db->fetch_array($consulta_status))

					{

						$status = $row1['status'];

					}

					$consulta_puesto = $db->consulta("SELECT * FROM `puesto` WHERE id_puesto='".$id_puesto."'");

					while ($row2 = $db->fetch_array($consulta_puesto))

					{

						$puesto = $row2['puesto'];

					}

					

					 echo "<tr><td>".$numero."</td>
					 <td>".$cve."</td>

					 <td>".$nombre."</td>
					 <td>".$rfc."</td>

					 <td>".$direccion."</td>

					 <td>".$telefono."</td>

					 <td>".$celular."</td>

					 <td>".$puesto."</td>

					 <td>$ ".$salario."</td>
					 
					 <td>$ ".$comi."%</td>

					 <td>".$entrada."</td>

					 <td>".$alta."</td>

					 <td>".$imss."</td>

					 <td>".$status."</td>

					 <td>".$baja."</td>			 

					 </tr>";

					

					}

			?>

			

			

			</table>

			

</body>

</html>

<?php

}

?> 

<?php

ob_end_flush();

?> 

