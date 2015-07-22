<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost","root","root");
mysql_select_db("imprenta", $conexion);

//obtener numero de orden
$clave = $_GET['clave'];

//variables que acumularan los totales
$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_orden = 0;

$queOrd = "SELECT * FROM `orden_compra` WHERE id_orden='".$clave."'";
$orden = mysql_query($queOrd, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($orden)) {
			
			// datos generales de la orden
			
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
			  // estado de la orden
			  $sql_status = "SELECT status FROM status_orden WHERE id_status='".$id_status."'";
			  $consulta_status = mysql_query($sql_status, $conexion) or die(mysql_error());
			  $row_status = mysql_fetch_assoc($consulta_status);
			  $status = $row_status['status'];
			  
			  $id_mon = $row['id_moneda'];
			  //moneda
			  $sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
			  $consulta_mon = mysql_query($sql_mon, $conexion) or die(mysql_error());
			  $row_mon = mysql_fetch_assoc($consulta_mon);
			  $moneda = $row_mon['moneda'];
			  
			  $t_cambio = $row['tipo_cambio'];
			  $plazo = $row['plazo'];
			  $observaciones = $row['observaciones'];
											
			  $id_iva = $row['id_iva'];
			  //voy a buscar la tasa del iva que vamos a usar
			  $sql_tas = "SELECT * FROM iva WHERE id_iva='".$id_iva."'";
			  $consulta_tas = mysql_query($sql_tas, $conexion) or die(mysql_error());
			  $row_tas = mysql_fetch_assoc($consulta_tas);
			  $tasa_iva = $row_tas['tasa'];
			  $tipo_iva = $row_tas['tipo'];
			  
			  //obtengo el dato de quien ordena y quien autoriza
			  
			  $id_ordena = $row['id_ordena'];
			  //voy a buscar nombre del usuario
			  $sql_ordena = "SELECT * FROM myuser WHERE ID='".$id_ordena."'";
			  $consulta_ordena = mysql_query($sql_ordena, $conexion) or die(mysql_error());
			  $row_ordena = mysql_fetch_assoc($consulta_ordena);
			  $ordena = $row_ordena['userRemark'];
			  
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
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha
    $this->Cell(80);
    //TÃ­tulo
    $this->Cell(110,3,'ORDEN DE COMPRA',0,0,'R');
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

//folio de la orden
$folio = str_pad($clave, 6, "0", STR_PAD_LEFT);
$pdf->SetFont('Arial','B',12);
$pdf->text($x+167,$y+2,'Folio: ');

$pdf->SetTextColor(236,25,21);
$pdf->text($x+180,$y+2,$folio);
$pdf->SetTextColor(0,0,0);

//dibujar un rectangulo para el encabezado de proveedor
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+22, 105, 4,'DF');

$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+25,'DATOS DEL PROVEEDOR');

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


//otro rectangulo para los datos de expedicion
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+120, $y+26, 75, 13,'DF');

//datos de expedicion
$pdf->SetFont('Arial','B',9);
$dat5 = 'Fecha de Expedicion: ';
$pdf->text($x+122,$y+29,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+156,$y+29,$fecha);

$pdf->SetFont('Arial','B',9);
$pdf->text($x+122,$y+32,'Moneda: ');
$pdf->SetFont('Arial','',9);
$pdf->text($x+136,$y+32,$moneda);

$pdf->SetFont('Arial','B',9);
$pdf->text($x+122,$y+36,'Tipo de Cambio: ');
$pdf->SetFont('Arial','',9);
$t_cambio = number_format($t_cambio,2);
$pdf->text($x+148,$y+36,$t_cambio);



//dibujar un rectangulo para los encabezados
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);

$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 185, 4,'DF');
//encabezados de la tabla de detalle
$dat6 = utf8_decode('DESCRIPCIÓN');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+13,$y+53,'NUM.');
$pdf->text($x+28,$y+53,'CANT.');
$pdf->text($x+63,$y+53,'INSUMO');
$pdf->text($x+98,$y+53,'ANCHO');
$pdf->text($x+116,$y+53,'LARGO');
$pdf->text($x+133,$y+53,'UNITARIO');
$pdf->text($x+158,$y+53,'DESC.');
$pdf->text($x+179,$y+53,'TOTAL');
$pdf->SetFont('Arial','',9);

