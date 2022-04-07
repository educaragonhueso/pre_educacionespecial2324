<?php
require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';
#GENERAMOS DUPLICADO SBASADO EN EL NOMBRE APELLIDOS Y FNACIMIENTO

require_once 'UtilidadesAdmision.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$ccentros=new CentrosController($conexion);
$centro=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,$ccentros,$centro);

$res=$utils->setDuplicados();

if($res==1) echo "Actualizadas duplicados a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
else "Error generando duplicados: $res";
?>
