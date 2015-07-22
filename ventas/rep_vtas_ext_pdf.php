<?php
require('../fpdf/fpdf.php');

function cambiarFormatoFecha($fecha){
	list($anio,$mes,$dia)=explode("-",$fecha);
	return $dia."-".$mes."-".$anio;
}

//recibo los datos de fechas
$inicio = $_GET['inicio'];
$fin = $_GET['fin'];	
$finicio = cambiarFormatoFecha($inicio);
$ffin = cambiarFormatoFecha($fin);	


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
	$this->Cell(0,52,'Comparativo de ventas en moneda nacional y extranjera',0,0,'C');
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

// Subtitulo con las fechas
$pdf->SetFont('Arial','BI',10);
$limites = "del ".$finicio." a ".$ffin;
$pdf->text($x+79,$y+18,$limites);

//inserto la imagen de la grafica
$imagen = "graficas/ventas_ext.png";
$pdf->Image($imagen,$x+48,$y+29);
$pdf->Output();

?>