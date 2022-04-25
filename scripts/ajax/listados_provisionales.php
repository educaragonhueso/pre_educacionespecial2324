<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

######################################################################################
$log_listados_provisionales=new logWriter('log_listados_provisionales',DIR_LOGS);
$log_baremacion=new logWriter('log_baremacion',DIR_LOGS);
$log_listados_provisionales->warning("OBTENIENDO DATOS PROVISIONALES POST:");
$log_listados_provisionales->warning(print_r($_POST,true));
######################################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
//VARIABLES
$dir_pdf=DIR_BASE.'/scripts/datossalida/pdflistados/provisionales/';
$id_centro=$_POST['id_centro'];
$rol=$_POST['rol'];
$provincia=$_POST['provincia'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$tipo_listado='provisionales';
$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado
$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';

$tcentro=new Centro($conexion,$_POST['id_centro'],'ajax');
$tcentro->setNombre();
$tsolicitud=new Solicitud($conexion);
$ccentros=new CentrosController(0,$conexion);
$list=new ListadosController('alumnos',$conexion);

$nsolicitudes=$tcentro->getNumSolicitudes($id_centro);

$titulo_listado=strtoupper($tipo_listado)." ".strtoupper($subtipo_listado).$tcentro->getNombre();
//OPERACIONES ACTUALIZACION SOLICITUDES SEGUN ESTADO CONVOCATORIA
//Si se ha realizado ya el sorteo y aun ya estamos en el estado de provisionales
$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;

######################################################################################
$log_listados_provisionales->warning("OBTENIENDO SOLICITUDES PROVISIONALES, CABECERA: ".$cabecera);
$log_listados_provisionales->warning("OBTENIENDO SOLICITUDES PROVISIONALES, CENTRO: ".$id_centro);
$log_listados_provisionales->warning("OBTENIENDO SOLICITUDES PROVISIONALES, ESTADO CONVOCATORIA:  ".$estado_convocatoria);
######################################################################################

//mostramos las solitudes completas sin incluir borrador
$solicitudes=$list->getSolicitudes($id_centro,$estado_convocatoria,'provisionales',$subtipo_listado,$tsolicitud,$log_listados_provisionales,0,$rol,$provincia); 
######################################################################################
$log_listados_provisionales->warning("OBTENIDAS SOLICITUDES PROVISIONALES ");
######################################################################################
//actualizamos las solicitudes por si hay cambios para tener en cuenta el sorteo previo

$formato='provisional'; //formato listado en el pdf
$anchuracelda=10;
$primera_celda=20;
if($_POST['pdf']==1)
{
	$datos=array();
	$i=0;
	//extraemos los campos de datos q nos interesan
	foreach($solicitudes as $sol)
	{
		$datos[$i] = new stdClass;
		foreach($$camposdatos as $d)
		{
			$datos[$i]->$d=$sol->$d;
		}
	   $i++;
	}
	$pdf = new PDF();
	$cab=$$cabecera;
	$pdf->SetFont('Helvetica','',8);
	$pdf->AddPage('L','',0,$titulo_listado);
	//$pdf->BasicTable($cab,$datos,0,30,'provisional');
	$pdf->BasicTable($cab,$datos,0,$anchuracelda,$formato,$primera_celda);
	$pdf->Ln(20);
	$pdf->SetFont('Arial','I',8);
	  // Page number
	$pdf->Cell(30);
	$pdf->Cell(40,10,'SELLO CENTRO',1,0,'C');
	$pdf->Cell(140,10,'En ______________________ a ____de_________de 2022',0,0,'C');
	$pdf->Cell(0,10,'Firmado:',0,0);
	$pdf->Ln();
	$pdf->Cell(220,10,'El Director/a',0,0,'R');
	$pdf->Output(DIR_PROV.$subtipo_listado.'.pdf','F');
}

if($subtipo_listado=='admitidos_prov') $subtipo='ADMITIDOS PROVISIONAL';
if($subtipo_listado=='noadmitidos_prov') $subtipo='NO ADMITIDOS PROVISIONAL';
if($subtipo_listado=='excluidos_prov') $subtipo='EXCLUIDOS PROVISIONAL';

print("<button type='button' class='btn btn-info' onclick='window.open(\"".DIR_PROV_WEB.$subtipo_listado.".pdf\",\"_blank\");'>Descarga listado</button>");
/*
print("TITULO: ".$titulo_listado);
print("IDCENTRO: ".$id_centro);
print("NOMBRE CENTRO: ".str_replace(' ','',$tcentro->getNombre()));
*/
print($list->showFiltrosTipo());
print($filtro_datos);
print("<div style='text-align:center'><h1>LISTADO ".$titulo_listado."</h1></div>");
print($list->showListado($solicitudes,$_POST['rol'],$$cabecera,$$camposdatos,$provisional=1));

?>
