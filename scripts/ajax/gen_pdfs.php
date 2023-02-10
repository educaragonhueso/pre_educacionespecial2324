<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/scripts/ajax/form_alumnojs.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';

#####VARIABLES#############################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$id_centro=$_POST['id_centro'];
$tipo=$_POST['tipolistado'];
$rol=$_POST['rol'];
$provincia=$_POST['provincia'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$subtipo_pdf=$_POST['tipolistado'];//dentro de cada tipo, el subtipo de listado
$subtipo_listado='vacantes';

$cabecera="campos_cabecera_".$subtipo_pdf;
$camposdatos="campos_bbdd_".$subtipo_pdf;

$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/';
$dir_pdf_web='scripts/datossalida/pdflistados/';

$tiposol=0;
$fase_sorteo=0;
$modo='pdf';

$list=new ListadosController('alumnos',$conexion,$estado_convocatoria);
$centros_cont=new CentrosController(0,$estado_convocatortia);

##################################################################################
$log_genpdfs=new logWriter('log_genpdfs',DIR_LOGS);
$log_genpdfs->warning("DATOSSS POST PARA PDFS");
$log_genpdfs->warning(print_r($_POST,true));
$log_genpdfs->warning("CABECERA: ".$cabecera);
##################################################################################

//si es para datos de matricula
if($tipo=='pdf_mat')
{
   $titulo="LISTADO COMPLETO DE VACANTES";
	//mostramos las solitudes completas sin incluir borrador
	$datoslistado=$list->getResumenMatriculaCentros($rol='centro',$id_centro,$modo,$log_genpdfs); 
	$log_genpdfs->warning("PREOBTENIENDO RESUMEN MATRICULA PDF");
   $cab=$$cabecera;
}
elseif($tipo=='pdf_usu')
{
   $titulo="LISTADO COMPLETO DE USUARIOS";
	//mostramos las solitudes completas sin incluir borrador
	$log_genpdfs->warning("OBTENIENDO RESUMEN USUARIOS PDF");
	$datoslistado=$list->getUsuarios($rol,$id_centro,$log_genpdfs,$provincia); 

   $cab=array('CENTRO','NOMBRE','ENLACE');
}
###################################################################################

$log_genpdfs->warning("OBTENIDO RESUMEN USUARIOS PDF");
$datos=array();
$i=0;
$pdf = new PDF();
//$cab=$$cabecera;
$pdf->SetFont('Helvetica','',8);
$pdf->Ln(20);
$pdf->Ln(20);
$pdf->setTitle('PROCESO DE ESCOLARIZACION DE ALUMNOS EN CENTROS SOSTENIDOS CON FONDOS PUBLICOS');
//pagina en Landscape
$pdf->AddPage('L','',0,$titulo);
  //tamaño de cada celta es $tam
$pdf->BasicTable($cab,(array)$datoslistado,0,45,'normal',40);
$pdf->Ln(20);
 // Arial italic 8
$pdf->SetFont('Arial','I',8);
// Page number
$pdf->Cell(0,10,'                         SELLO DEL CENTRO',0,1);
$pdf->Cell(0,10,'EL/LA DIRECTORA                        ',0,1,'R');
$firma='En Zaragoza______________________a____de____'.CURSO;
$pdf->Cell(0,10,$firma,0,0,'C');
$pdf->Ln(20);
$pdf->Cell(0,10,'Fdo                        ',0,1,'R');

$fichero=$dir_pdf.$subtipo_listado.'/'.$id_centro.'.pdf';
unlink($fichero);

$pdf->Output($dir_pdf.$subtipo_listado.'/'.$id_centro.'.pdf','F');

$pdffile=$dir_pdf_web.$subtipo_listado.'/'.$id_centro.'.pdf';
print($pdffile);
?>
