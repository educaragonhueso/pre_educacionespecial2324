<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once 'UtilidadesAdmision.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$log_fase2_final=new logWriter('log_fase2_final',DIR_LOGS);

$ccentros=new CentrosController($conexion);
$tsolicitud=new Solicitud($conexion);

//copiamos todos los datos a tabla de definitivoes	
$ct=$tsolicitud->copiaTablaFase2Final($log_fase2_final);	
$log_fase2_final->warning("RESULTADO COPIAR TABLA $ct ");

echo PHP_EOL."Copia tabla solicitudes definitivoes realizada corectamente a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
?>
