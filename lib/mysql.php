<?php
class MySQL{
 /*private $conexion;
  private $total_consultas;*/
  var $conexion;
  var $total_consultas;
 function MySQL(){
  if(!isset($this->conexion)){

  $this->conexion = (mysql_connect("localhost","root","sru3s2gg")) or die(mysql_error());
  mysql_select_db("imprenta",$this->conexion) or die(mysql_error());
  mysql_query("SET NAMES 'utf8'");
  }
  }
 function consulta($consulta){
  $this->total_consultas++;
  $resultado = mysql_query($consulta,$this->conexion);
  if(!$resultado){
  echo 'MySQL Error: ' . mysql_error();
  exit;
  }
  return $resultado; 
  }
 function fetch_array($consulta){ 
  return mysql_fetch_array($consulta);
  }
 function num_rows($consulta){ 
  return mysql_num_rows($consulta);
  }
 function getTotalConsultas(){
  return $this->total_consultas;
  }
  
  function free($consulta){ 
  return mysql_free_result();
  }
  function cerrar(){
  	if(!isset($this->conexion)){
	 mysql_close($this->conexion) or die(mysql_error());
  	}
  }
}?>

