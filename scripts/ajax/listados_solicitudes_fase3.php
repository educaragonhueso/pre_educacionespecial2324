<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';
require_once DIR_BASE.'/scripts/servidor/UtilidadesAdmision.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';

require_once DIR_BASE.'/scripts/ajax/form_alumnofase2js.php';
######################################################################################
$log_listados_solicitudes_fase3=new logWriter('log_listados_solicitudes_fase3',DIR_LOGS);
$log_listados_solicitudes_fase3->warning("OBTENIENDO DATOS SOLICITUDES FASE III:");
$log_listados_solicitudes_fase3->warning(print_r($_POST,true));
######################################################################################
$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);
//VARIABLES
$estado_convocatoria=$_POST['estado_convocatoria'];
$rol=$_POST['rol'];
$id_centro=$_POST['id_centro'];
//comprobamos si es el dia de sorteo para la fase 2

$tipo_listado='solicitudes_fase3';
if(isset($_POST['subtipo']))
	$subtipo_listado=$_POST['subtipo'];//dentro de cada tipo, el subtipo de listado, para ebo o tva

$filtro_datos='<input type="text" class="form-control" id="filtrosol"  placeholder="Introduce datos del alumno"><small id="emailHelp" class="form-text text-muted"></small>';
$list=new ListadosController('matricula',$conexion);
$utils=new UtilidadesAdmision($conexion);
$tsolicitud=new Solicitud($conexion);
$tcentro=new Centro($conexion,1,'ajax');
$nsolicitudes=$tcentro->getNumSolicitudes();
$cabecera="campos_cabecera_".$subtipo_listado;
$camposdatos="campos_bbdd_".$subtipo_listado;
//actualizamos el estado del sorteo. 0:no realizado, 1.numero asignado, 2. realizado
//mostramos las solitudes completas sin incluir borrador
$solicitudes=$tsolicitud->getSolicitudesFase3($subtipo_listado,$rol,$id_centro,$estado_convocatoria,$log_listados_solicitudes_fase3); 

######################################################################################
$log_listados_solicitudes_fase3->warning("OBTENIDAS $nsolicitudes SOLICITUDES FASE III:");
######################################################################################
$tablaresumen=$tcentro->getResumenFase2($_POST['rol']);
print($list->showTablaResumenFase2($tablaresumen,$ncol=1));
print($list->showFiltrosTipo());
print($filtro_datos);
print("<div id='listado_fase3' style='text-align:center'><h1>LISTADO SOLICITUDES COMPLETO FASE III</h1></div>");
print($list->showListadoFase3($solicitudes,$rol,$$cabecera,$$camposdatos,$provisional=1,$subtipo_listado));
print($script);
?>
