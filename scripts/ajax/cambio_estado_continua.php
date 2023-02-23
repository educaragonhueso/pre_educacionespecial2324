<?php
######################
# script para modificar/editar y crear solicitudes
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

$nuevoestado='CONTINUA';
$nuevoestado=$_POST['estado'];

#$vacantes=$centro->getVacantes('centro',$log_cambio_estado);
$sql="update matricula set estado='".$nuevoestado."' where id_alumno=".$_POST['id_alumno'];
$result=$conexion->query($sql);

$vacantes=$centro->getVacantesCentro('centro',$log_cambio_estado);
#$centro->setVacantes($vacantes);
$conexion->close();
if ($result)
	print($vacantes['ebo'].':'.$vacantes['tva']);
	#print_r($vacantes);
	else     
	echo "ERROR OBTENIENDO VACANTES".$sql;

?>
