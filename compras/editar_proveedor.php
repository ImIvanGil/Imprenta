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

<!-- script que hace el ordenamiento de la tabla -->
<script type="text/javascript">
$(document).ready(function() 
    { 
        $("#tablesorter-test").tablesorter({sortList:[[0,0]], widthFixed: true, widgets: ['zebra'], headers: { 4:{sorter: false},5:{sorter: false},6:{sorter: false}}});
		 $("#tablesorter-test").tablesorterPager({container: $("#pager")});
    } 
);
</script>

<!-- Script que hace que aparezcan enlazados los combos de pais, estado y ciudad -->
<script type="text/javascript">
	$(document).ready(function(){
		cargar_paises();
		$("#pais").change(function(){dependencia_estado();});
		$("#estado").change(function(){dependencia_ciudad();});
		$("#estado").attr("disabled",false);
		$("#ciudad").attr("disabled",false);
	});
	
	function cargar_paises()
	{
		$.get("scripts/cargar-paises.php", function(resultado){
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$('#pais').append(resultado);			
			}
		});	
	}
	function dependencia_estado()
	{
		var code = $("#pais").val();
		$.get("scripts/dependencia-estado.php", { code: code },
			function(resultado)
			{
				if(resultado == false)
				{
					alert("Error");
				}
				else
				{
					$("#estado").attr("disabled",false);
					document.getElementById("estado").options.length=1;
					$('#estado').append(resultado);			
				}
			}
	
		);
	}
	
	function dependencia_ciudad()
	{
		var code = $("#estado").val();
		$.get("scripts/dependencia-ciudades.php?", { code: code }, function(resultado){
			if(resultado == false)
			{
				alert("Error");
			}
			else
			{
				$("#ciudad").attr("disabled",false);
				document.getElementById("ciudad").options.length=1;
				$('#ciudad').append(resultado);			
			}
		});	
		
	}
