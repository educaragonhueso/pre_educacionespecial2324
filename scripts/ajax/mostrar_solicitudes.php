<?php
######################
# script para mostrar solicitudes de centros
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
#require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE."/config/config_global.php";
require_once DIR_BASE."/config/config_soap.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_BASE.'/clases/core/Notificacion.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();


$solicitud=new Solicitud($conexion);
$log_mostrar_solicitudes=new logWriter('log_mostrar_solicitudes',DIR_LOGS);
$rol=$_POST['rol'];
$provincia=$_POST['provincia'];
$estado_convocatoria=$_POST['estado_convocatoria'];
$id_centro=$_POST['id_centro'];

if($_POST['rol']=='admin' || $_POST['rol']=='sp') 
{
	$list=new ListadosController('alumnos');
	$log_mostrar_solicitudes->warning('IDCENTRO: '.$id_centro);
	$solicitudes=$list->getSolicitudes($id_centro,$estado_convocatoria,'normal','normal',$solicitud,$log_mostrar_solicitudes,0,$rol,$provincia); 
	$log_mostrar_solicitudes->warning('MOSTRANDO SOLICITUDES');
	print_r($list->showSolicitudes($solicitudes,'centro',$id_centro));
}
else
   print("ERROR");
?>
