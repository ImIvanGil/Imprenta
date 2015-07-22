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

<script language="javascript" src="../js/jquery.js"></script> 
<script type="text/javascript" src="../js/jquery-latest.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.js"></script>
<script type="text/javascript" src="../js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="../js/chili/chili-1.8b.js"></script>
<script type="text/javascript" src="../js/docs.js"></script>

<script type="text/javascript" src="../js/jquery.alerts.js"></script>
<script src="../js/jquery.ui.draggable2.js"></script>

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
            <li><a href="insumos.php" class="current">Insumos</a></li>
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

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_insumo.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Editar Insumo</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.nombre.value == "") {
						alert ('Debe escribir el nombre del insumo');
						document.getElementById('nombre').focus();
						return false;
					}
					if (document.registro.descripcion.value == "") {
						alert ('Debe escribir una descripcion del insumo');
						document.getElementById('descripcion').focus();
						return false;
					}
					if (document.registro.moneda.value == "-1") {
						alert ('Debe seleccionar la moneda en que se realizarán las operaciones del insumo');
						document.getElementById('moneda').focus();
						return false;
					}
					if (document.registro.linea.value == "-1") {
						alert ('Debe seleccionar una linea a la que pertenece el insumo');
						document.getElementById('linea').focus();
						return false;
					}
					if (document.registro.clase.value == "-1") {
						alert ('Debe seleccionar una clase a la que pertenece el insumo');
						document.getElementById('clase').focus();
						return false;
					}
					if (document.registro.tipo.value == "-1") {
						alert ('Debe seleccionar el tipo de insumo que esta registrando');
						document.getElementById('tipo').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            
            <?php
              $db = new MySQL();
              $clave = $_GET['numero'];
			  $insumo = $db->consulta("SELECT * FROM `insumo` WHERE id_insumo='".$clave."'");
              while ($row = $db->fetch_array($insumo))
			  {
				  $nombre = $row['nombre'];
				  $noparte = $row['num_parte'];
				  $descripcion = $row['descripcion'];
				  $id_linea = $row['id_linea'];
				  $id_clase = $row['id_clase'];
				  $tipo = $row['tipo'];
				  $id_moneda = $row['id_moneda'];
				  $max = $row['maximo'];
				  $min = $row['minimo'];
				  $id_unidad = $row['id_unidad'];
				}
				
					
			?>
            <form method="post" enctype="multipart/form-data" action="registro_insumo.php" name="registro" onSubmit="return validar()">
							<input type="hidden" name="operacion" id="operacion" value="editar">
                            <input type="hidden" name="clave" id="clave" value="<?php echo $clave;?>">	
							<table align="center" width="550px" border="0px">
								
								<tr><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Datos de Registro </B></legend>
								    <table border="0">	

								    <tr><td align="right">No. de Parte:</td>
								    <td> <input class="texto" type="text" id="noparte" name="noparte" size="10" value="<?php echo $noparte;?>" /><br /></td></tr>
                                    <tr><td align="right"><span>*</span>Nombre:</td>
								    <td> <input class="texto" type="text" id="nombre" name="nombre" size="60" value="<?php echo $nombre;?>" /><br /></td></tr>
                                    <tr><td align="right" valign="top"><span>*</span>Descripci&oacute;n:</td>
								    <td> <textarea id="descripcion" class="required" cols="0" rows="0" name="descripcion"><?php echo $descripcion;?></textarea><br /></td></tr>
                                    
                                    <tr>
                                    <td align="right">L&iacute;nea: </td>
                                    	<td align="left" valign="middle">
                                        <select id="linea" name="linea">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
											  $lista_tin = $db->consulta("SELECT * FROM `linea_producto`;");
											  while ($row6 = $db->fetch_array($lista_tin))
											  {
												$id_lin = $row6['id_linea'];
												$linea = $row6['linea'];
												if($id_lin==$id_linea){
														echo "<option value=\"".$id_lin."\"selected>".$linea."</option>";
												  }else{
														 echo "<option value=\"".$id_lin."\">".$linea."</option>";
												  }
											  }
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                    <td align="right">Clase: </td>
                                    	<td align="left" valign="middle">
                                        <select id="clase" name="clase">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
											  $lista_tin = $db->consulta("SELECT * FROM `clase_insumo`;");
											  while ($row6 = $db->fetch_array($lista_tin))
											  {
												$id_clas = $row6['id_clase'];
												$clase = $row6['clase'];
												if($id_clas==$id_clase){
														echo "<option value=\"".$id_clas."\"selected>".$clase."</option>";
												  }else{
														 echo "<option value=\"".$id_clas."\">".$clase."</option>";
												  }
											  }
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right">Tipo: </td>
                                    
                                    	<td align="left" valign="middle">
                                        <select id="tipo" name="tipo">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
											 
												if($tipo=='1'){
													  
														echo "<option value=\"1\"selected>Principal</option>";
														echo "<option value=\"2\">Accesorio</option>";
												  }else{
													  if($tipo=='2'){
														
														  	echo "<option value=\"1\">Principal</option>";
														 	echo "<option value=\"2\"selected>Accesorio</option>";
														}else{
															
															echo "<option value=\"1\">Principal</option>";
															 echo "<option value=\"2\">Accesorio</option>";
														}
													 	 
												  }
											?>
                                        </select> 
                                    </td>
                                    </tr>
                                    
                                    <tr>
                                    <td align="right">Unidad de medida: </td>
                                    	<td align="left" valign="middle">
                                        <select id="unidad" name="unidad">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
												$lista_un = $db->consulta("SELECT * FROM `unidades`;");
													while ($row6 = $db->fetch_array($lista_un))
													{
													  $id_un = $row6['id_unidad'];
													  $un = $row6['unidad'];
													  
													  if($id_un==$id_unidad){
															  echo "<option value=\"".$id_un."\"selected>".$un."</option>";
														}else{
															   echo "<option value=\"".$id_un."\">".$un."</option>";
														}
													  
													  
													  
													}
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    
                                    <tr>
                                    <td align="right"><span>*</span>Moneda: </td>
                                    	<td align="left" valign="middle">
                                       <select id="moneda" name="moneda">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
											  $lista_mon = $db->consulta("SELECT * FROM `moneda`;");
											  while ($row6 = $db->fetch_array($lista_mon))
											  {
												$id_mon = $row6['id_moneda'];
												$moneda = $row6['moneda'];
												if($id_mon==$id_moneda){
														echo "<option value=\"".$id_mon."\"selected>".$moneda."</option>";
												  }else{
														 echo "<option value=\"".$id_mon."\">".$moneda."</option>";
												  }
											  }
											?>
                                        </select>
                                    </td>
                                    </tr>
                                    
                                    <tr><td align="right"><span>*</span>Inventario m&aacute;ximo:</td>
								    <td> <input class="texto" type="text" id="max" name="max" size="10" value="<?php echo $max;?>" /><br /></td></tr>
                                    
                                    <tr><td align="right"><span>*</span>Inventario m&iacute;nimo:</td>
								    <td> <input class="texto" type="text" id="min" name="min" size="10" value="<?php echo $min;?>" /><br /></td></tr>
                                    
                                    </table>
								  </fieldset>
								</td></tr>
                                
                                
                               

								
								<tr><td colspan = "2"><small><span>*</span>Campos obligatorios</small></td></tr>

								<tr><td colspan="2"align="right"> 	   
								 <BR> <input class="submit_btn reset" type="submit" value="Registrar"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								 <input class="submit_btn reset" type="reset" value="Cancelar"/>
								</td></tr>
								
								</table>  
							</form>
        
        
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