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

$log_fase_final=new logWriter('log_fase_final',DIR_LOGS);

$tipo='final';

$ccentros=new CentrosController($conexion,ESTADO_ASIGNACIONES);
$centro=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,$ccentros,$centro);
$tsolicitud=new Solicitud($conexion);

$acentros=array();
$centros=$ccentros->getAllCentros('todas','especial');
$ccentros=new CentrosController(0,$conexion);

while($row = $centros->fetch_assoc()) { $acentros[]=$row;}

//copiamos todos los datos a tabla de provisionales	
$ct=$utils->copiaTablaFase2('fase2',0);	
$ct=$tsolicitud->copiaCentrosFinal();	
$log_fase_final->warning("RESULTADO COPIAR TABLA FINAL $ct ");

echo PHP_EOL."Copia tabla solicitudes final realizada corectamente a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
?>
