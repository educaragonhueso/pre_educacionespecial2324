<?php
##########################################################
#GENRAMOS LA TABLA alumnos_baremada_final
##########################################################

require_once "../../config/config_global.php";
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/controllers/CentrosController.php';
require_once DIR_BASE.'/controllers/ListadosController.php';
require_once DIR_BASE.'/clases/models/Centro.php';

require_once 'UtilidadesAdmision.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$centro=new Centro($conexion,'','no',0);
$utils=new UtilidadesAdmision($conexion,'',$centro);

//actualizar vacantes de centros
$res=$utils->genBaremadas();

print("\n");
print($res);
print("\n");
if($res==1) echo "Generada tabla baremadas final a las ".date('H:m')." del dia ".date('d-M-Y').PHP_EOL;	
else "Error generando provisionales: $res";
?>
