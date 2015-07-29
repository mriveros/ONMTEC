<?php 
session_start();
?>
<?php
//Example FPDF script with PostgreSQL
//Ribamar FS - ribafs@dnocs.gov.br

require('fpdf.php');

class PDF extends FPDF{
function Footer()
{
        $this->text(40,200,"................................................");
        $this->text(40,206,"Funcionario ONM-INTN");
        $this->text(128,200,"...............................................");
        $this->text(140,206,utf8_decode("Usuario - C. I. P. Nº"));
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,280,9,280);//largor,ubicacion derecha,inicio,ubicacion izquierda
    // Go to 1.5 cm from bottom
        $this->SetY(-15);
    // Select Arial italic 8
        $this->SetFont('Arial','I',8);
    // Print centered page number
	$this->Cell(0,2,utf8_decode('Página: ').$this->PageNo().' de {nb}',0,0,'R');
	$this->text(10,283,'Datos Generados en: '.date('d-M-Y').' '.date('h:i:s'));
}

function Header()
{
   // Select Arial bold 15
    $this->SetFont('Arial','',16);
    $this->Image('img/intn.jpg',10,14,-300,0,'','../../InformeCargos.php');
    // Move to the right
    $this->Cell(80);
    // Framed title
    $this->text(37,19,utf8_decode('Instituto Nacional de Tecnología, Normalización y Metrología'));
    $this->SetFont('Arial','',8);
    $this->text(37,24,"Avda. Gral. Artigas 3973 c/ Gral Roa- Tel.: (59521)290 160 -Fax: (595921) 290 873 ");
    $this->text(37,29,"ORGANISMO NACIONAL DE METROLOGIA");
    $this->text(37,34,"Telefax: (595921) 295 408 e-mail: metrologia@intn.gov.py");
    //-----------------------TRAEMOS LOS DATOS DE CABECERA----------------------
    $conectate=pg_connect("host=localhost  port=5434 dbname=onmworkflow user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
    $consulta=pg_exec($conectate,"select max(ing_cod) as codigo from ingreso");
    $codingreso=pg_result($consulta,0,'codigo');
    $consulta=pg_exec($conectate,"select ing.ing_cod,cli.cli_contacto ,ing.ing_proforma,to_char(ing.fecha_recepcion,'DD/MM/YYYY') as fecha_recepcion,
    cli.cli_nom||' '||cli.cli_ape as cliente, to_char(now(),'DD/MM/YYYY') as fecha_entrega,cli.cli_mail,cli.cli_ruc,cli.cli_nro 
    from ingreso ing, clientes cli
    where ing.cli_cod=cli.cli_cod
    and ing.ing_cod=$codingreso");
    $codingreso=pg_result($consulta,0,'ing_cod');
    $proforma=pg_result($consulta,0,'ing_proforma');
    $fecha=pg_result($consulta,0,'fecha_recepcion');
    $fechaentrega=pg_result($consulta,0,'fecha_entrega');
    $cliente=pg_result($consulta,0,'cliente');
    $ruc=pg_result($consulta,0,'cli_ruc');
    $numero=pg_result($consulta,0,'cli_nro');
    $mail=pg_result($consulta,0,'cli_mail');
    $contacto=pg_result($consulta,0,'cli_contacto');
    //--------------------------------------------------------------------------
    $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(230,40,10,40);//largor,ubicacion derecha,inicio,ubicacion izquierda

    //table header CABECERA        
    $this->SetFont('Arial','B',12);
    $this->SetTitle('LISTADO DE INGRESOS');
    $this->text(160,39,utf8_decode('Nº:'));
    $this->text(175,39,$codingreso);
    
    $this->text(160,44,'Fecha:');
    $this->text(175,44,$fecha);
    $this->text(55,50,'CONTROL DE INGRESO DE INSTRUMENTOS O EQUIPOS');
    $this->SetFont('Arial','',12);
    $this->text(10,59,'RAZON SOCIAL:');
    $this->text(45,59,$cliente);
    $this->text(10,65,'RUC:');
    $this->text(45,65,$ruc);
    $this->text(10,70,'CONTACTO:');
    $this->text(45,65,$contacto);
    $this->text(10,75,'FECHA ENTREGA:');
    $this->text(50,75,$fechaentrega);
    $this->text(130,59,'PROFORMA Nro.:');
    $this->text(170,59,$proforma);
    $this->text(130,65,'TEL/FAX:');
    $this->text(150,65,$numero);
    $this->text(130,70,'E-mail:');
    $this->text(150,70,$mail);
    
    
}
}
$pdf= new PDF();//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
$pdf->AddPage();
//-------------------------Damos formato al informe-----------------------------    
$pdf->AliasNbPages();
$pdf->SetFont('Arial','',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);


//------------------------QUERY and data cargue y se reciben los datos-----------
$conectate=pg_connect("host=localhost  port=5434 dbname=onmworkflow user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');

if  (empty($_POST['txtCodigo'])){$codigo=0;}else{$codigo=$_POST['txtCodigo'];}
$consulta=pg_exec($conectate,"select  ins.ins_nom,1 as cantidad,ingdet.ing_obs,ingdet.ing_coddet
from ingreso ing, ingreso_detalle ingdet, instrumentos ins
where ing.ing_cod=ingdet.ing_cod
and ins.ins_cod=ingdet.ins_cod
and ingdet.ing_estado='f'
and ingdet.situacion='ENTREGADO'
and ing.ing_cod=$codigo");
$numregs=pg_numrows($consulta);
//------------------------------------------------------------------------------

    
//----------------------------Build table---------------------------------------
$pdf->SetXY(10,100);
$pdf->Cell(25,10,'Cantidad',1,0,'C',50);
$pdf->Cell(60,10,'Descripcion',1,0,'C',50);
$pdf->Cell(95,10,'Observacion',1,1,'C',50);
$fill=false;
$i=0;

while($i<$numregs)
{   
    $cantidad=pg_result($consulta,$i,'cantidad');
    $instrumento=pg_result($consulta,$i,'ins_nom');
    $observacion=pg_result($consulta,$i,'ing_obs');
    $codigodetalle=pg_result($consulta,$i,'ing_coddet');
    $pdf->Cell(25,5,$cantidad,1,0,'C',$fill);
    $pdf->Cell(60,5,$instrumento,1,0,'L',$fill);
    $pdf->Cell(95,5,$observacion,1,1,'L',$fill);
    $fill=!$fill;
    $i++;
    $actualizar=pg_exec($conectate,"update ingreso_detalle set ing_estado='t' where ing_coddet=$codigodetalle");
}
$actualizar=pg_exec($conectate,"select COALESCE(sum(ing_coddet),0) as detalle_si_no from ingreso_detalle where ing_estado='f' and ing_cod=$codigo");
$cantidad=pg_result($actualizar,0,'detalle_si_no');
if ($cantidad==0)
{
    $actualizar=pg_exec($conectate,"update ingreso set estado='f',situacion='ENTREGADO' where ing_cod=$codigo");
}
//Add a rectangle, a line, a logo and some text
/*
$pdf->Rect(5,5,170,80);
$pdf->Line(5,90,90,90);
//$pdf->Image('mouse.jpg',185,5,10,0,'JPG','http://www.dnocs.gov.br');
$pdf->SetFillColor(224,235);
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(5,95);
$pdf->Cell(170,5,'PDF gerado via PHP acessando banco de dados - Por Ribamar FS',1,1,'L',1,'mailto:ribafs@dnocs.gov.br');
*/
$pdf->Output();
$pdf->Close();

?>