</script>
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
            <li><a href="proveedores.php" class="current">Proveedores</a></li>
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
                
            <img src="../images/iconos/onebit_64.png" alt="image 3" />
            <a href="nuevo_proveedor.php">Nuevo Proveedor</a>
            
        </div>
        <div class="sb_box sb_box_last">
                
            <img src="../images/iconos/onebit_70.png" alt="image 3" />
            <a href="proveedores.php">Lista Proveedores</a>
            
        </div>

         <div class="sb_box">
                
            <img src="../images/iconos/onebit_02.png" alt="image 1" />
            <a href="busqueda_proveedor.php">Buscar</a>
            
        </div>
        
        
        
    
    </div> <!-- end of templatemo_service_bar -->
    
    </div> <!-- end of templatemo_service_bar_wrapper -->
    
    <div id="templatemo_content_wrapper">
    <div id="templatemo_content">
    
    	<div class="col_w565_interior">
    
            <h2>Editar Datos de Proveedor</h2>
            <div id="data_form">
            
            <SCRIPT LANGUAGE="JavaScript">
			<!-- Funcion que valida que se hayan escrito los campos obligatorios-->

				function validar() {
					if (document.registro.nombre.value == "") {
						alert ('Debe escribir el nombre del proveedor');
						document.getElementById('nombre').focus();
						return false;
					}
					if (document.registro.contacto.value == "") {
						alert ('Debe escribir el nombre de la persona de contacto');
						document.getElementById('contacto').focus();
						return false;
					}
					if (document.registro.calle.value == "") {
						alert ('Debe escribir la calle');
						document.getElementById('calle').focus();
						return false;
					}
					if (document.registro.colonia.value == "") {
						alert ('Debe escribir la colonia');
						document.getElementById('colonia').focus();
						return false;
					}
					if (document.registro.pais.value == "-1") {
						alert ('Debe seleccionar el pais');
						document.getElementById('pais').focus();
						return false;
					}
					if (document.registro.estado.value == "-1") {
						alert ('Debe seleccionar el estado');
						document.getElementById('estado').focus();
						return false;
					}
					if (document.registro.ciudad.value == "-1") {
						alert ('Debe seleccionar la ciudad');
						document.getElementById('ciudad').focus();
						return false;
					}
					if (document.registro.tel.value == "") {
						alert ('Debe escribir un numero de telefono');
						document.getElementById('tel').focus();
						return false;
					}
					if (document.registro.dias.value == "") {
						alert ('Debe escribir los dias de credito que otorga el proveedor');
						document.getElementById('dias').focus();
						return false;
					}
					return true;
					
				}
				
			</SCRIPT> 
            
            <?php
              $db = new MySQL();
              $clave = $_GET['numero'];
			  $proveedor = $db->consulta("SELECT * FROM `proveedor` WHERE id_proveedor='".$clave."'");
              while ($row = $db->fetch_array($proveedor))
			  {
				  $nombre = $row['nombre'];
				  $calle = $row['calle'];
				  $num = $row['numero'];
				  $colonia = $row['colonia'];
				  $ciudad = $row['ciudad'];
				  $estado = $row['estado'];
				  $tel = $row['telefono'];
				  $tel2 = $row['telefono1'];
				  $tel3 = $row['telefono2'];
				  $cp = $row['codigo_postal'];
				  $mail = $row['correo'];
				  $rfc = $row['rfc'];
				  $contacto = $row['contacto'];
				  $pais = $row['pais'];
				  $dias = $row['dias'];
				}
				
					
			?>
            <form method="post" enctype="multipart/form-data" action="registro_proveedor.php" name="registro" onSubmit="return validar()">
							<input type="hidden" name="operacion" id="operacion" value="editar">	
                            <input type="hidden" name="clave" id="clave" value="<?php echo $clave;?>">	
							<table align="center" width="550px" border="0px">
								
								<tr><td>
								  <BR>
                                  <fieldset>								  
								    <legend> <B>Datos de Registro </B></legend>
								    <table border="0">	

								    <tr><td align="right"><span>*</span>Nombre:</td>
								    <td> <input class="texto" type="text" id="nombre" name="nombre" size="60" value="<?php echo $nombre;?>" /><br /></td></tr>
                                    <tr><td align="right"><span>*</span>Contacto:</td>
								    <td> <input class="texto" type="text" id="contacto" name="contacto" size="60" value="<?php echo $contacto;?>"/><br /></td></tr>
                                                                       
								    <tr><td colspan="2"><span>Domicilio</span> <hr></td>
                                    <tr><td align="right"><span>*</span>Calle:</td>
								    <td> <input class="texto" type="text" id="calle" name="calle" size="40" value="<?php echo $calle;?>" /><br /></td></tr>
                                    
                                   <tr><td align="right">N&uacute;mero:</td>
								   <td> <input class="texto" type="text" id="num" name="num" size="20" value="<?php echo $num;?>"/><br /></td></tr>
                                   <tr><td align="right"><span>*</span>Colonia:</td>
								   <td> <input class="texto" type="text" id="colonia" name="colonia" size="40" value="<?php echo $colonia;?>"/><br /></td></tr>
                                   <tr><td align="right">C.P.:</td>
								   <td> <input class="texto" type="text" id="cp" name="cp" size="10" value="<?php echo $cp;?>"/><br /></td></tr>
                                   
                                   <tr><td align="right"><span>*</span>Pa&iacute;s:</td>
								   <td> <select id="pais" name="pais">
                                            <option value="-1">Seleccione Uno...</option>
                                        <?php 
										
												$lista_paises = $db->consulta("SELECT * FROM `pais`");
												while ($row3 = $db->fetch_array($lista_paises))
													{
														$pais1 = $row3['Name'];
														$cve_pais1 = $row3['Code'];
														if($cve_pais1==$pais){
															echo "<option value=\"".$cve_pais1."\"selected>".$pais1."</option>";
															}
														else{
															echo "<option value=\"".$cve_pais1."\">".$pais1."</option>";
															}
														
													}
										
										?>
                                         </select><br />
                                        
                                   </td></tr>
                                        
                                   <tr><td align="right"><span>*</span>Estado:</td>
								   <td> <select id="estado" name="estado">
                                            <option value="-1">Selecciona Uno...</option>
                                            <?php 
											
													$lista_estados = $db->consulta("SELECT * FROM `estado`");
													while ($row4 = $db->fetch_array($lista_estados))
														{
															$estado1 = $row4['Name'];
															$cve_pais1 = $row4['Country'];
															if($cve_pais1==$pais&&$estado1==$estado){
																echo "<option value=\"".$estado1."\"selected>".$estado1."</option>";
																}
															else{
																echo "<option value=\"".$estado1."\">".$estado1."</option>";
																}
															
														}
											
											?>
                                            
                                            
                                        </select><br />
                                   </td></tr>
                                   
                                   <tr><td align="right"><span>*</span>Ciudad:</td>
								   <td> <select id="ciudad" name="ciudad">
                                            <option value="-1">Selecciona Uno...</option>
                                              <?php 
											
													$lista_ciudad = $db->consulta("SELECT * FROM `ciudad`");
													while ($row5 = $db->fetch_array($lista_ciudad))
														{
															$ciudad1 = $row5['Name'];
															$cve_pais1 = $row5['Country'];
															$estado1 = $row5['Province'];
															if($ciudad1==$ciudad&&$estado1==$estado&&$cve_pais1==$pais){
																echo "<option value=\"".$ciudad1."\"selected>".$ciudad1."</option>";
															}
															else{
																echo "<option value=\"".$ciudad1."\">".$ciudad1."</option>";
																}
															
														}
											
											?>
                                        </select><br />
                                   </td></tr>
                                   
                                    <tr><td colspan="2"><hr></td>
                                                                     
                                    <tr><td align="right"><span>*</span>Tel&eacute;fono:</td>
								    <td> <input class="texto" type="text" id="tel" name="tel" size="30" value="<?php echo $tel;?>"/><br /></td></tr>	    
                                    <tr><td align="right"> Tel&eacute;fono 2:</td>
								    <td> <input class="texto" type="text" id="tel2" name="tel2" size="30" value="<?php echo $tel2;?>"/><br /></td></tr>	
                                    
                                    <tr><td align="right"> Tel&eacute;fono 3:</td>
								    <td> <input class="texto" type="text" id="tel3" name="tel3" size="30" value="<?php echo $tel3;?>"/><br /></td></tr>	     
								    <tr><td align="right">E-mail:</td>
								    <td> <input class="texto" type="text" id="mail" name="mail" size="30" value="<?php echo $mail;?>"/><br /></td></tr>
                                    <tr><td align="right">RFC:</td>
								    <td> <input class="texto" type="text" id="rfc" name="rfc" size="20" value="<?php echo $rfc;?>"/><br /></td></tr>
                                    <tr><td align="right"><span>*</span>D&iacute;as de Cre&eacute;dito:</td>
								    <td> <input class="texto" type="text" id="dias" name="dias" size="20" value="<?php echo $dias;?>"/><br /></td></tr>
                                    
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