<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost", "root", "root");
mysql_select_db("imprenta", $conexion);

//obtener numero de proveedor
$clave = $_GET['numero'];

$queproveedor = "SELECT * FROM `proveedor` WHERE id_proveedor='".$clave."'";
$proveedor = mysql_query($queproveedor, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($proveedor)) {
			
			// datos generales del proveedor
			$nombre = $row['nombre'];
			$calle = utf8_encode($row['calle']);
			$num = $row['numero'];
			$colonia = utf8_encode($row['colonia']);
			$ciudad = utf8_encode($row['ciudad']);
			$estado = utf8_encode($row['estado']);
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
			$direccion = $calle." ".$num.", ".$colonia.", ".$ciudad." C.P. ".$cp;
			
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
    $this->Cell(42,40,'Sistema Cotizador',0,0,'C');
	
	$this->SetFont('Arial','I',12);
	$this->SetTextColor(105, 176, 188);
	$this->SetX(23);
	$this->Cell(0,52,'Ficha de Proveedor',0,0,'C');
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
$pdf->Rect($x+10, $y+20, 170, 43,'DF');

//Clave de proveedor
$dat1 = utf8_decode('No. de proveedor: ');
$pdf->text($x+140,$y+18,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+168,$y+18,$clave);
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

$pdf->Output();

?>