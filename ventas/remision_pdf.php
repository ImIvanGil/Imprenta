<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost","root","root");
mysql_select_db("imprenta", $conexion);

//obtener numero de remision
$clave = $_GET['clave'];
$letras = $_GET['letras'];

//variables que acumularan los totales
$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_remision = 0;

$queFac = "SELECT * FROM `remision` WHERE id_remision='".$clave."'";
$remision = mysql_query($queFac, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($remision)) {
			
			// datos generales de la remision
			
			$id_empresa = 1;
			$oc_cliente = $row['oc_cliente'];
			$num_cuenta = $row['num_cuenta'];
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
			
			$id_cliente = $row['id_cliente'];
			  //datos del cliente
			  $sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
			  $consulta_cliente = mysql_query($sql_cliente, $conexion) or die(mysql_error());
			  while($row_cliente = mysql_fetch_assoc($consulta_cliente)){
				  $nom_cliente=$row_cliente['nombre'];
				  $rfc_cliente=$row_cliente['rfc'];
				  $calle_cliente=$row_cliente['calle'];
				  $numero_cliente=$row_cliente['numero'];
				  $numInt_cliente=$row_cliente['no_interior'];
				  $colonia_cliente=$row_cliente['colonia'];
				  $ciudad_cliente=$row_cliente['ciudad'];
				  $estado_cliente=$row_cliente['estado'];
				  
				  if($numInt_cliente!=""){
					  $dir_cliente= $calle_cliente." No.".$numero_cliente."Int.".$numInt_cliente;
				  }else{
					  $dir_cliente= $calle_cliente." No.".$numero_cliente;
				  }
			  
				  $cp_cliente=$row_cliente['codigo_postal'];
				  $tel_cliente=$row_cliente['telefono'];
				  $cve_pais_cliente=$row_emp['pais'];
					//buscare el nombre del pais
					$sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_cliente."'";
					$consulta_pais = mysql_query($sql_pais, $conexion) or die(mysql_error());
					while($row_pa = mysql_fetch_array($consulta_pais)){
						$pais_cliente=$row_pa['Name'];
					}
				  
			  }
			  
			  $fecha = $row['fecha'];
			  /*voy a separar la fecha de la hora y a ponerle un :
			  $fechas = explode("T", $fecha);
			  $solo_fecha = $fechas[0];
			  $solo_hora = $fechas[1];
			  $fecha = $solo_fecha." ".$solo_hora;*/
			  
			  $id_forma = $row['id_forma_pago'];
			  //forma de pago
			  $sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
			  $consulta_forma = mysql_query($sql_forma, $conexion) or die(mysql_error());
			  $row_forma = mysql_fetch_assoc($consulta_forma);
			  $forma = $row_forma['forma_pago'];
			  
			  $id_status = $row['id_status_remision'];
			  // estado de la remision
			  $sql_status = "SELECT status FROM status_remision WHERE id_status_remision='".$id_status."'";
			  $consulta_status = mysql_query($sql_status, $conexion) or die(mysql_error());
			  $row_status = mysql_fetch_assoc($consulta_status);
			  $status = $row_status['status'];
			  
			  $id_met = $row['id_metodo_pago'];
			  //metodo de pago
			  $sql_met = "SELECT metodo_pago FROM metodo_pago WHERE id_metodo_pago='".$id_met."'";
			  $consulta_met = mysql_query($sql_met, $conexion) or die(mysql_error());
			  $row_met = mysql_fetch_assoc($consulta_met);
			  $metodo = $row_met['metodo_pago'];
			  
			  $id_mon = $row['id_moneda'];
			  //moneda
			  $sql_mon = "SELECT moneda FROM moneda WHERE id_moneda='".$id_mon."'";
			  $consulta_mon = mysql_query($sql_mon, $conexion) or die(mysql_error());
			  $row_mon = mysql_fetch_assoc($consulta_mon);
			  $moneda = $row_mon['moneda'];
			  
			  $t_cambio = $row['tipo_cambio'];
			  $plazo = $row['plazo_pago'];
			  $observaciones = $row['observaciones'];
											
			  $id_iva = $row['id_iva'];
			  //voy a buscar la tasa del iva que vamos a usar
			  $sql_tas = "SELECT * FROM iva WHERE id_iva='".$id_iva."'";
			  $consulta_tas = mysql_query($sql_tas, $conexion) or die(mysql_error());
			  $row_tas = mysql_fetch_assoc($consulta_tas);
			  $tasa_iva = $row_tas['tasa'];
			  $tipo_iva = $row_tas['tipo'];
			  
			 
			
      }


