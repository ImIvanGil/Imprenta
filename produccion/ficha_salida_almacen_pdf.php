<?php
//require('../fpdf/fpdf.php');
require('../fpdf/rotate.php');


//conexion a la base de datos
$conexion = mysql_connect("localhost","root","root");
mysql_select_db("imprenta", $conexion);

//obtener numero de orden
$clave = $_GET['movimiento'];
$numero = $_GET['numero'];
$fecha = $_GET['fecha'];
$solicita = $_GET['solicita'];
$cant = $_GET['cantidad'];
$nomprod = $_GET['nomprod'];

$id_empresa = 1;
//datos de la empresa
$sql_empresa = "SELECT * FROM empresa where `id_empresa`='".$id_empresa."'";
$consulta_empresa = mysql_query($sql_empresa, $conexion) or die(mysql_error());
while($row_emp = mysql_fetch_assoc($consulta_empresa)){
	$nom_emp=$row_emp['nombre'];
	$rfc_emp=$row_emp['rfc'];
	$calle_emp=$row_emp['calle'];
	$numero_emp=$row_emp['numero'];
	$colonia_emp=$row_emp['colonia'];
	$ciudad_emp=$row_emp['ciudad'];
	$estado_emp=$row_emp['estado'];
	$cp_emp=$row_emp['codigo_postal'];
	$cve_pais_emp=$row_emp['pais'];
	$regimen=$row_emp['regimen'];
	$dir_emp= $calle_emp." No.".$numero_emp;
	//buscare el nombre del pais
	$sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_emp."'";
	$consulta_pais = mysql_query($sql_pais, $conexion) or die(mysql_error());
	while($row_pa = mysql_fetch_array($consulta_pais)){
		$pais_emp=$row_pa['Name'];
	}
}
	

class PDF extends PDF_Rotate
{
function RotatedText($x, $y, $txt, $angle)
{
    //Text rotated around its origin
    $this->Rotate($angle, $x, $y);
    $this->Text($x, $y, $txt);
    $this->Rotate(0);
}

function RotatedImage($file, $x, $y, $w, $h, $angle)
{
    //Image rotated around its upper-left corner
    $this->Rotate($angle, $x, $y);
    $this->Image($file, $x, $y, $w, $h);
    $this->Rotate(0);
}
	
//Cabecera de pÃ¡gina
function Header()
{
	$sal = utf8_decode('SALIDA DE ALMACEN');
	//margenes de pagina
	$this->SetMargins(.5,2,.5);
    //Logo
    $this->Image('../images/logo.jpg',15,12,50);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha
    $this->Cell(80);
    //TÃ­tulo
    $this->Cell(110,3,$sal,0,0,'R');
    //Salto de lÃ­nea
    $this->Ln(20);
}

//Pie de pÃ¡gina
function Footer()
{
    //PosiciÃ³n: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','BI',8);
	$this->SetTextColor(0,0,0);
	
}
}

//CreaciÃ³n del objeto de la clase heredada
$pdf=new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','b',9);
$pdf->SetAutoPageBreak(1,15);

// variables que controlan la posicion del contenido y el ancho de la linea cuando hay que brincar solo un renglon
$x=5;
$y=15;
$l=5;
$pdf->SetFont('Arial','B',10);
//datos de la empresa
$pdf->text($x+65,$y,$nom_emp);
$pdf->SetFont('Arial','',9);
$pdf->text($x+93,$y+3,$rfc_emp);
$pdf->text($x+87,$y+6,$dir_emp);
$pdf->text($x+84,$y+9,$colonia_emp);
$dat = $ciudad_emp.", ".$estado_emp." C.P. ".$cp_emp;
$pdf->text($x+79,$y+12,$dat);

//folio de la orden
$folio = str_pad($clave, 6, "0", STR_PAD_LEFT);
$pdf->SetFont('Arial','B',12);
$pdf->text($x+174,$y+6,'Folio: ');

$pdf->SetTextColor(236,25,21);
$pdf->text($x+186,$y+6,$clave);
$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','BI',10);
$pdf->text($x+159,$y+2,'Producto Terminado');
$pdf->SetTextColor(0,0,0);

//dibujar un rectangulo para el encabezado del solicitante
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+22, 105, 4,'DF');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+25,'NOMBRE DEL SOLICITANTE');
$pdf->SetFillColor(255,255,255);
$pdf->Rect($x+10, $y+26, 105, 8,'DF');
$pdf->SetFont('Arial','',9);
$pdf->text($x+12,$y+31,$solicita);

//dibujar un rectangulo para el encabezado de fecha
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+138, $y+22, 55, 4,'DF');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+158,$y+25,'FECHA');
$pdf->SetFillColor(255,255,255);
$pdf->Rect($x+138, $y+26, 55, 8,'DF');
$pdf->SetFont('Arial','',9);
$pdf->text($x+155,$y+31,$fecha);

//CUADRO CON EL DETALLE DE LA SALIDA
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+37, 183, 5,'DF');
$pdf->Rect($x+10, $y+37, 36, 14,'D');
$pdf->Rect($x+46, $y+37, 147, 14,'D');

$pdf->SetFont('Arial','B',9);
$pdf->text($x+18,$y+41,'CANTIDAD');
$pdf->text($x+102,$y+41,'DESCRIPCION');
$pdf->SetFont('Arial','',9);
$pdf->text($x+26,$y+48,$cant);
$pdf->text($x+50,$y+48,$nomprod);

//recuadro para firmas
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+54, 183, 5,'DF');
$pdf->Rect($x+10, $y+54, 46, 20,'D');
$pdf->Rect($x+56, $y+54, 46, 20,'D');
$pdf->Rect($x+102, $y+54, 46, 20,'D');
$pdf->Rect($x+148, $y+54, 45, 20,'D');

$pdf->SetFont('Arial','B',9);
$pdf->text($x+24,$y+58,'SOLICITO');
$pdf->text($x+72,$y+58,'ENTREGO');
$pdf->text($x+118,$y+58,'FACTURA');
$pdf->text($x+163,$y+58,'REMISION');



$pdf->Output();
?>