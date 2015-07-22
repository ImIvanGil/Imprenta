<?php
require('fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost","root","root");
mysql_select_db("imprenta", $conexion);

//obtener numero de factura
$clave = $_GET['clave'];
$letras = $_GET['letras'];

//variables que acumularan los totales
$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_factura = 0;

$queFac = "SELECT * FROM `factura` WHERE id_factura='".$clave."'";
$factura = mysql_query($queFac, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($factura)) {
			
			// datos generales de la factura
			
			$id_empresa = $row['id_empresa'];
			$oc_cliente = $row['oc_cliente'];
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
			  //voy a separar la fecha de la hora y a ponerle un :
			  $fechas = explode("T", $fecha);
			  $solo_fecha = $fechas[0];
			  $solo_hora = $fechas[1];
			  $fecha = $solo_fecha." ".$solo_hora;
			  
			  $id_forma = $row['id_forma_pago'];
			  //forma de pago
			  $sql_forma = "SELECT forma_pago FROM forma_pago WHERE id_forma_pago='".$id_forma."'";
			  $consulta_forma = mysql_query($sql_forma, $conexion) or die(mysql_error());
			  $row_forma = mysql_fetch_assoc($consulta_forma);
			  $forma = $row_forma['forma_pago'];
			  
			  $id_status = $row['id_status_factura'];
			  // estado de la factura
			  $sql_status = "SELECT status FROM status_factura WHERE id_status_factura='".$id_status."'";
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
											
			  $id_iva = $row['id_iva'];
			  //voy a buscar la tasa del iva que vamos a usar
			  $sql_tas = "SELECT tasa FROM iva WHERE id_iva='".$id_iva."'";
			  $consulta_tas = mysql_query($sql_tas, $conexion) or die(mysql_error());
			  $row_tas = mysql_fetch_assoc($consulta_tas);
			  $tasa_iva = $row_tas['tasa'];
			  
			  $uuid = $row['uuid'];
			  $no_serie_cert = $row['no_serie_cert'];
			  $fecha_cert = $row['fecha_certificacion'];
			  $sello_digital = $row['sello'];
			  $sello_sat = $row['sello_sat'];
			  $cadena_cert = $row['cadena_cert'];
			  
			   //voy a separar la fecha de la hora de cert y a ponerle un :
			  $fechas2 = explode("T", $fecha_cert);
			  $solo_fecha1 = $fechas2[0];
			  $solo_hora1 = $fechas2[1];
			  $fecha_cert = $solo_fecha1." ".$solo_hora1;
			  
			  //formare el nombre del archivo en donde se encuentra el sello bidimensional para futuras referencias.
			  $file = "$rfc_emp"."_"."$clave";
			
			
      }


class PDF extends FPDF
{
//Cabecera de página
function Header()
{
	//margenes de pagina
	$this->SetMargins(.5,2,.5);
    //Logo
    $this->Image('../images/logo.jpg',20,12,45);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha
    $this->Cell(80);
    //Título
    $this->Cell(110,3,'FACTURA',0,0,'R');
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
    //Número de página
    $this->Cell(0,10,'ESTE DOCUMENTO ES UNA REPRESENTACION IMPRESA DE UN CFDI',0,0,'C');
}
}

//Creación del objeto de la clase heredada
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
$pdf->text($x+64,$y,$nom_emp);
$pdf->SetFont('Arial','',9);
$pdf->text($x+93,$y+3,$rfc_emp);
$pdf->text($x+87,$y+6,$dir_emp);
$pdf->text($x+84,$y+9,$colonia_emp);
$dat = $ciudad_emp.", ".$estado_emp." C.P. ".$cp_emp;
$pdf->text($x+79,$y+12,$dat);

//folio de la factura control interno
$folio = str_pad($clave, 6, "0", STR_PAD_LEFT);
$pdf->SetFont('Arial','B',8);
$pdf->text($x+180,$y+1,'No. ');
$pdf->SetFont('Arial','',8);
$pdf->text($x+185,$y+1,$folio);

//datos de validez
$pdf->SetFont('Arial','B',8);
$pdf->text($x+178,$y+4,'Folio Fiscal');
$pdf->SetFont('Arial','',8);
$pdf->text($x+137,$y+7,$uuid);

$pdf->SetFont('Arial','B',8);
$pdf->text($x+146,$y+10,'No. de Serie del Certificado del SAT');
$pdf->SetFont('Arial','',8);
$pdf->text($x+162,$y+13,$no_serie_cert);

$pdf->SetFont('Arial','B',8);
$pdf->text($x+154,$y+16,'Fecha y Hora de Certificacion');
$pdf->SetFont('Arial','',8);
$pdf->text($x+167,$y+19,$fecha_cert);


//dibujar un rectangulo para el encabezado de cliente
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+15, $y+20, 100, 4,'DF');

$pdf->SetFont('Arial','B',8);
$pdf->text($x+18,$y+23,'DATOS DEL CLIENTE');

//otro rectangulo para los datos del cliente
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+15, $y+24, 100, 17,'DF');

//datos del cliente
$pdf->SetFont('Arial','',9);
$pdf->text($x+18,$y+27,$nom_cliente);
$pdf->text($x+18,$y+30,$rfc_cliente);
$pdf->text($x+18,$y+33,$dir_cliente);
$pdf->text($x+18,$y+36,$colonia_emp);
$dat4 = $ciudad_cliente.", ".$estado_cliente." C.P. ".$cp_cliente;
$pdf->text($x+18,$y+39,$dat4);

