<?php
######################
# script para cambiar estado de las solicitudes de matriculados
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');
require_once DIR_CLASES.'LOGGER.php';

require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';

$log_cambio_estado=new logWriter('log_cambio_estado',DIR_LOGS);

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$centro=new Centro($conexion,$_POST['id_centro'],'ajax');

$vacantes=$centro->getVacantes('centro',$log_cambio_estado);
$nuevotipo=str_replace('CAMBIA A ','',$_POST['estado_pulsado']);
$result=1;
if($_POST['continua']=='CONTINUA')
{
   $sql="update matricula set tipo_alumno_actual='".trim($nuevotipo)."' where id_alumno=".$_POST['id_alumno'];
   $result=$conexion->query($sql);
}
if($result)
{
	$vacantes=$centro->getVacantes('centro',$log_cambio_estado);
	print($vacantes[0]->vacantes.':'.$vacantes[1]->vacantes);
}
else     
	echo "error";
$conexion->close();

?>
