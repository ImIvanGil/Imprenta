<?php
require('../fpdf/fpdf.php');

//conexion a la base de datos
$conexion = mysql_connect("localhost","root","root");
mysql_select_db("imprenta", $conexion);

//obtener numero de orden
$clave = $_GET['clave'];



$queOrd = "SELECT * FROM `orden_diseno` WHERE id_orden='".$clave."'";
$orden = mysql_query($queOrd, $conexion) or die(mysql_error());

 while ($row = mysql_fetch_assoc($orden)) {
			
			// datos generales de la orden de produccion
			
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
			
			$observaciones = $row['especificaciones'];
			$id_cliente = $row['id_cliente'];
			  //datos del cliente
			  $sql_cliente = "SELECT * FROM cliente where `id_cliente`='".$id_cliente."'";
			  $consulta_cliente = mysql_query($sql_cliente, $conexion) or die(mysql_error());
			  while($row_cliente = mysql_fetch_assoc($consulta_cliente)){
				  $nom_cliente=$row_cliente['nombre'];
				  $rfc_cliente=$row_cliente['rfc'];
				  $calle_cliente=$row_cliente['calle'];
				  $numero_cliente=$row_cliente['numero'];
				  $colonia_cliente=$row_cliente['colonia'];
				  $ciudad_cliente=utf8_decode($row_cliente['ciudad']);
				  $estado_cliente=$row_cliente['estado'];
				  
				 
				  $dir_cliente= $calle_cliente." No.".$numero_cliente;
				  
			  
				  $cp_cliente=$row_cliente['codigo_postal'];
				  $tel_cliente=$row_cliente['telefono'];
				  $correo=$row_cliente['correo'];
				  $cve_pais_cliente=$row_emp['pais'];
					//buscare el nombre del pais
					$sql_pais = "SELECT * FROM pais where `Code`='".$cve_pais_cliente."'";
					$consulta_pais = mysql_query($sql_pais, $conexion) or die(mysql_error());
					while($row_pa = mysql_fetch_array($consulta_pais)){
						$pais_cliente=$row_pa['Name'];
					}
				  
			  }
			  
			  $fecha = $row['fecha'];
			  $fecha_entrega = $row['fecha_entrega'];
			  
			  $id_status = $row['id_estado'];
			  // estado de la orden
			  $sql_status = "SELECT status FROM status_orden_diseno WHERE id_status='".$id_status."'";
			  $consulta_status = mysql_query($sql_status, $conexion) or die(mysql_error());
			  $row_status = mysql_fetch_assoc($consulta_status);
			  $status = $row_status['status'];
			  
			  //obtengo el dato de quien ordena y quien autoriza
			  
			  $id_vendedor = $row['ordena'];
			  //voy a buscar nombre del usuario
			  $sql_ordena = "SELECT * FROM myuser WHERE ID='".$id_vendedor."'";
			  $consulta_vendedor = mysql_query($sql_ordena, $conexion) or die(mysql_error());
			  $row_ordena = mysql_fetch_assoc($consulta_vendedor);
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
	$titulo = utf8_decode('ORDEN DE DISEÑO');
	//margenes de pagina
	$this->SetMargins(.5,2,.5);
    //Logo
    $this->Image('../images/logo.jpg',15,12,50);
    //Arial bold 15
    $this->SetFont('Arial','B',12);
    //Movernos a la derecha
    $this->Cell(80);
    //TÃ­tulo
    $this->Cell(110,3,$titulo,0,0,'R');
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
$pdf->text($x+167,$y+10,'Folio: ');

$pdf->SetTextColor(236,25,21);
$pdf->text($x+180,$y+10,$folio);
$pdf->SetTextColor(0,0,0);

$pdf->text($x+165,$y+6,$status);
$pdf->SetTextColor(0,0,0);

if($urgente=="si"){
	$pdf->SetFont('Arial','B',14);
	$pdf->SetTextColor(236,25,21);
	$pdf->text($x+170,$y+19,'URGENTE');
	$pdf->SetTextColor(0,0,0);
}


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
$pdf->Rect($x+10, $y+26, 105, 22,'DF');

//datos del cliente
$pdf->SetFont('Arial','',9);
$pdf->text($x+12,$y+29,$nom_cliente);
$pdf->text($x+12,$y+32,$dir_cliente);
$pdf->text($x+12,$y+35,$colonia_cliente);
$dat4 = $ciudad_cliente.", ".$estado_cliente." C.P. ".$cp_cliente;
$pdf->text($x+12,$y+38,$tel_cliente);
$pdf->text($x+12,$y+41,$correo);
$pdf->text($x+12,$y+44,$dat4);


//otro rectangulo para los datos de expedicion
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(255,255,255);
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+120, $y+22, 75, 26,'DF');

