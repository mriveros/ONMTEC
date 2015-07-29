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
        
       
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(290,200,9,200);//largor,ubicacion derecha,inicio,ubicacion izquierda
    // Go to 1.5 cm from bottom
        $this->SetY(-15);
    // Select Arial italic 8
        $this->SetFont('Arial','I',8);
    // Print centered page number
	$this->Cell(0,2,utf8_decode('Página: ').$this->PageNo().' de {nb}',0,0,'R');
	$this->text(10,197,'Datos Generados en: '.date('d-M-Y').' '.date('h:i:s'));
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
   
    //---------------------------------------------------------
    $this->Ln(30);
    $this->Ln(30);
	$this->SetDrawColor(0,0,0);
	$this->SetLineWidth(.2);
	$this->Line(290,40,10,40);//largor,ubicacion derecha,inicio,ubicacion izquierda
    //------------------------RECIBIMOS LOS VALORES DE POST-----------
    if  (empty($_POST['txtDesdeFecha'])){$desde='';}else{ $desde= $_POST['txtDesdeFecha'];}
    if  (empty($_POST['txtHastaFecha'])){$hasta='';}else{ $hasta= $_POST['txtHastaFecha'];}
   
    
    //table header CABECERA        
    $this->SetFont('Arial','B',12);
    $this->SetTitle('Registros Entradas');
    $this->text(100,50,'CONTROL DE CALIBRACION INSTRUMENTOS');
    $this->text(108,60,'Registros de Entradas por Fecha');//Titulo
    //$this->text(45,65,$cliente);
    $this->text(10,75,'DESDE FECHA:');
    $this->text(45,75,$desde);
    $this->text(10,85,'HASTA FECHA:');
    $this->text(45,85,$hasta);
    
}
}
$pdf= new PDF('L');//'P'=vertical o 'L'=horizontal,'mm','A4' o 'Legal'
$pdf->AddPage();
//------------------------RECIBIMOS LOS VALORES DE POST-----------
    if  (empty($_POST['txtDesdeFecha'])){$desde='';}else{ $desde= $_POST['txtDesdeFecha'];}
    if  (empty($_POST['txtHastaFecha'])){$hasta='';}else{ $hasta= $_POST['txtHastaFecha'];}
    
//-------------------------Damos formato al informe-----------------------------    
$pdf->AliasNbPages();
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(224,235,255);
$pdf->SetTextColor(0);
    
//----------------------------Build table---------------------------------------
$pdf->SetXY(10,100);
$pdf->Cell(25,10,'Nro.Control',1,0,'C',50);
$pdf->Cell(60,10,'Clientes',1,0,'C',50);
$pdf->Cell(100,10,'Observaciones',1,0,'C',50);
$pdf->Cell(25,10,'Recepcion',1,0,'C',50);
$pdf->Cell(25,10,'Entrega',1,0,'C',50);
$pdf->Cell(25,10,'Situacion',1,1,'C',50);
$fill=false;
$i=0;
$pdf->SetFont('Arial','',10);

//------------------------QUERY and data cargue y se reciben los datos-----------
 $conectate=pg_connect("host=localhost  port=5434 dbname=onmworkflow user=postgres password=postgres"
                    . "")or die ('Error al conectar a la base de datos');
$consulta=pg_exec($conectate,"select ing.ing_proforma, cli.cli_nom || ' '||cli.cli_ape as cliente,ing.ing_obs,to_char(ing.fecha_recepcion,'DD/MM/YYYY') as fecha_recepcion,
to_char(ing.fecha_entrega,'DD/MM/YYYY') as fecha_entrega,ing.situacion
from ingreso ing, clientes cli
where ing.cli_cod=cli.cli_cod
and ing.fecha_recepcion >= '$desde'
and ing.fecha_recepcion <= '$hasta'");
$numregs=pg_numrows($consulta);
while($i<$numregs)
{   
    $nrocontrol=pg_result($consulta,$i,'ing_proforma');
    $cliente=pg_result($consulta,$i,'cliente');
    $obs=pg_result($consulta,$i,'ing_obs');
    $recepcion=pg_result($consulta,$i,'fecha_recepcion');
    $entrega=pg_result($consulta,$i,'fecha_entrega');
    $situacion=pg_result($consulta,$i,'situacion');
    $pdf->Cell(25,5,$nrocontrol,1,0,'C',$fill);
    $pdf->Cell(60,5,$cliente,1,0,'L',$fill);
    $pdf->Cell(100,5,$obs,1,0,'L',$fill);
    $pdf->Cell(25,5,$recepcion,1,0,'C',$fill);
    $pdf->Cell(25,5,$entrega,1,0,'C',$fill);
    $pdf->Cell(25,5,$situacion,1,1,'C',$fill);
    $fill=!$fill;
    $i++;
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