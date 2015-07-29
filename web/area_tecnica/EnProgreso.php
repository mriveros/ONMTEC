<?php
session_start();
if(!isset($_SESSION["codigo_usuario"]))
header("Location:http://localhost/app/ONMTEC/web/login_tecnicos/acceso.html");
$codtecnico=  $_SESSION["codigo_usuario"];
?>
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ONM-Listado Ingresos</title>
    <!-- Bootstrap Core CSS -->
    <link href="../../../bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- MetisMenu CSS -->
    <link href="../../../bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
	<!-- DataTables CSS -->
    <link href="../../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="../../../bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../../dist/css/sb-admin-2.css" rel="stylesheet">
    <!-- Custom Fonts -->
    <link href="../../../bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="../../../bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../../bower_components/metisMenu/dist/metisMenu.min.js"></script>
	
    <!-- DataTables JavaScript -->
    <script src="../../../bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="../../../bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../../dist/js/sb-admin-2.js"></script>
	    
	<!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
			responsive: true
        });
    });
    </script>
    <script type="text/javascript">
		function cambiarEstado(coddetalle){
                    $.ajax({type: "GET",url:"../class/ClsAreaTecnicaCalibrando.php",data:"coddetalle="+coddetalle,success:function(msg){
                            $("#").fadeIn("slow",function(){
                            $("#").html(msg);
                            })}})
		};
	</script>	
</head>

<body>

    <div id="wrapper">

        <?php 
        include("../funciones.php");
        include("./menu_tecnicos.php");
        conexionlocal();
        ?>
        <!-- Page Content -->
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                      <h1 class="page-header">Listado Ingresos - <small>ONM WORKFLOW</small></h1>
                </div>	
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Listado de Instrumentos en Proceso de Calibracion
                        </div>
                        <!-- /.panel-heading -->
                        <form class="form-horizontal"  method="post" role="form" >
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr class="success">
                                            <th>Codigo</th>
                                            <th>Cliente</th>
                                            <th>Instrumento</th>
                                            <th>Observacion</th>
                                            <th>Fecha Entrega</th>
                                            <th>Situacion</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                    <?php
                    $query = "select ingdet.ing_coddet,ingdet.ing_cant,ins.ins_nom,ingdet.ing_obs,cli.cli_nom|| ' '||cli.cli_ape as nombres,to_char(ing.fecha_entrega,'DD/MM/YYYY')as fecha_entrega,ingdet.situacion 
                            from tecnicos_laboratorios teclab,clientes cli,tecnicos tec,ingreso ing, ingreso_detalle ingdet, 
                            laboratorios lab, instrumentos ins
                            where ins.lab_cod=lab.lab_cod 
                            and  teclab.lab_cod=lab.lab_cod 
                            and teclab.tec_cod=tec.tec_cod 
                            and ing.ing_cod=ingdet.ing_cod
                            and ingdet.ins_cod=ins.ins_cod
                            and cli.cli_cod=ing.cli_cod
                            and ingdet.situacion='EN PROGRESO'
                            and tec.tec_cod=$codtecnico
                            and ingdet.ing_codtecnico_retira=$codtecnico";
                    $result = pg_query($query) or die ("Error al realizar la consulta");
                    while($row1 = pg_fetch_array($result))
                    {
                        echo "<tr><td>".$row1["ing_coddet"]."</td>";
                        echo "<td>".$row1["nombres"]."</td>";
                        echo "<td>".$row1["ins_nom"]."</td>";
                        echo "<td>".$row1["ing_obs"]."</td>";
                        echo "<td>".$row1["fecha_entrega"]."</td>";
                        echo "<td>".$row1["situacion"]."</td>";
                        echo "<td>";?>
                        <button onclick="cambiarEstado(<?php echo $row1["ing_coddet"]; ?>)" type="submit" name="modificar" class="btn btn-primary">Enviar a Recepcion</button>
                        <?php
                        echo "</td></tr>";
                    }
                    pg_free_result($result);
                    ?>
                                    </tbody>
                                </table>
                            </div>		
                        </div>
                       </form>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
 
</html>