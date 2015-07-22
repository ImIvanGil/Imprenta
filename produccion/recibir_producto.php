<?php 
ob_start();
include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
include("../lib/numero_letras.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../styles/ui-lightness/jquery.ui.all.css">

<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>

<script src="../js/jquery-1.8.0.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.autocomplete.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>




<SCRIPT LANGUAGE="JavaScript">
//validar el formulario
function validar() {
	if (document.registro.fecha.value == "") {
		alert ('Debe escribr la fecha en que se efectuo la operacion');
		document.getElementById('fecha').focus();
		return false;
	}
	if (document.registro.tipo.value == "-1") {
		alert ('Debe seleccionar el tipo de operacion');
		document.getElementById('tipo').focus();
		return false;
	}
	if (document.registro.tipo.value == "1") {
			if (document.registro.unitario.value == "") {
				alert ('Debe escribir el costo al que entrarn las unidades al almacen');
				document.getElementById('unitario').focus();
				return false;
			}
			if(document.registro.o_prod.value == "-1"){
				alert ('Debe seleccionar la orden de produccion');
				document.getElementById('o_prod').focus();
				return false;
			}else{
				if(document.registro.o_prod.value == "-2"){
					alert ('No puede registrar la entrada por que no hay ordenes de produccion pendientes para este producto');
					document.getElementById('o_prod').focus();
					return false;
				}
			}
	}
	if (document.registro.unidades.value == "") {
		alert ('Debe escribir las unidades de insumo que entran o salen del inventario');
		document.getElementById('unidades').focus();
		return false;
	}
	
	return true;
}
</SCRIPT>



<script>
//muestra el calendario
$(function() {
	$( "#fecha" ).datepicker({
		changeMonth: true,
		changeYear: true,
		showOn: 'both',
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: 'yy-mm-dd',
		showWeek: true,
		showOtherMonths: false,
		selectOtherMonths: false

  
	});
	
});
</script>




<!-- SI ENVIARON LOS DATOS DEL FORMULARIO DE PRODUCTO LOS VOY A GUARDAR  -->
 <?php


if(isset($_POST['operacion'])){
	$id_prod=$_POST['producto'];
	$tipo=$_POST['tipo'];
	$fecha=$_POST['fecha'];
	$o_prod=$_POST['o_prod'];
	$unitario=$_POST['unitario'];
	$orden=$_POST['orden'];
	$cantidad=$_POST['unidades'];
	//echo $orden;

	$query="INSERT INTO `producto_almacen`( `id_producto`, `fecha`, `cantidad`, `id_tipo_movimiento`, `id_orden_produccion`,
	 `unitario`) 
	VALUES (".$id_prod.",'".$fecha."',".$cantidad.",".$tipo.",".$orden.",".$unitario.");";
	$query=$db->consulta($query);

	$query="SELECT * from producto_almacen where id_orden_produccion=".$orden.";";
		$query=$db->consulta($query);
		$suma_cantidades=0;
		while($row3=$db->fetch_array($query)){
			$aux=$row3['cantidad'];
			$suma_cantidades=$suma_cantidades+$aux;
		}
		
		$query="SELECT * FROM detalle_orden_produccion where id_orden=".$orden.";";
		//echo $query;
		$query=$db->consulta($query);
		$row4=$db->fetch_array($query);
		if($suma_cantidades>=$row4['cantidad']){
			$query="UPDATE `orden_produccion` SET `id_estado`=3 WHERE id_orden=".$orden.";";
			echo $query;
			$query=$db->consulta($query);
			$link="LOCATION: ./ficha_orden_prod.php?numero=".$orden;
			header($link);
		}else{
			$link="LOCATION: ./ficha_orden_prod.php?numero=".$orden;
			header($link);
		}

	


	}

?>




</head>
<body>

