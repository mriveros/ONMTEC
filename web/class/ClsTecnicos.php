<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Control ONM-INTN
 */

    include '../funciones.php';
    conexionlocal();
    
    //Datos del Form Agregar
    if  (empty($_POST['txtNombreA'])){$nombreA=0;}else{ $nombreA = $_POST['txtNombreA'];}
    if  (empty($_POST['txtApellidoA'])){$apellidoA='';}else{ $apellidoA= $_POST['txtApellidoA'];}
    if  (empty($_POST['txtCiA'])){$ciA='';}else{ $ciA= $_POST['txtCiA'];}
    if  (empty($_POST['txtMailA'])){$mailA='';}else{ $mailA= $_POST['txtMailA'];}
    if  (empty($_POST['opcionA'])){$estadoA='f';}else{ $estadoA= 't';}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombre'])){$nombreM='';}else{ $nombreM = $_POST['txtNombre'];}
    if  (empty($_POST['txtApellido'])){$apellidoM='';}else{ $apellidoM= $_POST['txtApellido'];}
    if  (empty($_POST['txtCi'])){$ciM='';}else{ $ciM= $_POST['txtCi'];}
    if  (empty($_POST['txtMail'])){$mailM='';}else{ $mailM= $_POST['txtMail'];}
    if  (empty($_POST['txtOpcion'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($ciA, 'tecnicos', 'tec_ci')==true){
                echo '<script type="text/javascript">
		alert("El Tecnico ya existe. Intente ingresar otro Tecnico");
                window.location="http://localhost/app/ONMTEC/web/tecnicos/ABMtecnico.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO tecnicos(tec_nom,tec_ape,tec_ci,tec_mail,fecha,estado) "
                        . "VALUES ('$nombreA','$apellidoA','$ciA','$mailA',now(),'$estadoA');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/app/ONMTEC/web/tecnicos/ABMtecnico.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update tecnicos set tec_nom='$nombreM',tec_ape= '$apellidoM',tec_ci='$ciM',tec_mail='$mailM',estado='$estadoM' WHERE tec_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/tecnicos/ABMtecnico.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update tecnicos set estado='f' WHERE tec_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/tecnicos/ABMtecnico.php");
	}