//datos de expedicion
$pdf->SetFont('Arial','B',9);
$dat5 = 'Fecha de Creacion: ';
$pdf->text($x+122,$y+25,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+152,$y+25,$fecha);

$pdf->SetFont('Arial','B',9);
$dat5 = 'Promesa Entrega: ';
$pdf->text($x+122,$y+33,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+150,$y+33,$fecha_entrega);

$pdf->SetFont('Arial','B',9);
$dat5 = 'Vendedor: ';
$pdf->text($x+122,$y+37,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+138,$y+37,$ordena);

$pdf->SetFont('Arial','B',9);
$dat5 = 'Ultimo movimiento: ';
$pdf->text($x+122,$y+41,$dat5);
$pdf->SetFont('Arial','',9);
$pdf->text($x+152,$y+41,$autoriza);


//dibujar un rectangulo para los encabezados
$pdf->SetDrawColor(0,0,0);
$pdf->SetFillColor(223,223,223);

$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 185, 4,'DF');
//encabezados de la tabla de detalle
$pdf->SetFont('Arial','B',9);
$pdf->text($x+15,$y+53,'CLAVE');
$pdf->text($x+88,$y+53,'PRODUCTO');
$pdf->SetFont('Arial','',9);

//recuadros para enmarcar el detalle de la orden
$pdf->SetLineWidth(0.09);
$pdf->Rect($x+10, $y+50, 21, 60,'D');
$pdf->Rect($x+31, $y+50, 164, 60,'D');



