<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost","root","sru3s2gg");
mysql_select_db("imprenta", $conexion);

//obtener numero de requisicion
$clave = $_GET['clave'];

//variables que acumularan los totales
$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_requisicion = 0;

$queOrd = "SELECT * FROM `requisicion` WHERE id_requisicion='".$clave."'";
$requisicion = mysql_query($queOrd, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($requisicion)) {
			
			// datos generales de la requisicion
			
			$id_empresa = $row['id_empresa'];
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
			
			$id_proveedor = $row['id_proveedor'];
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
				  $tel_proveedor=$row_proveedor['telefono'];
				  $cve_pais_proveedor=$row_emp['pais'];
					//buscare el nombre del pais
					$sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_proveedor."'";
					$consulta_pais = mysql_query($sql_pais, $conexion) or die(mysql_error());
					while($row_pa = mysql_fetch_array($consulta_pais)){
						$pais_proveedor=$row_pa['Name'];
					}
				  
			  }
			  
			  $fecha = $row['fecha'];
			  
			  $id_status = $row['id_status'];
			  // estado de la requisicion
			  $sql_status = "SELECT status FROM status_orden WHERE id_status='".$id_status."'";
			  $consulta_status = mysql_query($sql_status, $conexion) or die(mysql_error());
			  $row_status = mysql_fetch_assoc($consulta_status);
			  $status = $row_status['status'];
			  
			  
			  $observaciones = $row['observaciones'];
											
			 
			  //obtengo el dato de quien requisiciona y quien autoriza
			  
			  $id_requisiciona = $row['id_ordena'];
			  //voy a buscar nombre del usuario
			  $sql_requisiciona = "SELECT * FROM myuser WHERE ID='".$id_requisiciona."'";
			  $consulta_requisiciona = mysql_query($sql_requisiciona, $conexion) or die(mysql_error());
			  $row_requisiciona = mysql_fetch_assoc($consulta_requisiciona);
			  $requisiciona = $row_requisiciona['userRemark'];
			  
			  $id_autoriza = $row['id_autoriza'];
			  //voy a buscar nombre del usuario
			  $sql_autoriza = "SELECT * FROM myuser WHERE ID='".$id_autoriza."'";
			  $consulta_autoriza = mysql_query($sql_autoriza, $conexion) or die(mysql_error());
			  $row_autoriza = mysql_fetch_assoc($consulta_autoriza);
			  $autoriza = $row_autoriza['userRemark'];
			  
			  
			
			
      }


class PDF extends FPDF
{
//Cabecera de pÃ¡gina
function Header()
{
	//margenes de pagina
	$this->SetMargins(.5,2,.5);
    //Logo
    $this->Image('../images/logo.jpg',15,12,50);
    //Arial bold 15
    $this->SetFont('Arial','B',10);
    //Movernos a la derecha
    $this->Cell(80);
    //TÃ­tulo
    $this->Cell(110,3,'REQUISICION DE COMPRA',0,0,'R');
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
//$pdf->SetMargins(3,10,3);
$pdf->SetFont('Arial','b',9);
//$pdf->SetTextColor(63,63,63);
$pdf->SetAutoPageBreak(1,15);



// variables que controlan la posicion del contenido y el ancho de la linea cuando hay que brincar solo un renglon
//$x = $pdf->GetX();
//$y = $pdf->GetY();
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

//folio de la requisicion
$folio = str_pad($clave, 6, "0", STR_PAD_LEFT);
$pdf->SetFont('Arial','B',12);
$pdf->text($x+167,$y+2,'Folio: ');

$pdf->SetTextColor(236,25,21);
$pdf->text($x+180,$y+2,$folio);
$pdf->SetTextColor(0,0,0);

$pdf->SetFont('Arial','B',9);
$dat5 = 'Fecha de Expedicion: ';
$pdf->text($x+143,$y+6,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+177,$y+6,$fecha);

//dibujar un rectangulo para el encabezado de proveedor
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+22, 105, 4,'DF');

$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+25,'PROVEEDOR RECOMENDADO');

//otro rectangulo para los datos del proveedor
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+26, 105, 20,'DF');

