<?php
 include '../funciones.php';
 conexionlocal();
 if  (empty($_GET['coddetalle'])){$codigodetalle=0;}else{$codigodetalle=$_GET['coddetalle'];}
$query = "update ingreso_detalle set situacion='TERMINADO',fecha_trabajo=now() where ing_coddet=$codigodetalle";
pg_query($query)or die('Error al realizar la carga');
 ?>