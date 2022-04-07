<?php
//copiamos los datos de alumnos:nombre, apellidos, téléfono e email a la tabla de matricula para permitir la matrícula definitiva
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

$log_fase_final_matricula=new logWriter('log_fase_final_matricula',DIR_LOGS);
$utils=new UtilidadesAdmision($conexion);

//copiamos todos los datos a tabla de provisionales	
$ct=$utils->copiaTablaMatriculaFinal();	
$log_fase_final_matricula->warning("RESULTADO COPIAR TABLA FINAL $ct ");

echo PHP_EOL."Copia tabla solicitudes final realizada corectamente a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
?>