//datos del proveedor
$pdf->SetFont('Arial','',9);
$pdf->text($x+12,$y+29,$nom_proveedor);
$pdf->text($x+12,$y+32,$dir_proveedor);
$pdf->text($x+12,$y+35,$colonia_proveedor);
$dat4 = $ciudad_proveedor.", ".$estado_proveedor." C.P. ".$cp_proveedor;
$pdf->text($x+12,$y+38,$dat4);


//dibujar un rectangulo para los encabezados
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);

$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 185, 4,'DF');
//encabezados de la tabla de detalle
$pdf->SetFont('Arial','B',9);
$pdf->text($x+13,$y+53,'NUM. PARTE');
$pdf->text($x+43,$y+53,'CANT.');
$pdf->text($x+89,$y+53,'INSUMO');
$pdf->text($x+150,$y+53,'COMENTARIOS');
$pdf->SetFont('Arial','',9);

//recuadros para enmarcar el detalle de la requisicion
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 25, 70,'D');
$pdf->Rect($x+35, $y+50, 25, 70,'D');
$pdf->Rect($x+60, $y+50, 70, 70,'D');
$pdf->Rect($x+130, $y+50, 65, 70,'D');

//una variable para llevar la cuenta de las lineas  y otra para acumular los totales
$n = 0;

$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_requisicion = 0;

$actual = $pdf->GetY();
$salto = 4;
$m = $y+57;
//Coordenadas auxiliares
$y5=70;
//ahora vamos a hacer un ciclo para la consulta del detalle de la requisicion
$sql_det = "SELECT * FROM detalle_requisicion WHERE id_requisicion='".$clave."'";
$consulta_det = mysql_query($sql_det, $conexion) or die(mysql_error());
while ($row = mysql_fetch_assoc($consulta_det)){

	$n= $n+1;
	
	$id_insumo = $row['id_insumo'];
	
	$no_parte = $row['num_parte'];
	
	$cantidad = $row['cantidad'];
	

	$obs = $row['descripcion'];
	
	
	//consultare el nombre del insumo
	$sql_ins = "SELECT *  FROM `insumo` WHERE `id_insumo` = '".$id_insumo."';";
	$consulta_ins = mysql_query($sql_ins, $conexion) or die(mysql_error());
	 while ($row2 = mysql_fetch_assoc($consulta_ins)){
		 $nom_insumo = $row2['nombre'];
		 $desc_insumo = utf8_decode($row2['descripcion']);
		 $id_unidad = $row2['id_unidad'];
		 //aqui dentro consulto el nombre de la unidad
		 	$sql_uni = "SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';";
			$cons_uni = mysql_query($sql_uni, $conexion) or die(mysql_error());
			 while ($row15 = mysql_fetch_assoc($cons_uni)){
				 $unidad = $row15['unidad'];
			}
	}
	

	
	$act = 'Act1 = '.$actual;
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+18,$m+3,$no_parte);
	$pdf->text($x+47,$m+3,$cantidad);
	//$pdf->text($x+68,$m,$nom_insumo);
	$pdf->SetXY($x+61,$m);
	$pdf->SetFillColor(0,0,0);
	$pdf->MultiCell(68,4,$nom_insumo, 0 , 'L' , false);
	$y5=$pdf->GetY();
	$pdf->text($x+134,$m+3,$obs,true);
	$pdf->SetXY($x+47,$m);
	$actual = $pdf->GetY();
	
	
	if ($actual >= 200)
	{
		$pdf->AddPage();
		
		$pdf->SetX(5);
		$pdf->SetY(20);
		//$pdf->SetMargins(5,20,3);
		$x = $pdf->GetX();
		$y = $pdf->GetY();
		$m = $y+3;
	}else{
		//$m = $y + $actual;
		//$m= $m+$salto;
		$aux=m;
		$m=$y5;
		$y5=$aux;
	}

}

//volvemos a meterle valores a x para que se muestren los totales
//$y= $m+4;
//$y=220;
$y=120;

//observaciones a la requisicion
$pdf->SetFont('Arial','B',9);
$pdf->text($x+11,$y+20,'PROPOSITO DE LA COMPRA:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+11,$y+21);
$pdf->SetFont('Arial','',9);
//dibujamos la celda
$pdf->MultiCell(183,3,$observaciones,0);

$pdf->Output();

?>