<?php
//require('../fpdf/fpdf.php');
require('../fpdf/rotate.php');


//conexion a la base de datos
$conexion = mysql_connect("localhost","root","root");
mysql_select_db("imprenta", $conexion);

//obtener numero del cargo
$clave = $_GET['clave'];
//consulto los datos del cargo
$sql_cargo = "SELECT * FROM cargo where `id_cargo`='".$clave."'";
$consulta_cargo = mysql_query($sql_cargo, $conexion) or die(mysql_error());
while($row_c = mysql_fetch_assoc($consulta_cargo)){
	$id_proveedor=$row_c['id_proveedor'];
	
	//datos del proveedor
	$sql_proveedor = "SELECT * FROM proveedor where `id_proveedor`='".$id_proveedor."'";
	$consulta_proveedor = mysql_query($sql_proveedor, $conexion) or die(mysql_error());
	while($row_proveedor = mysql_fetch_assoc($consulta_proveedor)){
		$nom_proveedor=$row_proveedor['nombre'];
		$rfc_proveedor=$row_proveedor['rfc'];
		$calle_proveedor=$row_proveedor['calle'];
		$numero_proveedor=$row_proveedor['numero'];
		$colonia_proveedor=$row_proveedor['colonia'];
		$ciudad_proveedor=$row_proveedor['ciudad'];
		$estado_proveedor=$row_proveedor['estado'];
		
		$dir_proveedor= $calle_proveedor." No.".$numero_proveedor;
	
		$cp_proveedor=$row_proveedor['codigo_postal'];
		$tel_proveedor="Tel.". $row_proveedor['telefono'];
		$correo=$row_proveedor['correo'];
		$cve_pais_proveedor=$row_proveedor['pais'];
		  //buscare el nombre del pais
		  $sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_proveedor."'";
		  $consulta_pais = mysql_query($sql_pais, $conexion) or die(mysql_error());
		  while($row_pa = mysql_fetch_array($consulta_pais)){
			  $pais_proveedor=$row_pa['Name'];
		  }
		
	}
			  
	
	$referencia=$row_c['referencia'];
	$id_tipo_pago=$row_c['id_tipo_pago'];
	$id_status_cargo=$row_c['id_status_cargo'];
	$fecha=$row_c['fecha'];
	$plazo_pago=$row_c['plazo_pago'];
	
	//consulto la fecha de pago sumando los dias de plazo
	$sql_vence = "SELECT DATE_ADD('".$fecha."', INTERVAL $plazo_pago DAY) as vence;";
	$consulta_vence = mysql_query($sql_vence, $conexion) or die(mysql_error());
	while($row_v = mysql_fetch_array($consulta_vence)){
		$vencimiento=$row_v['vence'];
	}
	
	$observaciones=$row_c['observaciones'];
	
	$id_forma_pago=$row_c['id_forma_pago'];
	  //forma de pago
	  $sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma_pago."'";
	  $consulta_forma = mysql_query($sql_forma, $conexion) or die(mysql_error());
	  $row_forma = mysql_fetch_assoc($consulta_forma);
	  $forma = $row_forma['forma_pago'];
	
	$id_metodo_pago=$row_c['id_metodo_pago'];
		$sql_met = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo_pago='".$id_metodo_pago."'";
		$consulta_met = mysql_query($sql_met, $conexion) or die(mysql_error());
		$row_met = mysql_fetch_assoc($consulta_met);
		$metodo = $row_met['metodo_pago'];
			  
	$id_status_cobranza=$row_c['id_status_cobranza'];
	  // estado de la factura
	  $sql_status = "SELECT status_cobranza FROM status_cobranza WHERE id_status_cobranza='".$id_status_cobranza."'";
	  $consulta_status = mysql_query($sql_status, $conexion) or die(mysql_error());
	  $row_status = mysql_fetch_assoc($consulta_status);
	  $status_cob = $row_status['status_cobranza'];
	
	$id_moneda=$row_c['id_moneda'];
	  $sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_moneda."'";
	  $consulta_mon = mysql_query($sql_mon, $conexion) or die(mysql_error());
	  $row_mon = mysql_fetch_assoc($consulta_mon);
	  $moneda = $row_mon['moneda'];
		
		  
	$tipo_cambio=$row_c['tipo_cambio'];
	$sub_total=$row_c['sub_total'];
	$impuestos=$row_c['impuestos'];
	$total = number_format($sub_total+$impuestos,2);
	$sub_total = number_format($sub_total,2);
	$impuestos =number_format($impuestos,2);
}

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
	$sal = utf8_decode('FICHA DE CARGO');
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
$pdf->text($x+174,$y+3,'Folio: ');

