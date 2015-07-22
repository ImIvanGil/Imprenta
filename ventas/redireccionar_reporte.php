<?php
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect();
if ($prot->showPage) {
$curUser=$prot->getUser();

	
	include("../lib/mysql.php"); 
	$db = new MySQL();	
	$tipo = $_GET['tipo'];
	$inicio = $_GET['finicio'];
	$fin = $_GET['ffin'];													
	
	
	if ($tipo == 'general'){
		//reporte general de ventas
		$link = "Location: rep_gen_vtas.php?inicio=$inicio&fin=$fin";
		header($link);
	}else{
		if($tipo=='cliente'){
			$id_cliente = $_GET['cliente'];
			//reporte de ventas por cliente
			$link = "Location: rep_vtas_cte.php?inicio=$inicio&fin=$fin&id_cte=$id_cliente";
			header($link);
		}else{
			if($tipo=='vendedor'){
				$id_vendedor = $_GET['vendedor'];
				//reporte de ventas por cliente
				$link = "Location: rep_vtas_vend.php?inicio=$inicio&fin=$fin&id_vend=$id_vendedor";
				//echo "id_vendedor es $id_vendedor";
				header($link);
			}else{
				if($tipo=='producto'){
				$id_producto = $_GET['producto'];
				//reporte de ventas por cliente
				$link = "Location: rep_vtas_prod.php?inicio=$inicio&fin=$fin&id_prod=$id_producto";
				//echo "id_vendedor es $id_vendedor";
				header($link);
			}else{
					if($tipo=='ext'){
						//reporte de ventas en dolares
						$link = "Location: rep_vtas_ext.php?inicio=$inicio&fin=$fin";
						header($link);
					}else{
						if($tipo=='comisiones'){
							//reporte de comisiones
							$link = "Location: rep_comisiones.php?inicio=$inicio&fin=$fin";
							header($link);
						}else{
							if($tipo == 'cobranza'){
								//reporte de cobranza
								$link = "Location: rep_cobranza.php?inicio=$inicio&fin=$fin";
								header($link);
							}else{
								if($tipo == 'anti'){
									//reporte de antigüedad de saldos
									$link = "Location: rep_saldos.php?inicio=$inicio&fin=$fin";
									header($link);
					
								}else{
									if($tipo == 'canceladas'){
										//reporte de facturas canceladas
										$link = "Location: rep_fac_canceladas.php?inicio=$inicio&fin=$fin";
										header($link);
									}else{
										if($tipo == 'linea'){
											//reporte de ventas por linea
											$link = "Location: rep_ventas_linea.php?inicio=$inicio&fin=$fin";
											header($link);
										}
									}
									
								}									
							}
						}	
					}
				}
			
			
			}
		}
		
	}
	

}

ob_end_flush();
?> 