//recuadros para enmarcar el detalle de la orden
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 14, 130,'D');
$pdf->Rect($x+24, $y+50, 18, 130,'D');
$pdf->Rect($x+42, $y+50,54, 130,'D');
$pdf->Rect($x+96, $y+50,17, 130,'D');
$pdf->Rect($x+113, $y+50,17, 130,'D');
$pdf->Rect($x+130, $y+50,22, 130,'D');
$pdf->Rect($x+152, $y+50,22, 130,'D');
$pdf->Rect($x+174, $y+50,21, 130,'D');


//una variable para llevar la cuenta de las lineas  y otra para acumular los totales
$n = 0;

$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_orden = 0;

$actual = $pdf->GetY();
$salto = 4;
$m = $y+57;
//ahora vamos a hacer un ciclo para la consulta del detalle de la orden
$sql_det = "SELECT * FROM detalle_orden WHERE id_orden='".$clave."'";
$consulta_det = mysql_query($sql_det, $conexion) or die(mysql_error());
while ($row = mysql_fetch_assoc($consulta_det)){

	$n= $n+1;
	
	$id_insumo = $row['id_insumo'];
	
	$no_parte = $row['num_parte'];
	
	$cantidad = $row['cantidad'];
	
	$unitario = $row['precio'];
	
	$descuento = $row['descuento'];
	
	$obs = $row['observaciones'];
	
	$ancho = $row['ancho'];
	
	$largo = $row['largo'];
	
	$val = $unitario - $descuento;
	$clase_iva = $row['id_clase_iva'];
	
	$precio = $unitario * $cantidad;
	$monto_desc = $precio*$descuento/100;
	$precio_desc = $precio-$monto_desc;
	$unitario_desc = $precio_desc/$cantidad;
	
	$sub_total = $sub_total + $precio_desc;
	
	
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
	
	
	
	//voy a acumular las cantidades para calcular el iva segun como sea la clase
	  switch($clase_iva){
		  case 1:
			  $graba_normal = $graba_normal+$precio_desc;
		  break;
		  case 2:
			  $graba_cero = $graba_cero+$precio_desc;
		  break;
		  case 3:
			  $exento = $exento + $precio_desc;
		  break;
	  }
	

	$funitario = "$ ".number_format($unitario,4);
	$fprecio = "$ ".number_format($precio_desc,2);
	$fdesc = number_format($descuento,2)."%";
	
	$act = 'Act1 = '.$actual;
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+12,$m,$no_parte);
	$pdf->text($x+28,$m,$cantidad);
	$pdf->text($x+47,$m,$nom_insumo);
	$pdf->text($x+103,$m,$ancho);
	$pdf->text($x+120,$m,$largo);
	$pdf->text($x+132,$m,$funitario);
	$pdf->text($x+160,$m,$fdesc);
	$pdf->text($x+176,$m,$fprecio);	
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
		$m= $m+$salto;
	}

}

//volvemos a meterle valores a x para que se muestren los totales
//$y= $m+4;
//$y=220;
$y=200;

//dibujar un rectangulo para los totales
$pdf->SetDrawColor(63,63,63);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.3);
$pdf->Rect($x+10, $y-5, 185, 20,'DF');

//Subtotal
$pdf->SetFont('Arial','B',9);
$pdf->text($x+156,$y,'Subtotal: ');
$pdf->SetFont('Arial','',9);
$subtot_fac1 = "$ ".number_format($sub_total,2);
$pdf->text($x+175,$y,$subtot_fac1);

if($id_iva!=3){
	//IVA
	$iva = $graba_normal * ($tasa_iva/100);
	
	$iva1 = number_format($iva,2);
	$iva1 = '$ '.$iva1;
	$pdf->SetFont('Arial','B',9);
	$dat7 = "I.V.A. al ".$tasa_iva."%: ";
	$pdf->text($x+150,$y+5,$dat7);
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+175,$y+5,$iva1);
	
	//Total
	$total_orden = $sub_total + $iva;
	$total1 = number_format($total_orden,2);
	$total1 = '$ '.$total1;
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+161,$y+10,'Total: ');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+175,$y+10,$total1);

}else{
	//Total solamente por que no se desglosa IVA
	$iva = $graba_normal * ($tasa_iva/100);
	$total_orden = $sub_total + $iva;
	$total1 = number_format($total_orden,2);
	$total1 = '$ '.$total1;
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+161,$y+5,'Total: ');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+175,$y+5,$total1);
	
}

//observaciones a la orden
$pdf->SetFont('Arial','B',9);
$pdf->text($x+11,$y+20,'Observaciones:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+11,$y+21);
$pdf->SetFont('Arial','',9);
//dibujamos la celda
$pdf->MultiCell(183,3,$observaciones,0);

$pdf->Output();

?>