<div id="templatemo_wrapper">

	<div id="templatemo_header">

    	<div id="site_title">
            <h1><a href="#">Sistema de ERP<span><?php echo 'Bienvenido, <b> '.$curUser.' </b>'; ?></span></a></h1>
        </div> <!-- end of site_title -->
        
        <div class="cleaner"></div>
    </div> <!-- end of templatemo_header -->
    
   <div id="templatemo_menu">
        <ul>
            <li><a href="../inicio.php">Inicio</a></li>
            <li><a href="produccion.php">Producci&oacute;n</a></li>
            <li><a href="almacen.php" class="current">Almac&eacute;n</a></li>
           	<li><a href="#" onclick="javascript:document.forms['salir'].submit();">Salir</a></li>
         <?php
			echo '<form action="logout.php" method="POST" name="salir" id="salir">
			<input type="hidden" name="action" value="logout">';
			echo'</form>';
		?>
        </ul>    	
    </div> <!-- end of templatemo_menu -->

    <div id="templatemo_banner_wrapper">
    
    <div id="templatemo_banner_thin"> 
    
    	
    
    	<div class="cleaner"></div>
        
    </div> <!-- end of banner -->
    
    </div>	<!-- end of banner_wrapper -->
    
    <div id="templatemo_service_bar_wrapper">
    
    <div id="templatemo_service_bar">
    
    	<div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_59.png" alt="image 3" />
            <a href="nueva_orden_produccion.php">Nueva Orden de Producci&oacute;n</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="#">Buscar</a>
            
        </div>
        
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Nuevo Movimiento Almac&eacute;n</h2>

			<?php 
			
			$clave = $_GET['numero'];
		
				//DATOS DEL PRODUCTO
				$producto = $db->consulta("SELECT * FROM `producto` WHERE id_producto='".$clave."'");
				while ($row = $db->fetch_array($producto))
				{
					$nombre_prod = utf8_encode($row['nombre']);
					$precio = $row['precio'];
				}
			$fecha = date('Y-m-d');
			?>
     
            <p><b>Producto: </b><?php echo $nombre_prod;?></p>
            <div id="comment_form">
           
           <fieldset>								  
          <legend> <B>Datos de Movimiento </B></legend>
          <table border="0" align="center">	

          
         <form method="POST" action="recibir_producto.php"  onSubmit="return validar()" name="registro" id="registro">
         <input type="hidden" name="operacion" id="operacion" value="1">
         <input type="hidden" name="producto" id="producto" value="<?php echo $clave;?>">
         <input type="hidden" name="orden" value="<?php echo $_GET['key'] ?>">
          
          <tr>
	          <td align="right">
		          *Tipo de movimiento: <b>Entrada</b>
		          <input type="hidden" name="tipo"  value="1" ></tr>
	          </td>
          </tr> 

         <tr><td align="left">*Fecha:&nbsp;&nbsp;&nbsp;</td>
          <td> <input class="texto" type="text" id="fecha" name="fecha" value="<?php echo $fecha; ?>" size="10" /></td></tr>
         
         
          <tr><td align="left" colspan="2"><div id="oprod" >*Orden de producci&oacute;n: <b> <?php echo $_GET['key'] ?></b>
                  
              <input type="hidden" name="o_prod" value="<?php echo $_GET['key'] ?>" > 
              
          </div></td>
          </tr>
  
          <tr>
         <td align="left" colspan="4"><div id="cunitario" ">&nbsp;&nbsp;&nbsp;*Costo unitario: $ 
         <input type="text" class="texto" value="<?php echo $precio; ?>" id="unitario" name="unitario" size="20"></div></td>
         </tr>
         
         
          <tr>
          <td align="right">*Unidades:</td>
          <?php
          $query="SELECT * from producto_almacen where id_orden_produccion=".$_GET['key'].";";
			$query=$db->consulta($query);
			$suma_cantidades=0;
			while($row3=$db->fetch_array($query)){
				$aux=$row3['cantidad'];
				$suma_cantidades=$suma_cantidades+$aux;
			}
		//echo $suma_cantidades;

		$query="SELECT * from detalle_orden_produccion where id_orden=".$_GET['key'].";";
		$query=$db->consulta($query);
			$suma_cantidades=0;
			while($row4=$db->fetch_array($query)){
				$ajuste=$row4['ajuste'];
				echo "Ajs".$ajuste	;
			}
		$suma_cantidades = $suma_recibido+$ajuste;
		$pendiente = $_GET['cantidad'] - $suma_cantidades;
          ?>
          <td colspan="2"><input type="text" class="texto" id="unidades" value="<?php echo $pendiente; ?>" name="unidades" size="20"></td>
          </tr>
          <tr>
          <td colspan="4" align="right">
              <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input class="submit_btn reset" type="reset" value="Cancelar"/>
          </td>
          </tr>
          </table>
          
          </fieldset>
                                   

           
            </div>

            
            
            
            
            
            

       	  <div class="cleaner_h20"></div>
            
            
		</div>
    	<div class="cleaner"></div>
    </div>
    </div>

</div> <!-- end of templatemo_wrapper -->

<div id="templatemo_footer_wrapper">
	<div id="templatemo_footer">

       	<a href="#">C&eacute;nit Consultores</a>
    
    </div> <!-- end of templatemo_footer -->
</div> <!-- end of templatemo_footer_wrapper -->

</body>
</html>
<?php
}
ob_end_flush();
?>