$actual = $pdf->GetY();
$salto = 4;
$m = $y+59;
//ahora vamos a hacer un ciclo para la consulta del detalle de la orden
$sql_det = "SELECT `id_producto` FROM `orden_diseno` WHERE id_orden='".$clave."'";
$consulta_det = mysql_query($sql_det, $conexion) or die(mysql_error());
while ($row = mysql_fetch_assoc($consulta_det)){

	
	$id_producto = $row['id_producto'];
	
	//consultare el nombre del producto
	$sql_prod = "SELECT *  FROM `producto` WHERE `id_producto` = '".$id_producto."';";
	$consulta_prod = mysql_query($sql_prod, $conexion) or die(mysql_error());
	 while ($row2 = mysql_fetch_assoc($consulta_prod)){
		 $nom_producto = $row2['nombre'];
		 $cve_prod = $row2['clave'];
		 $block = $row2['cant'];
		 $desc_producto = utf8_decode($row2['descripcion']);
		 $id_unidad = $row2['id_unidad'];
		 $id_linea = $row2['id_linea'];
		 
		 $ancho = $row2['ancho'];
		 $largo = $row2['largo'];
		 $dado = $row2['dado'];
		 $core = $row2['core'];
		 
		 $dientes = $row2['dientes'];
		 $repeticiones = $row2['repeticiones'];
		 
		 $prec = $row2['precorte'];
		 if($prec==0){
			$precorte = "No";
		}else{
			$precorte= "Si";
		}
		 
		 
		  $id_tintas = $row2['id_tintas'];
		   if($id_tintas=='-1'){
			   $tinta ="Sin Información";
			  }
		   //buscar las tintas
		  $txt_tin = "SELECT *  FROM `tinta` WHERE `id_tinta` = '".$id_tintas."';";
		  $consulta_tin = mysql_query($txt_tin, $conexion) or die(mysql_error());
		  while ($row4 = mysql_fetch_assoc($consulta_tin)){$tinta = $row4['tinta'];}
									 
		 
		 //aqui dentro consulto el nombre de la unidad
		 	$sql_uni = "SELECT *  FROM `unidades` WHERE `id_unidad` = '".$id_unidad."';";
			$cons_uni = mysql_query($sql_uni, $conexion) or die(mysql_error());
			 while ($row15 = mysql_fetch_assoc($cons_uni)){
				 $unidad = $row15['unidad'];
			}
			
		//aqui dentro consulto el nombre de la linea
		 	$sql_lin = "SELECT *  FROM `linea_producto` WHERE `id_linea` = '".$id_linea."';";
			$cons_lin = mysql_query($sql_lin, $conexion) or die(mysql_error());
			 while ($row16 = mysql_fetch_assoc($cons_lin)){
				 $linea = $row16['linea'];
			}
			
		//Consulto los datos segun la linea
		if($id_linea==1){
		//si la linea es flexo
		
		//Tipo de papel
		$id_papel = $row2['id_tipo_papel'];
		if($id_papel=="0"){$papel = "Sin Información";}
		$query_papel = "SELECT *  FROM `tipo_papel` WHERE `id_tipo` = '".$id_papel."';";
		$consulta_pap = mysql_query($query_papel);
		while ($row3 = mysql_fetch_assoc($consulta_pap)){$papel = $row3['tipo'];}
		
		//laminado
		$id_laminado = $row2['laminado'];
		switch ($id_laminado){
			case 0:
				$laminado = "Sin Información";
			break;
			case -1:
				$laminado = "No";
			break;
			case 1:
				$laminado = "Si";
			break;
		}
		
		//barnizado
		$id_barnizado = $row2['barnizado'];
		switch ($id_barnizado){
			case 0:
				$barnizado = "Sin Información";
			break;
			case -1:
				$barnizado = "Ninguno";
			break;
			case 1:
				$barnizado = "UV";
			break;
			case 2:
				$barnizado = "Antiestático";
			break;
			case 3:
				$barnizado = "Base Agua";
			break;
		}
		
		//etiquetadora
		$id_etiquetadora = $row2['etiquetadora'];
		switch ($id_etiquetadora){
			case -1:
				$etiquetadora = "Sin Información";
			break;
			case 1:
				$etiquetadora = "Si";
			break;
			case 2:
				$etiquetadora = "No";
			break;
		}
		
		//rebobinado
		$id_rebobinado = $row2['rebobinado'];
		switch ($id_rebobinado){
			case -1:
				$rebobinado = "Sin Informacion";
			break;
			case 1:
				$rebobinado = "Izquierdo";
			break;
			case 2:
				$rebobinado = "Derecho";
			break;
		}
		
	}else{
		//si la linea es offset
		//consulto la prensa
		 $id_prensa = $row2['prensa'];
		 switch ($id_prensa){
			case 0:
				$prensa = "Sin Información de tipo de prensa";
			break;
			case -1:
				$prensa ="No hay información";
			break;
			case 1:
				$prensa ="Abdick";
			break;
			case 2:
				$prensa ="Forma Continua";
			break;
			case 3:
				$prensa ="Full Color";
			break;
		}
		
		$color_tinta = $row2['color'];
		
		 $ext_ancho = $row2['ext_ancho'];
		 $ext_largo = $row2['ext_largo'];
		 
		 $grap = $row2['grapado'];
		 if($grap==1){
			 $grapado = "Si";
		 }else{$grapado = "No";}
		 
		 $peg = $row2['pegado'];
		 if($peg==1){
			 $pegado = "Quimico";
		 }else{
			 if($peg==2){
				 $pegado = "Bond";
			}else{$pegado = "Sin informacion";}
		}
		 
		 $marginal = $row2['marginal'];
		 $prefijo = $row2['prefijo'];
		 $sufijo = $row2['sufijo'];
		
		$perf = $row2['perforacion'];
		 if($perf==1){
			 $perforacion = "Si";
		 }else{$perforacion = "No";}
		 
		 $eng = $row2['engargolado'];
		 if($eng==1){
			 $engargolado = "Si";
		 }else{$engargolado = "No";}
		 
		 $enc = $row2['encuadernado'];
		 if($enc==1){
			 $encuadernado = "Si";
		 }else{$encuadernado = "No";}
				
		$imp = $row2['impresion'];
		 if($imp==1){
			 $impresion = "Frente";
		 }else{
			 if($imp==2){
				 $impresion = "Reverso";
			}else{
				if($imp==3){
					$impresion = "Ambos lados";
				}else{
					$impresion = "Sin informacion";
				}}
		}
		
							
	}
			
			
			
			
			
	}
	
	$act = 'Act1 = '.$actual;
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+12,$m,$cve_prod);
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+37,$m,$nom_producto);
	$pdf->SetFont('Arial','',9);
	//************muestro los datos segun la linea
	$n = $pdf->GetY();
	if($id_linea=="1"){
		//si la linea el flexo
		$pdf->SetFont('Arial','B',9);
		$pdf->text($x+37,$n+48,'Papel: ');
		$pdf->SetFont('Arial','',9);
		$pdf->text($x+47,$n+48,utf8_decode($papel));
		$pdf->SetFont('Arial','B',9);
		$pdf->text($x+37,$n+52,'Laminado: ');
		$pdf->SetFont('Arial','',9);
		$pdf->text($x+54,$n+52,utf8_decode($laminado));
		$pdf->SetFont('Arial','B',9);
		$pdf->text($x+37,$n+56,'Barnizado: ');
		$pdf->SetFont('Arial','',9);
		$pdf->text($x+54,$n+56,utf8_decode($barnizado));
		$pdf->SetFont('Arial','B',9);
		$pdf->text($x+37,$n+60,'Etiquetadora: ');
		$pdf->SetFont('Arial','',9);
		$pdf->text($x+58,$n+60,utf8_decode($etiquetadora));
		$pdf->SetFont('Arial','B',9);
		$pdf->text($x+37,$n+64,'Rebobinado: ');
		$pdf->SetFont('Arial','',9);
		$pdf->text($x+58,$n+64,utf8_decode($rebobinado));
	}else{
		if($id_linea=="2"){
			//si la linea es offset
			$pdf->SetFont('Arial','B',9);
			$pdf->text($x+37,$n+48,'Prensa: ');
			$pdf->SetFont('Arial','',9);
			$pdf->text($x+49,$n+48,utf8_decode($prensa));
			
			//si la linea es offset
			$pdf->SetFont('Arial','B',9);
			$pdf->text($x+37,$n+52,'Pantone: ');
			$pdf->SetFont('Arial','',9);
			$pdf->text($x+52,$n+52,utf8_decode($color_tinta));
			
			
			//ahora las copias
			$cons = "SELECT * FROM `copias_producto` WHERE id_producto='$id_producto'";
			$copias = mysql_query($cons);
			$existe_copias = mysql_num_rows($copias);
			if($existe_copias<=0){
				$pdf->SetFont('Arial','B',9);
				$pdf->text($x+37,$n+56,'Detalle de Copias: ');
				$pdf->SetFont('Arial','I',9);
				$pdf->text($x+37,$n+60,'No hay registros de copias para este producto');
			}else{
				$pdf->SetFont('Arial','B',9);
				$pdf->text($x+37,$n+56,'Detalle de Copias: ');
				$i=1;
				$w=$n+60;
				while ($row21 = mysql_fetch_assoc($copias))
				{
					$id_registro = $row21['id_registro'];
					$id_papel = $row21['id_papel'];
					//consulto el nombre del papel
					$cons_p = "SELECT * FROM `tipo_papel` WHERE `id_tipo` =$id_papel;";
					$lista_p = mysql_query($cons_p);
					while ($row9 = mysql_fetch_assoc($lista_p)){$nombre_papel = $row9['tipo'];}
					$color = $row21['color'];
					$txt = "-".$i." ".$nombre_papel." ".$color;
					$pdf->SetFont('Arial','I',9);
					$pdf->text($x+37,$w,$txt);
					$i++;
					$w = $w+4;
				}
			
			}
			
		}
	}
	
