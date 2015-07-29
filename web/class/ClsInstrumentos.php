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
    if  (empty($_POST['txtLaboratorioA'])){$laboratorioA=0;}else{ $laboratorioA= $_POST['txtLaboratorioA'];}
    if  (empty($_POST['txtEstadoA'])){$estadoA='f';}else{ $estadoA= 't';}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtNombre'])){$nombreM=0;}else{ $nombreM = $_POST['txtNombre'];}
    if  (empty($_POST['txtDescripcion'])){$descripcionM='';}else{ $descripcionM= $_POST['txtDescripcion'];}
    if  (empty($_POST['txtLaboratorio'])){$laboratorioM=0;}else{ $laboratorioM= $_POST['txtLaboratorio'];}
    if  (empty($_POST['txtEstado'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDato($nombreA, 'laboratorios', 'lab_nom')==true){
                echo '<script type="text/javascript">
		alert("El Instrumento ya existe. Intente ingresar otro Instrumento");
                window.location="http://localhost/app/ONMTEC/web/instrumentos/ABMinstrumento.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO instrumentos(ins_nom,ins_des,lab_cod,fecha,estado) "
                        . "VALUES ('$nombreA','$descripcionA',$laboratorioA,now(),'$estadoA');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/app/ONMTEC/web/instrumentos/ABMinstrumento.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update instrumentos set ins_nom='$nombreM',ins_des= '$descripcionM',lab_cod=$laboratorioM,estado='$estadoM' WHERE ins_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/instrumentos/ABMinstrumento.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update instrumentos set estado='f' WHERE ins_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/instrumentos/ABMinstrumento.php");
	}