//si existe el dato muestro el numero de orden de compra
if($oc_cliente!=""){
	$pdf->SetFont('Arial','B',8);
	$pdf->text($x+18,$y+45,'No. de Orden de Compra:');
	$pdf->SetFont('Arial','',8);
	$pdf->text($x+53,$y+45,$oc_cliente);	
}

//otro rectangulo para los datos e expedicion
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+120, $y+24, 70, 17,'DF');

//datos de expedicion
$pdf->SetFont('Arial','B',9);
$dat2 = utf8_decode('Lugar de Expedición: ');
$pdf->text($x+122,$y+27,$dat2);
$pdf->SetFont('Arial','',9);
$pdf->text($x+122,$y+30,$dir_emp);
$pdf->text($x+122,$y+33,$colonia_emp);
$pdf->text($x+122,$y+36,$dat);
$pdf->SetFont('Arial','B',9);
$dat5 = utf8_decode('Fecha de Expedición: ');
$pdf->text($x+122,$y+39,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+156,$y+39,$fecha);



//dibujar un rectangulo para los encabezados
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);

$pdf->SetLineWidth(0.09);
$pdf->Rect($x+15, $y+50, 175, 4,'DF');
//encabezados de la tabla de detalle
$dat6 = utf8_decode('DESCRIPCIÓN');
$pdf->SetFont('Arial','B',9);
$pdf->text($x+20,$y+53,'CANT.');
$pdf->text($x+58,$y+53,$dat6);
$pdf->text($x+140,$y+53,'UNITARIO');
$pdf->text($x+170,$y+53,'IMPORTE');
$pdf->SetFont('Arial','',9);

//recuadros para enmarcar el detalle de la factura
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+15, $y+50, 20, 150,'D');
$pdf->Rect($x+35, $y+50,100, 150,'D');
$pdf->Rect($x+135, $y+50,28, 150,'D');
$pdf->Rect($x+163, $y+50,27, 150,'D');


//una variable para llevar la cuenta de las lineas  y otra para acumular los totales
$n = 0;

$sub_total = 0;
$graba_normal = 0;
$graba_cero = 0;
$exento = 0;
$iva = 0;
$total_factura = 0;

$actual = $pdf->GetY();
$salto = 4;
$m = $y+57;
//ahora vamos a hacer un ciclo para la consulta del detalle de la factura
$sql_det = "SELECT * FROM detalle_factura WHERE id_factura='".$clave."'";
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
	

	$funitario = "$ ".number_format($unitario,2);
	$fprecio = "$ ".number_format($precio,2);
	$act = 'Act1 = '.$actual;
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+22,$m,$cantidad);
	$pdf->text($x+48,$m,$nom_producto);
	$pdf->text($x+145,$m,$funitario);
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
$y=220;

//dibujar un rectangulo para los totales
$pdf->SetDrawColor(63,63,63);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.3);
$pdf->Rect($x+15, $y-5, 175, 18,'DF');

//Subtotal
$pdf->SetFont('Arial','B',9);
$pdf->text($x+151,$y,'Subtotal: ');
$pdf->SetFont('Arial','',9);
$subtot_fac1 = "$ ".number_format($sub_total,2);
$pdf->text($x+170,$y,$subtot_fac1);



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
$total_factura = $sub_total + $iva;
$total1 = number_format($total_factura,2);
$total1 = '$ '.$total1;
$pdf->SetFont('Arial','B',9);
$pdf->text($x+156,$y+10,'Total: ');
$pdf->SetFont('Arial','',9);
$pdf->text($x+170,$y+10,$total1);


//importe con letra
$pdf->SetFont('Arial','B',9);
$pdf->text($x+21,$y,'Importe con Letra:');
$pdf->SetFont('Arial','',9);
$pdf->text($x+20,$y+5,$letras);

//inserto la imagen del codigo QR
$imagen = './cfdi/QR/'.$file.'.png';
$pdf->Image($imagen,$x+13,$y+14,34,0);

//sello digital
$pdf->SetFont('Arial','B',9);
$pdf->text($x+50,$y+20,'Sello Digital del CFDI:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+48,$y+21);
$pdf->SetFont('Arial','',9);
//dibujamos la celda, el 140 es al ancho y el 3 es el alto de cada celdita, osea el alto del renglon, el 0 inica que no tiene borde
$pdf->MultiCell(140,3,$sello_digital,0);

//sello SAT
$pdf->SetFont('Arial','B',9);
$pdf->text($x+50,$y+34,'Sello del SAT:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+48,$y+35);
$pdf->SetFont('Arial','',9);
//dibujamos la celda, el 140 es al ancho y el 3 es el alto de cada celdita, osea el alto del renglon, el 0 inica que no tiene borde
$pdf->MultiCell(140,3,$sello_sat,0);


//cadena del certificado
$pdf->SetFont('Arial','B',9);
$pdf->text($x+18,$y+49,'Cadena original del complemento de certificacion digital del SAT:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+16,$y+50);
$pdf->SetFont('Arial','',9);
//dibujamos la celda, el 140 es al ancho y el 3 es el alto de cada celdita, osea el alto del renglon, el 0 inica que no tiene borde
$pdf->MultiCell(173,3,$cadena_cert,0);

$rutaPDF = './cfdi/pdf/'.$file.'.pdf';
$pdf->Output($rutaPDF);
$pdf->Output();

?>