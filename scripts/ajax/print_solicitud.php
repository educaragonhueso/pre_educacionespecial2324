<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/scripts/ajax/form_alumnojs.php';

require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/scripts/informes/pdf/fpdf/classpdf.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$log_imprimir_solicitud=new logWriter('log_imprimir_solicitud',DIR_LOGS);

$solicitud=new Solicitud($conexion);

$fsol=$solicitud->crearpdf($_POST['id_alumno'],$log_imprimir_solicitud);

if($fsol==1) print('../datossalida/pdfsolicitudes/sol'.$_POST['id_alumno'].'.pdf');
else print("error generando pdf");
?>