//ENCABEZADO SEGUN LA LINEA
if($id_linea=="1"){
	$pdf->SetFont('Arial','B',12);
	$pdf->text($x+164,$y+2,'FLEXOGRAFIA');
}else{
	$pdf->SetFont('Arial','B',12);
	$pdf->text($x+177,$y+2,'OFFSET');
}



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
//Especificaciones adicionales
$pdf->SetFont('Arial','B',9);
$pdf->text($x+11,$m+54,'Especificaciones Adicionales');

//datos adicionales
$pdf->SetFont('Arial','B',9);
$lin = utf8_decode('Línea: ');
$tam = utf8_decode('Ancho: ');
$pdf->text($x+11,$m+58,'Unidad de medida:');
$pdf->text($x+11,$m+62,$lin);
$pdf->text($x+11,$m+66,'Cantidad: ');
$pdf->text($x+11,$m+70,$tam);
$pdf->text($x+35,$m+70,'Largo: ');
$pdf->text($x+60,$m+70,'No. Dado: ');
$pdf->text($x+90,$m+70,'Core: ');
$pdf->text($x+120,$m+70,'Precorte: ');

$pdf->text($x+11,$m+74,'Dientes del plate roll: ');
$pdf->text($x+11,$m+78,'No. de repeticiones: ');



$pdf->text($x+100,$m+58,'Tintas:');
$pdf->text($x+100,$m+62,'Cantidad por unidad:');
$pdf->text($x+100,$m+66,'Stock:');

$pdf->SetFont('Arial','',9);
$pdf->text($x+41,$m+58,$unidad);
$pdf->text($x+21,$m+62,$linea);
$pdf->text($x+26,$m+66,$cantidad);
$pdf->text($x+25,$m+70,$ancho);
$pdf->text($x+46,$m+70,$largo);
$pdf->text($x+76,$m+70,$dado);
$pdf->text($x+99,$m+70,$core);
$pdf->text($x+135,$m+70,$precorte);

$pdf->text($x+44,$m+74,$dientes);
$pdf->text($x+42,$m+78,$repeticiones);

$pdf->text($x+110,$m+66,$stock);

