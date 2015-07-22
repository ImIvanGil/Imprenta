<?php include("../adminuser/adminpro_class.php");
$prot=new protect("1");
if ($prot->showPage) {
$curUser=$prot->getUser();
include("../lib/mysql.php");
$db = new MySQL();
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="shortcut icon" href="../images/favicon.ico">
<title>Sistema de ERP</title>
<link href="../styles/templatemo_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="../styles/ui-lightness/jquery.ui.all.css">


<script src="../js/jquery-1.8.0.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
<script src="../js/ui/jquery.ui.widget.js"></script>
<script src="../js/ui/jquery.ui.button.js"></script>
<script src="../js/ui/jquery.ui.position.js"></script>
<script src="../js/ui/jquery.ui.autocomplete.js"></script>
<script src="../js/ui/jquery.ui.datepicker.js"></script>

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
	if (document.registro.unidades.value == "") {
		alert ('Debe escribir las unidades de insumo que entran o salen del inventario');
		document.getElementById('unidades').focus();
		return false;
	}
	if (document.registro.tipo.value == "1" || document.registro.tipo.value == "3") {
			if(document.registro.unitario.value == ""){
				alert ('Debe escribir el costo unitario de los productos que van a entrar al inventario');
				document.getElementById('unitario').focus();
				return false;
			}
	}
	if (document.registro.descripcion.value == "") {
		alert ('Debe escribir escribir una descripcion que sirva como referencia del movimiento');
		document.getElementById('descripcion').focus();
		return false;
	}
	
	return true;
}
</SCRIPT>

<script type="text/javascript">
<!-- muestra y oculta campos en el formulario segun el select de tipo
function mostrar(){
if (document.registro.tipo.value == "1" || document.registro.tipo.value == "3") {
	//muestra el campo de costo unitario si es una entrada de mercancias si es una salida debera calclar el costo unitario promedio al momento de guardar
	document.getElementById('cunitario').style.display='block';
	document.getElementById('cunitario').focus();
} else {
	
		if(document.registro.tipo.value == "2" || document.registro.tipo.value == "-1"){
			//oculta y elimina el costo unitario si el tipo de operacion no esta seleccionado o si es salida
			document.getElementById('cunitario').style.display='none';
			document.registro.unitario.value = "";
		}
		
	}
	//oculta el div con id 'desdeotro'
}


-->
</script>
<?php
	if(isset($_POST['operacion'])){
		$id_orden=$_POST['orden'];
		$fecha=$_POST['fecha'];
		$tipoMov=$_POST['tipoMov'];
		$unidades=$_POST['unidades'];
		$desc=$_POST['descripcion'];
		
		$query= "INSERT INTO `almacen`( `id_orden`, `fecha`, `descripcion`, `id_tipo_movimiento`, `unidades`)
		VALUES (".$id_orden.",'".$fecha."','".$desc."',".$tipoMov.",".$unidades.");";
		$query=$db->consulta($query);

		$query="SELECT * from almacen where id_orden=".$id_orden.";";
		$query=$db->consulta($query);
		$suma_cantidades=0;
		while($row3=$db->fetch_array($query)){
			$aux=$row3['unidades'];
			$suma_cantidades=$suma_cantidades+$aux;
		}
		
		$query="SELECT * FROM detalle_orden_produccion where id_orden=".$id_orden.";";
		echo $query;
		$query=$db->consulta($query);
		$row4=$db->fetch_array($query);
		if($suma_cantidades>=$row4['cantidad']){
			$query="UPDATE `orden_produccion` SET `id_estado`=3 WHERE id_orden=".$id_orden.";";
			echo $query;
			$query=$db->consulta($query);
			$link="LOCATION: ./ficha_orden_prod.php?numero=".$id_orden;
			header($link);
		}else{
			$link="LOCATION: ./ficha_orden_prod.php?numero=".$id_orden;
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
            <li><a href="compras.php">Compras</a></li>
            <li><a href="inventario.php" class="current">Inventarios</a></li>
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
                
            <img src="../images/iconos/onebit_14.png" alt="image 3" />
            <a href="nuevo_insumo.php">Nuevo Insumo</a>
            
        </div>

         
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565">
        
     	<?php 
		
		$orden = $_GET['numero'];
		$detalle_orden = $_GET['key'];
		//$clave = $_GET['id_insumo'];
		$unidades = $_GET['cantidad'];
		//$unitario = $_GET['precio'];
		
		$desc = "Registro de entrada orden de compra $orden";
		
		//DATOS DEL INSUMO
		
		
		//DATOS DEL ajuste al detalle de la orden
		$cons_ajuste = $db->consulta("SELECT `ajuste` FROM `detalle_orden` WHERE `id_detalle` =$detalle_orden");
		while ($row2 = $db->fetch_array($cons_ajuste))
		{
			$ajuste = $row2['ajuste'];
		}
		
		
		//consultare las entradas que hay en inventario del insumo y la orden
		
		$query="SELECT * from almacen where id_orden=".$orden.";";
		$query=$db->consulta($query);
		$suma_cantidades=0;
		while($row3=$db->fetch_array($query)){
			$aux=$row3['unidades'];
			$suma_cantidades=$suma_cantidades+$aux;
		}
		//echo $suma_cantidades;
		//$suma_cantidades = $suma_recibido+$ajuste;
		$pendiente = $unidades - $suma_cantidades;
		//
		//datos del movimiento
		$fecha = date('Y-m-d');
		$descripcion = "";
		$tipo_mov = 1;
		

    	?> 
    
  <h3>Nuevo movimiento de almacen</h3>
            <p><b>Insumo: </b><?php echo $nombre_ins;?></p>
            <div id="comment_form">
           
           <fieldset>								  
          <legend> <B>Datos de Movimiento </B></legend>
          <table border="0" align="center">	

          
         <form method="POST" action="recibe_insumo.php"  onSubmit="return validar()" name="registro" id="registro">
         <input type="hidden" name="operacion" id="operacion" value="registrar">
         <input type="hidden" name="orden" id="orden" value="<?php echo $orden;?>">
                                              
         <tr><td align="right">*Fecha:</td>
          <td> <input class="texto" type="text" id="fecha" name="fecha" size="10" value="<?php echo $fecha;?>" /></td>
         <td align="right">*Tipo de movimiento: <b>Entrada</b> </td>
         <input type="hidden" name="tipoMov" value="1">          
          
          
         </tr>
          
          <tr>
          <td align="right">*Unidades:</td>
          <td><input type="text" class="texto" id="unidades" name="unidades" size="20" value="<?php echo $pendiente;?>"></td>
          
          
          <tr><td align="right" valign="top">*Descripci&oacute;n:</td>
          <td align="left" colspan="3"><textarea class="textarea" name="descripcion" id="descripcion" cols="40" rows="2"><?php echo $desc;?></textarea></td></tr>
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
?>