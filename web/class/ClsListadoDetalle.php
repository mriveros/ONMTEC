<?php
 include '../funciones.php';
 conexionlocal();
 if  (empty($_GET['coddetalle'])){$codigodetalle=0;}else{$codigodetalle=$_GET['coddetalle'];}
 if  (empty($_GET['codtecnico'])){$codigotecnico=0;}else{$codigotecnico=$_GET['codtecnico'];}
  
 
$query = "update ingreso_detalle set situacion='EN PROGRESO',ing_codtecnico_retira=$codigotecnico where ing_coddet=$codigodetalle";
pg_query($query)or die('Error al realizar la carga');
header("Refresh:0; url=http://localhost/app/ONMTEC/web/ingresos/ListadoDetalle.php");
 ?>