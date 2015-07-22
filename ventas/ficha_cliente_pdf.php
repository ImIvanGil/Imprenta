<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost", "root", "root");
mysql_select_db("imprenta", $conexion);

//obtener numero de cliente
$clave = $_GET['numero'];

$quecliente = "SELECT * FROM `cliente` WHERE id_cliente='".$clave."'";
$cliente = mysql_query($quecliente, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($cliente)) {
			
			// datos generales del cliente
			$nombre = $row['nombre'];
			$cve = $row['clave'];
			$calle = $row['calle'];
			$num = $row['numero'];
			$numInt = $row['no_interior'];
			$colonia = $row['colonia'];
			$ciudad = $row['ciudad'];
			$estado = $row['estado'];
			$tel = $row['telefono'];
			$cp = $row['codigo_postal'];
			$mail = $row['correo'];
			$rfc = $row['rfc'];
			$contacto = $row['contacto'];
			$cve_pais = $row['pais'];
			
			//consulta del nombre de pais
			$consulta_pais = "SELECT *  FROM `pais` WHERE `Code` = '".$cve_pais."';";
			$cons = mysql_query($consulta_pais, $conexion) or die(mysql_error());
			while ($row2 = mysql_fetch_assoc($cons))
			{
				$pais = $row2['Name'];
			}
			$direccion = $calle." ".$num." ".$numInt.", ".$colonia.", ".$ciudad." C.P. ".$cp;
			
			
			$id_vendedor = $row['id_vendedor'];
			$consulta_vende = "SELECT nombre  FROM `empleado` WHERE `id_empleado` = '".$id_vendedor."';";
			$cons = mysql_query($consulta_vende, $conexion) or die(mysql_error());
			while ($row2 = mysql_fetch_assoc($cons))
			{
				$vendedor = $row2['nombre'];
			}
			
			$id_tipo = $row['id_tipo_cliente'];
			$consulta_tipo = "SELECT *  FROM `tipo_cliente` WHERE `id_tipo_cliente` = '".$id_tipo."';";
			$cons = mysql_query($consulta_tipo, $conexion) or die(mysql_error());
			while ($row2 = mysql_fetch_assoc($cons))
			{
				$tipo = $row2['tipo_cliente'];
			}
			
			$id_status = $row['id_estado_cliente'];
			$consulta_sta = "SELECT *  FROM `status_cliente` WHERE `id_status_cliente` = '".$id_status."';";
			$cons = mysql_query($consulta_sta, $conexion) or die(mysql_error());
			while ($row2 = mysql_fetch_assoc($cons))
			{
				$status = $row2['status_cliente'];
			}
			
			$id_facturacion = $row['id_tipo_facturacion'];
			$consulta_fac = "SELECT *  FROM `tipo_facturacion` WHERE `id_tipo_facturacion` = '".$id_facturacion."';";
			$cons = mysql_query($consulta_fac, $conexion) or die(mysql_error());
			while ($row2 = mysql_fetch_assoc($cons))
			{
				$facturacion = $row2['tipo_facturacion'];
			}
			
			
			$limite = $row['limite_credito'];
			$limite = number_format($limite,2);
			
      }


class PDF extends FPDF
{
//Cabecera de página
function Header()
{
    //Logo
    //$this->Image('../images/logo.gif',24,17,43);
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    $this->SetTextColor(136,155,8);
    //Movernos a la derecha
    $this->Cell(80);
    //Título
    $this->Cell(42,40,'Sistema ERP',0,0,'C');
	
	$this->SetFont('Arial','I',12);
	$this->SetTextColor(105, 176, 188);
	$this->SetX(23);
	$this->Cell(0,52,'Ficha de Cliente',0,0,'C');
    //Salto de línea
    $this->Ln(20);
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Número de página
    $this->Cell(0,10,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
}
}

//Creación del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(5,20,3);
$pdf->SetFont('Arial','b',9);
$pdf->SetTextColor(63,63,63);
$pdf->SetAutoPageBreak(1,70);



// variables que controlan la posicion del contenido y el ancho de la linea cuando hay que brincar solo un renglon
$x = $pdf->GetX();
$y = $pdf->GetY();
/*$x=5;
$y=20;
$l=5;*/

//dibujar un rectangulo para los datos generales
$pdf->SetDrawColor(136,155,8);
$pdf->SetFillColor(219,245,249);
$pdf->SetLineWidth(0.4);
$pdf->Rect($x+10, $y+20, 170, 68,'DF');

//numero de cliente
$dat1 = utf8_decode('No. de Cliente: ');
$pdf->text($x+140,$y+15,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+163,$y+15,$clave);
$pdf->SetFont('Arial','B',9);

//Clave de cliente
$dat1 = utf8_decode('Clave: ');
$pdf->text($x+140,$y+18,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+150,$y+18,$cve);
$pdf->SetFont('Arial','B',9);

// Subtitulo de datos generales
$pdf->SetFont('Arial','BI',10);
$pdf->text($x+19,$y+18,'Datos de Registro');

// datos generales
//nombre
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+25,'Nombre:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+33,$y+25,$nombre);

//contacto
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+30,'Contacto:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+35,$y+30,$contacto);

//direccion
$pdf->SetFont('Arial','B',9);
$dir = utf8_decode('Dirección: ');
$pdf->text($x+19,$y+35,$dir);
$pdf->SetFont('Arial','',9);
$pdf->text($x+35,$y+35,$direccion);

//estado
$pdf->SetFont('Arial','B',9);
$dir = utf8_decode('Estado: ');
$pdf->text($x+19,$y+40,$dir);
$pdf->SetFont('Arial','',9);
$pdf->text($x+31,$y+40,$estado);

//pais
$pdf->SetFont('Arial','B',9);
$dir = utf8_decode('País: ');
$pdf->text($x+19,$y+45,$dir);
$pdf->SetFont('Arial','',9);
$pdf->text($x+28,$y+45,$pais);

//Telefono
$pdf->SetFont('Arial','B',9);
$dat10 = utf8_decode('Teléfono: ');
$pdf->text($x+19,$y+50,$dat10);
$pdf->SetFont('Arial','',9);
$pdf->text($x+34,$y+50,$tel);

//E-mail
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+55,'E-mail:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+30,$y+55,$mail);

//rfc
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+60,'R.F.C.:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+30,$y+60,$rfc);

//vendedor
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+65,'Vendedor asignado:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+50,$y+65,$vendedor);

//tipo de cliente
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+70,'Tipo de cliente:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+43,$y+70,$tipo);

//limite de credito
$pdf->SetFont('Arial','B',9);
$dat1 = utf8_decode('Limite de crédito:');
$pdf->text($x+19,$y+75,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+46,$y+75,'$'.$limite);

//status
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+80,'Estado del cliente:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+48,$y+80,$status);

//tipo facturacion
$pdf->SetFont('Arial','B',9);
$dat1 = utf8_decode('Tipo de facturación:');
$pdf->text($x+19,$y+85,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+50,$y+85,$facturacion);

$pdf->Output();

?>