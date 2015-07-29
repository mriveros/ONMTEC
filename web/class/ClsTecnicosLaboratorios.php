<?php
/*
 * Autor: Marcos A. Riveros.
 * Año: 2015
 * Sistema de Control ONM-INTN
 */

    include '../funciones.php';
    conexionlocal();
    
    //Datos del Form Agregar
    if  (empty($_POST['txtTecnicoA'])){$tecnicoA=0;}else{ $tecnicoA = $_POST['txtTecnicoA'];}
    if  (empty($_POST['txtLaboratorioA'])){$laboratorioA='';}else{ $laboratorioA= $_POST['txtLaboratorioA'];}
    if  (empty($_POST['txtEstadoA'])){$estadoA='f';}else{ $estadoA= 't';}
    
    //Datos del Form Modificar
    if  (empty($_POST['txtCodigo'])){$codigoModif=0;}else{$codigoModif=$_POST['txtCodigo'];}
    if  (empty($_POST['txtTecnico'])){$tecnicoM='';}else{ $tecnicoM = $_POST['txtTecnico'];}
    if  (empty($_POST['txtLaboratorio'])){$laboratorioM='';}else{ $laboratorioM= $_POST['txtLaboratorio'];}
    if  (empty($_POST['txtEstado'])){$estadoM='f';}else{ $estadoM= 't';}
    
    //DAtos para el Eliminado Logico
    if  (empty($_POST['txtCodigoE'])){$codigoElim=0;}else{$codigoElim=$_POST['txtCodigoE'];}
    
    
        //Si es agregar
        if(isset($_POST['agregar'])){
            if(func_existeDatoDetalle($tecnicoA,$laboratorioA,'tecnicos_laboratorios','tec_cod', 'lab_cod','onmworkflow')==true){
                echo '<script type="text/javascript">
		alert("El Tecnico en el Laboratorio ya existe. Intente ingresar otro Tecnico o Laboratorio");
                window.location="http://localhost/app/ONMTEC/web/tecnicos_laboratorios/ABMtecniLab.php";
		</script>';
                }else{              
                //se define el Query   
                $query = "INSERT INTO tecnicos_laboratorios(tec_cod,lab_cod,fecha,estado) "
                        . "VALUES ('$tecnicoA',$laboratorioA,now(),'$estadoA');";
                //ejecucion del query
                $ejecucion = pg_query($query)or die('Error al realizar la carga');
                $query = '';
                header("Refresh:0; url=http://localhost/app/ONMTEC/web/tecnicos_laboratorios/ABMtecniLab.php");
                }
            }
        //si es Modificar    
        if(isset($_POST['modificar'])){
            
            pg_query("update tecnicos_laboratorios set tec_cod='$tecnicoM',lab_cod=$laboratorioM,estado='$estadoM' WHERE teclab_cod=$codigoModif");
            $query = '';
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/tecnicos_laboratorios/ABMtecniLab.php");
        }
        //Si es Eliminar
        if(isset($_POST['borrar'])){
            pg_query("update tecnicos_laboratorios set estado='f' WHERE teclab_cod=$codigoElim");
            header("Refresh:0; url=http://localhost/app/ONMTEC/web/tecnicos_laboratorios/ABMtecniLab.php");
	}
