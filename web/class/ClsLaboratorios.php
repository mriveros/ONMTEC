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
    if  (empty($_POST['txtDescripcionA'])){$descripcionA='';}else{ $descripcionA= $_POST['txtDescripcionA'];}
    if  (empty($_POST['txtEstadoA'])){$estadoA='f';}else{ $estadoA= 't';}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombre'])){$nombreM='';}else{ $nombreM = $_POST['txtNombre'];}
    if  (empty($_POST['txtDescripcion'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcion'];}
    if  (empty($_POST['txtEstado'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'laboratorios', 'lab_nom')==true){
                echo '<script type="text/javascript">
		alert("El Laboratorio ya existe. Intente ingresar otro Laboratorio");
                window.location="http://localhost/app/ONMTEC/web/laboratorios/ABMlaboratorio.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO laboratorios(lab_nom,lab_des,fecha,estado) "
                        . "VALUES ('$nombreA','$descripcionA',now(),'$estadoA');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/app/ONMTEC/web/laboratorios/ABMlaboratorio.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update laboratorios set lab_nom='$nombreM',lab_des= '$descripcionM',estado='$estadoM' WHERE lab_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/laboratorios/ABMlaboratorio.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update laboratorios set estado='f' WHERE lab_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/laboratorios/ABMlaboratorio.php");
	}