class PDF extends FPDF
{
//Cabecera de página
function Header()
{
	//margenes de pagina
	$this->SetMargins(.5,2,.5);
    //Logo
    $this->Image('../images/logo.jpg',15,12,50);
    //Arial bold 15
    $this->SetFont('Arial','B',14);
    //Movernos a la derecha
    $this->Cell(80);
    //Título
    $this->Cell(110,3,'NOTA DE REMISION',0,0,'R');
    //Salto de línea
    $this->Ln(20);
}

//Pie de página
function Footer()
{
    //Posición: a 1,5 cm del final
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','BI',8);
	$this->SetTextColor(236,25,21);

	
}
}

//Creacionn del objeto de la clase heredada
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
$pdf->text($x+163,$y+15,'Fecha:');
$pdf->text($x+175,$y+15,$fecha);



$pdf->text($x+65,$y,$nom_emp);
$pdf->SetFont('Arial','',9);
$pdf->text($x+93,$y+3,$rfc_emp);
$pdf->text($x+87,$y+6,$dir_emp);
$pdf->text($x+84,$y+9,$colonia_emp);
$dat = $ciudad_emp.", ".$estado_emp." C.P. ".$cp_emp;
$pdf->text($x+79,$y+12,$dat);

//folio de la remision control interno
$folio = str_pad($clave, 6, "0", STR_PAD_LEFT);
$pdf->SetFont('Arial','B',12);
$pdf->text($x+168,$y+3,'Folio: ');

$pdf->SetTextColor(236,25,21);
$pdf->text($x+180,$y+3,$folio);
$pdf->SetTextColor(0,0,0);
$pdf->text($x+180,$y+8,$status);



//dibujar un rectangulo para el encabezado de cliente
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+22, 105, 4,'DF');

$pdf->SetFont('Arial','B',9);
$pdf->text($x+12,$y+25,'DATOS DEL CLIENTE');

//otro rectangulo para los datos del cliente
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+26, 105, 17,'DF');

//datos del cliente
$pdf->SetFont('Arial','',9);
$pdf->text($x+12,$y+29,$nom_cliente);
$pdf->text($x+12,$y+32,$rfc_cliente);
$pdf->text($x+12,$y+35,$dir_cliente);
$pdf->text($x+12,$y+38,$colonia_cliente);
//$ciudad_cliente = utf8_decode($ciudad_cliente);
$dat4 = $ciudad_cliente.", ".$estado_cliente." C.P. ".$cp_cliente;
$pdf->text($x+12,$y+41,$dat4);

//si existe el dato muestro el numero de cuenta
if($num_cuenta!=""){
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+12,$y+44,'NO. DE CUENTA PAGO:');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+49,$y+44,$num_cuenta);	
}


//si existe el dato muestro el numero de orden de compra
if($oc_cliente!=""){
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+12,$y+47,'No.DE ORDEN DE COMPRA:');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+56,$y+47,$oc_cliente);	
}



//dibujar un rectangulo para los encabezados
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);

$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 185, 4,'DF');
//encabezados de la tabla de detalle
$dat6 = utf8_decode('DESCRIPCI�N');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+14,$y+53,'CANT.');
$pdf->text($x+31,$y+53,'UNIDAD');
$pdf->text($x+88,$y+53,$dat6);
$pdf->text($x+150,$y+53,'UNITARIO');
$pdf->text($x+175,$y+53,'IMPORTE');
$pdf->SetFont('Arial','',9);

