<?php
require('fpdf.php');

class PDF extends FPDF
{
function Header($title='')
{
    // Logo
   // $this->Image('logo_solicitud.jpg',10,6,60);
    $this->Image('logo_gobaragon.jpg',10,6,40);
    // Arial bold 15
    $this->SetFont('Arial','B',8);
		$this->setY(10);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(80,10,'PROCESO DE ESCOLARIZACION DE ALUMNOS EN CENTROS SOSTENIDOS CON FONDOS PUBLICOS',0,1,'C');
    
		$this->Cell(80);
		$this->Cell(80,10,CURSO_LARGO,0,1,'C');
		$this->Cell(80);
		$this->Cell(80,10,trim($title),0,1,'C');
    // Line break
    $this->Ln(20);
}
function HeaderListados($titulo)
{
    // Logo
    //$this->Image('logo_solicitud.jpg',10,6,60);
    $this->Image('logo_gobaragon.jpg',10,6,40);
    // Arial bold 15
    $this->SetFont('Arial','B',8);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(80,10,'ROCESO DE ESCOLARIZACION DE ALUMNOS EN CENTROS SOSTENIDOS CON FONDOS PUBLICOS',0,1);
    
		$this->Cell(80);
		$this->Cell(50,10,CURSO_LARGO,0,1,'C');
		$this->Cell(80);
		$this->Cell(50,10,$titulo,0,1,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}


// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header,$data,$comp,$tam,$tipoinforme,$primera_celda)
{
   $celdadatos=30;
   $celdavalores=13; 
	if($tipoinforme=='provisional' or $tipoinforme=='definitivos')
   {
      $primera_celda=35;
      $celdadatos=30;
      $margen=5;
      $celdavalores=30; 
   }
   else if(strcmp('detalle_baremo',$tipoinforme)==0)
   {
      $primera_celda=30;
      //celdas con datos 'largos'
      $celdadatos=22;
      $margen=1;
      $celdavalores=13; 
   }
   else if(strcmp('solicitudes_baremadas',$tipoinforme)==0)
   {
      $primera_celda=30;
      //celdas con datos 'largos'
      $celdadatos=30;
      $margen=40;
      $celdavalores=18; 
   }
   else if(strcmp('numero_aleatorio',$tipoinforme)==0)
   {
      $primera_celda=35;
      $celdadatos=35;
      $margen=45;
      $celdavalores=35; 
   }
	else
   {
      $tipoinforme='normal';
      $margen=20;
   }
	$cc=0;
	// Header. CABECERA DE LA TABLA, $tam indica la anchura de la celda
	foreach($header as $col)
   {
		if($cc==0)
		{
         if($comp==0) $this->Cell($margen);
         $this->Cell($primera_celda,7,utf8_decode($col),1);
		}
      else if($cc<=3)
      {
         if($col!='ENLACE')
            $this->Cell($celdadatos,7,utf8_decode($col),1);
         else
         {
            $tipoinforme='usuarios';
    	      $this->Cell(130,7,utf8_decode($col),1);
         }
      }
    	else   
         $this->Cell($celdavalores,7,utf8_decode($col),1);
		$cc++;
	}
   $this->Ln();
    // Data
   foreach($data as $row)
   {
	   $cc=0;
	   if($comp==0) $this->Cell($margen);
        foreach($row as $col)
	     {
		   if($cc==0)
		   {
			if(strlen($col)>30)
				$col = substr(utf8_decode($col),0,25);
			$this->Cell($primera_celda,7,utf8_decode($col),1);
			$cc++;
			continue;
		   }
         else if($cc<=3)
         {
            if($cc==2 and $tipoinforme=='usuarios')
               $this->Cell(130,7,utf8_decode($col),1);
            else
       	      $this->Cell($celdadatos,7,utf8_decode($col),1);
         }
         else
            $this->Cell($celdavalores,7,utf8_decode($col),1,0,'C');
		   $cc++;
        }
				$this->Ln();
    }
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(40, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Header
    $w = array(40, 35, 40, 45);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}
?>
