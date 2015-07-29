<?php
 include '../funciones.php';
 conexionlocal();
 if  (empty($_GET['proforma'])){$proforma='';}else{$proforma=$_GET['proforma'];}
 if  (empty($_GET['cliente'])){$cliente='';}else{$cliente=$_GET['cliente'];}
 if  (empty($_GET['fechaentrega'])){$fechaentrega='';}else{$fechaentrega=$_GET['fechaentrega'];}
 if  (empty($_GET['obs'])){$obs='';}else{$obs=$_GET['obs'];}

 
$query = "INSERT INTO ingreso(ing_proforma,cli_cod,usu_cod,fecha_recepcion,fecha_entrega,estado,situacion,ing_obs)"
        . "VALUES ($proforma,$cliente,1,now(),'$fechaentrega','t','RECEPCION','$obs');";
pg_query($query)or die('Error al realizar la carga');
 ?>