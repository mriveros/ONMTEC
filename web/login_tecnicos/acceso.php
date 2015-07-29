<?php 
session_start();
?>
<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de ONM CONTROL INTN
 */
 include '../funciones.php';
conexionlocal();
$mail= $_REQUEST['mail'];
$ci=$_REQUEST['ci'];
//$pwd= md5($pwd); esto usaremos despues para comparar carga que se realizara en md5
//session_start();
//print_r($_REQUEST);
//////////////////////////INGRESO DE USUARIO
	$sql= "SELECT * FROM tecnicos tec
        WHERE tec.tec_mail = '$mail' AND tec.tec_ci=('$ci') and tec.estado='t'" ;
	//echo "$sql";
	//echo $n.' ---'.$sql; 
	$datosusr = pg_query($sql);
        $row = pg_fetch_array($datosusr);
        $n=0;
        $n = count($row['tec_nom']);
	if($n==0)
	{
		echo '<script type="text/javascript">
                         alert("Datos erroneos ingresados..!");
			 window.location="http://localhost/app/ONM/login_tecnicos/acceso.html";
                      </script>';
	}
	else
	{
            $_SESSION["nombre_usuario"] = $row['tec_nom'];
            $_SESSION["codigo_usuario"] = $row['tec_cod'];
            //$_SESSION["categoria_usuario"] = $row['usu_cat'];
            
            header("Location:http://localhost/app/ONMTEC/web/area_tecnica/menu_tecnicos.php");
	} 
	exit;
?>