//recuadros para enmarcar el detalle de la remision
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 18, 130,'D');
$pdf->Rect($x+28, $y+50, 18, 130,'D');
$pdf->Rect($x+46, $y+50,100, 130,'D');
$pdf->Rect($x+146, $y+50,23, 130,'D');
$pdf->Rect($x+169, $y+50,26, 130,'D');


//una variable para llevar la cuenta de las lineas  y otra para acumular los totales
$n = 0;

$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_remision = 0;

$actual = $pdf->GetY();
$salto = 4;
$m = $y+57;
//ahora vamos a hacer un ciclo para la consulta del detalle de la remision
$sql_det = "SELECT * FROM detalle_remision WHERE id_remision='".$clave."'";
$consulta_det = mysql_query($sql_det, $conexion) or die(mysql_error());
while ($row = mysql_fetch_assoc($consulta_det)){

	$n= $n+1;
	
	$id_producto = $row['id_producto'];
	$cantidad = $row['cantidad'];
	
	$unitario = $row['unitario'];
	$clase_iva = $row['id_clase_iva'];
	
	
	$precio = $unitario * $cantidad;
	$sub_total = $sub_total + $precio;
	
	//consultare el nombre del producto
	$sql_prod = "SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';";
	$consulta_prod = mysql_query($sql_prod, $conexion) or die(mysql_error());
	 while ($row2 = mysql_fetch_assoc($consulta_prod)){
		 $nom_producto = $row2['nombre'];
		 $desc_producto = utf8_decode($row2['descripcion']);
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
			  $graba_normal = $graba_normal+$precio;
		  break;
		  case 2:
			  $graba_cero = $graba_cero+$precio;
		  break;
		  case 3:
			  $exento = $exento + $precio;
		  break;
	  }
	

	$funitario = "$ ".number_format($unitario,4);
	$fprecio = "$ ".number_format($precio,2);
	$act = 'Act1 = '.$actual;
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+12,$m,$cantidad);
	$pdf->text($x+32,$m,$unidad);
	$pdf->text($x+47,$m,$nom_producto);
	$pdf->text($x+148,$m,$funitario);
	$pdf->text($x+170,$m,$fprecio);	
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
$pdf->text($x+151,$y,'Subtotal: ');
$pdf->SetFont('Arial','',9);
$subtot_fac1 = "$ ".number_format($sub_total,2);
$pdf->text($x+170,$y,$subtot_fac1);

if($id_iva!=3){
	//IVA
	$iva = $graba_normal * ($tasa_iva/100);
	
	$iva1 = number_format($iva,2);
	$iva1 = '$ '.$iva1;
	$pdf->SetFont('Arial','B',9);
	$dat7 = "I.V.A. al ".$tasa_iva."%: ";
	$pdf->text($x+145,$y+5,$dat7);
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+170,$y+5,$iva1);
	
	//Total
	$total_remision = $sub_total + $iva;
	$total1 = number_format($total_remision,2);
	$total1 = '$ '.$total1;
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+156,$y+10,'Total: ');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+170,$y+10,$total1);

}else{
	//Total solamente por que no se desglosa IVA
	$iva = $graba_normal * ($tasa_iva/100);
	$total_remision = $sub_total + $iva;
	$total1 = number_format($total_remision,2);
	$total1 = '$ '.$total1;
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+156,$y+5,'Total: ');
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+170,$y+5,$total1);
	
}



//importe con letra
$pdf->SetFont('Arial','B',9);
$pdf->text($x+14,$y,'Importe con Letra:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+14,$y+4,$letras);

//observaciones a la remision
$pdf->SetFont('Arial','B',9);
$pdf->text($x+11,$y+20,'Observaciones:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+11,$y+21);
$pdf->SetFont('Arial','',9);
//dibujamos la celda
$pdf->MultiCell(183,3,$observaciones,0);

$pdf->Output();

?>