$pdf->text($x+111,$m+58,utf8_decode($tinta));
$pdf->text($x+132,$m+62,$block);

if($id_linea=="2"){
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+11,$m+74,'Folio incio:');
	$pdf->text($x+54,$m+74,'Folio final:');
	
	$pdf->text($x+11,$m+78,'Extendido ancho:');
	$pdf->text($x+54,$m+78,'Extendido largo:');
	$pdf->text($x+98,$m+78,'Grapado:');
	$pdf->text($x+134,$m+78,'Pegado:');
	
	$pdf->text($x+11,$m+82,'Perforacion:');
	$pdf->text($x+54,$m+82,'Engargolado:');
	$pdf->text($x+98,$m+82,'Encuadernado:');
	$pdf->text($x+134,$m+82,'Impresion:');
	
	$pdf->text($x+11,$m+86,'Marginal:');
	$pdf->text($x+54,$m+86,'Prefijo:');
	$pdf->text($x+98,$m+86,'Sufijo:');
	
	
	$pdf->SetFont('Arial','',9);
	$pdf->text($x+29,$m+74,$folio_inicio);
	$pdf->text($x+71,$m+74,$folio_final);
	
	$pdf->text($x+39,$m+78,$ext_ancho);
	$pdf->text($x+80,$m+78,$ext_largo);
	$pdf->text($x+113,$m+78,$grapado);
	$pdf->text($x+150,$m+78,$pegado);
	
	
	$pdf->text($x+30,$m+82,$perforacion);
	$pdf->text($x+76,$m+82,$engargolado);
	$pdf->text($x+122,$m+82,$encuadernado);
	$pdf->text($x+151,$m+82,$impresion);
	
	$pdf->text($x+39,$m+86,$marginal);
	$pdf->text($x+80,$m+86,$prefijo);
	$pdf->text($x+113,$m+86,$sufijo);
}



//ahora obtengo las salidas registradas de almacen
$cons = "SELECT * FROM `salida_produccion` WHERE id_orden='$clave'";
$salidas = mysql_query($cons);
$existe_salidas = mysql_num_rows($salidas);
if($existe_salidas>0){
	$pdf->SetFont('Arial','B',9);
	$pdf->text($x+11,$m+105,'Detalle de Salidas de Almacen:');
	$pdf->SetFont('Arial','',9);
	
	//****AQUI DIBUJAR UNA TABLITA DE ENCABEZADOS PARA LOS DATOS DE SALIDAS
	//dibujar un rectangulo para el encabezado de cliente
	$pdf->SetDrawColor(0,0,0);
	$pdf->SetFillColor(223,223,223);
	$pdf->SetLineWidth(0.09);
	$pdf->Rect($x+10, $m+106, 105, 4,'DF');
	
	$pdf->SetFont('Arial','I',9);
	$pdf->text($x+12,$m+109,'No.');
	$pdf->text($x+46,$m+109,'Papel');
	$pdf->text($x+86,$m+109,'Cant.+ Desperdicio');
	$pdf->SetFont('Arial','',9);
	
	$i = 1;
	$s = 114;
	$t =110;
	  while ($row = mysql_fetch_assoc($salidas))
	  {
		  $id_salida = $row['id_salida'];
		  $id_papel = $row['id_papel'];
		  $cant_salida = $row['cantidad'];
		  //consulto el nombre del papel
		  $cons_p = "SELECT * FROM `tipo_papel` WHERE `id_tipo` =$id_papel;";
		  $lista_p = mysql_query($cons_p);
		  while ($row9 = mysql_fetch_assoc($lista_p)){$nom_salida = $row9['tipo'];}
		  
		  //dibujo mi cuadrito
		  	$pdf->SetDrawColor(0,0,0);
			$pdf->SetFillColor(255,255,255);
			$pdf->SetLineWidth(0.09);
			$pdf->Rect($x+10, $m+$t, 105, 5,'DF');
		  
		  	$pdf->text($x+13,$m+$s,$i);
			$pdf->text($x+25,$m+$s,$nom_salida);
			$pdf->text($x+98,$m+$s,$cant_salida);
		  
		  
		  $i++;
		  $s = $s+5;
		  $t = $t+5;
	  }
}


//observaciones a la orden
$pdf->SetFont('Arial','B',9);
$pdf->text($x+11,$m+90,'Observaciones:');
//indicamos a partir de donde va a dibujar las celdas
$pdf->SetXY($x+11,$m+91);
$pdf->SetFont('Arial','',9);
//dibujamos la celda
$pdf->MultiCell(183,3,$observaciones,0);

$pdf->Output();

?>