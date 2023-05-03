<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/clases/models/Matricula.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/scripts/ajax/form_alumnojs.php';

require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';

#####VARIABLES#############################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$id_centro=$_POST['id_centro'];
$provincia=$_POST['provincia'];

$subtipo_original=$_POST['subtipo'];
$subtipo_listado=substr($subtipo_original,4);
$subtipo_csv=$subtipo_listado;//dentro de cada tipo, el subtipo de listado

$estado_convocatoria=$_POST['estado_convocatoria'];
$rol=$_POST['rol'];
$id_alumno=$_POST['id_alumno'];
$id_centro=$_POST['id_centro'];

$cabecera="campos_cabecera_csv_".$subtipo_csv;
$camposdatos="campos_bbdd_csv_".$subtipo_csv;
$modo='csv';

$list=new ListadosController('alumnos',$conexion,$estado_convocatoria);
$centros_cont=new CentrosController(0,$estado_convocatoria);
$solicitud=new Solicitud($conexion);

##################################################################################
$log_gencsvs=new logWriter('log_gencsvs',DIR_LOGS);
$log_gencsvs->warning("INICIOLOG DATOS POST PARA CSV");
$log_gencsvs->warning(print_r($_POST,true));
##################################################################################
//si es para datos de matricula, con rol de admin
if($subtipo_original=='csv_sol')
{
   $solicitudes=$list->getSolicitudes($id_centro,$modo,$subtipo_listado,$solicitud,$log_gencsvs,0,$rol,$provincia); 
}
//si es para datos de matricula, con rol de admin
if($subtipo_original=='csv_mat_admin' && $rol=='admin')
{
	$centros_data=$centros_cont->getCentrosData('matricula'); 
}
//si es para matricula de alumnos que promocionan
if($subtipo_original=='csv_pro')
	$solicitudes=$list->getMatriculas($id_centro); 
//si es para datos de matricula y vacantes 
if($subtipo_original=='csv_mat')
{
   $log_gencsvs->warning("PROCESANDO LISTADO VACANTES CSV_MAT");
	$solicitudes=$list->getResumenMatriculaCentros('centro',$id_centro,$modo,$log_gencsvs,$estado_convocatoria,$provincia); 
}
//si es para datos de fase2
if($subtipo_original=='csv_fase2')
{
	$solicitudes=$solicitud->getMatriculadosFinal($subtipo_original,$rol,$log_gencsvs); 
}
if($subtipo_original=='csv_mat_final')
{
	$solicitudes=$solicitud->getMatriculadosFinales($subtipo_original,$rol,$log_gencsvs); 
}
//$fcsv=$list->genCsv($solicitudes,$id_centro,$subtipo_original,$$cabecera,$$camposdatos,DIR_CSVS_WEB);
$fcsv=$list->genCsv($solicitudes,$id_centro,$subtipo_original,$$cabecera,$$camposdatos,DIR_CSVS,$log_gencsvs);

$log_gencsvs->warning("LISTADO CSV GENERADO, DATOS:");
$log_gencsvs->warning("EN: ".DIR_CSVS);
$log_gencsvs->warning("EN DIRECTORIO WEB: ".DIR_CSVS_WEB."FICHERO: $fcsv");

if($fcsv) 
{
   $csvfile=DIR_CSVS_WEB.$fcsv;
   print($csvfile);
//print($fcsv);
}
else print("error generando csv");
?>