$pdf->SetTextColor(236,25,21);
$pdf->text($x+186,$y+3,$clave);
$pdf->SetTextColor(0,0,0);

//dibujar un rectangulo para el encabezado del solicitante
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+22, 105, 4,'DF');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+25,'DATOS DEL PROVEEDOR');
$pdf->SetFillColor(255,255,255);
$pdf->Rect($x+10, $y+26, 105, 24,'D');
$pdf->SetFont('Arial','',9);
//datos del cliente
$pdf->SetFont('Arial','',9);
$pdf->text($x+12,$y+30,$nom_proveedor);
$pdf->text($x+12,$y+34,$dir_proveedor);
$pdf->text($x+12,$y+37,$colonia_proveedor);
$dat4 = $ciudad_proveedor.", ".$estado_proveedor." C.P. ".$cp_proveedor;
$pdf->text($x+12,$y+47,$tel_proveedor);
$pdf->text($x+12,$y+51,$correo);
$pdf->text($x+12,$y+40,$dat4);

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

//dibujar un rectangulo para la fecha de pago
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+138, $y+38, 55, 4,'DF');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+153,$y+41,'VENCIMIENTO');
$pdf->SetFillColor(255,255,255);
$pdf->Rect($x+138, $y+42, 55, 8,'DF');
$pdf->SetFont('Arial','',9);
$pdf->text($x+155,$y+47,$vencimiento);

//dibujar un rectangulo para los datos del cargo
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+55, 183, 5,'DF');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+59,'DATOS DEL CARGO');
$pdf->SetFillColor(255,255,255);
$pdf->Rect($x+10, $y+60, 183, 31,'D');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+64,'Forma de Pago:');
$pdf->text($x+82,$y+64,'Metodo de Pago:');
$pdf->text($x+12,$y+69,'Plazo:');
$pdf->text($x+82,$y+69,'Referencia:');
$pdf->text($x+12,$y+74,'Moneda:');
$pdf->text($x+82,$y+74,'Tipo de Cambio:');
$pdf->text($x+12,$y+79,'Subtotal: $');
$pdf->text($x+12,$y+84,'Impuesto:$ ');
$pdf->text($x+12,$y+89,'Total Cargo:$ ');
$pdf->SetFont('Arial','',9);
$pdf->text($x+37,$y+64,$forma);
$pdf->text($x+109,$y+64,$metodo);
$pdf->text($x+22,$y+69,$plazo_pago.' dias');
$pdf->text($x+100,$y+69,$referencia);
$pdf->text($x+27,$y+74,$moneda);
$pdf->text($x+108,$y+74,$tipo_cambio);
$pdf->text($x+30,$y+79,$sub_total);
$pdf->text($x+30,$y+84,$impuestos);
$pdf->text($x+33,$y+89,$total);


//observaciones
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+95, 183, 5,'DF');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+99,'OBSERVACIONES');
$pdf->SetFillColor(255,255,255);
$pdf->Rect($x+10, $y+100, 183, 31,'D');
$pdf->SetFont('Arial','',9);
$pdf->SetXY($x+11,$y+102);
$pdf->MultiCell(152,3,$observaciones,0);


$pdf->Output();
?>