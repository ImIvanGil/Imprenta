<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost", "root", "root");
mysql_select_db("imprenta", $conexion);

//obtener numero de producto
$clave = $_GET['numero'];
$puesto = $_GET['puesto'];
$status = $_GET['status'];


$quempleado = "SELECT * FROM `empleado` WHERE id_empleado='".$clave."'";
$empleado = mysql_query($quempleado, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($empleado)) {
			
			// datos generales del empleado
			$nombre = $row['nombre'];
			$rfc = $row['rfc'];
			$cve = $row['clave'];
			$comi = $row['comision']*100;
			$comi = number_format($comi,2);
			$nombre =  utf8_decode($nombre);
			$direccion = $row['direccion'];
			$direccion =  utf8_decode($direccion);
			$tel = $row['telefono'];
			$cel = $row['celular'];
			$sal = $row['salario_diario'];
			$sal = number_format($sal,2);
			$alta = $row['fecha_contratacion'];
			$baja = $row['fecha_baja'];
			$imss = $row['imss'];
			$id_status = $row['id_status'];
			$id_puesto = $row['id_puesto'];
			$puesto =  utf8_decode($puesto);
			$status =  utf8_decode($status);
			$entrada = $row['hora_entrada'];
			$observaciones = $row['observaciones'];
			
      }


class PDF extends FPDF
{
//Cabecera de página
function Header()
{
    //Logo
    $this->Image('../images/logo.jpg',24,17,43);
    $this->SetFont('Arial','B',15);
    $this->SetTextColor(136,155,8);
    //Movernos a la derecha
    $this->Cell(80);
    //Título
    $this->Cell(42,40,'Sistema ERP',0,0,'C');
	
	$this->SetFont('Arial','I',12);
	$this->SetTextColor(105, 176, 188);
	$this->SetX(23);
	$this->Cell(0,52,'Ficha de Empleado',0,0,'C');
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
$pdf->Rect($x+15, $y+20, 175, 37,'DF');

//No. de empleado
$dat1 = utf8_decode('No. de Empleado: ');
$pdf->text($x+140,$y+12,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+168,$y+12,$clave);
$pdf->SetFont('Arial','B',9);

//Clave de empleado
$dat1 = utf8_decode('Clave: ');
$pdf->text($x+157,$y+18,$dat1);
$pdf->SetFont('Arial','',9);
$pdf->text($x+168,$y+18,$cve);
$pdf->SetFont('Arial','B',9);

// status
$dat2 = utf8_decode('Status: ');
$pdf->text($x+156,$y+25,$dat2);
$pdf->SetFont('Arial','',9);
$pdf->text($x+168,$y+25,$status);

// Subtitulo de datos generales
$pdf->SetFont('Arial','BI',10);
$pdf->text($x+19,$y+18,'Datos de Registro');

// datos generales
//Puesto
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+25,'Puesto:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+33,$y+25,$puesto);

//nombre
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+30,'Nombre:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+33,$y+30,$nombre);

//direccion
$pdf->SetFont('Arial','B',9);
$dir = utf8_decode('Dirección: ');
$pdf->text($x+19,$y+35,$dir);
$pdf->SetFont('Arial','',9);
$pdf->text($x+35,$y+35,$direccion);

//Telefono
$pdf->SetFont('Arial','B',9);
$dat10 = utf8_decode('Teléfono: ');
$pdf->text($x+19,$y+40,$dat10);
$pdf->SetFont('Arial','',9);
$pdf->text($x+34,$y+40,$tel);

//Celular
$pdf->SetFont('Arial','B',9);
$pdf->text($x+130,$y+40,'Celular: ');
$pdf->SetFont('Arial','',9);
$pdf->text($x+144,$y+40,$cel);

//Salario diario
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+45,'Salario Diario:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+42,$y+45,'$ '.$sal);

//comision
$pdf->SetFont('Arial','B',9);
$pdf->text($x+130,$y+45,'Comision: ');
$pdf->SetFont('Arial','',9);
$pdf->text($x+146,$y+45,$comi.' %');

//rfc
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+50,'R.F.C.:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+30,$y+50,$rfc);

//hora de entrada
$dat8 = utf8_decode('Hora de Entrada:');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+72,$y+50,$dat8);
$pdf->SetFont('Arial','',9);
$pdf->text($x+99,$y+50,$entrada);

//Clave IMSS
$dat3 = utf8_decode('Clave IMSS:');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+130,$y+50,$dat3);
$pdf->SetFont('Arial','',9);
$pdf->text($x+150,$y+50,$imss);

//fecha de alta
$pdf->SetFont('Arial','B',9);
$pdf->text($x+19,$y+55,'Fecha de Alta:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+42,$y+55,$alta);

//si el status es suspendido o vetado imprimimos la fecha de baja
if($id_status!=1){
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+130,$y+55,'Fecha de Baja:');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+154,$y+55,$baja);
}

if($observaciones!=""){
	//observaciones
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+19,$y+62,'Observaciones:');
	//indicamos a partir de donde va a dibujar las celdas
	$pdf->SetXY($x+15,$y+65);
	$pdf->SetFont('Arial','',9);
	//dibujamos la celda
	$pdf->MultiCell(175,3,$observaciones,1,'J',true);
}


$pdf->Output();

?>