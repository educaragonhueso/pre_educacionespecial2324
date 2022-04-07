<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/clases/core/Verificacion.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$solicitud=new Solicitud($conexion);
$id_alumno=$_POST['id_alumno'];

$datossolicitud=$solicitud->getDatosSolicitud($id_alumno);
$padron=new Verificacion($datossolicitud);

$res=$padron->verificarPadron($datossolicitud);
print_r($res);
exit();
#if(padron->verificarPadron())

$nuevoestado='CONTINUA';
$nuevoestado=$_POST['estado'];

$vacantes=$centro->getVacantes('centro','');
$sql="update matricula set estado='".$nuevoestado."' where id_alumno=".$_POST['id_alumno'];
$result=$conexion->query($sql);

$vacantes=$centro->getVacantes('centro','');
$conexion->close();
if ($result)
	print($vacantes[0]->vacantes.':'.$vacantes[1]->vacantes);
	else     
	echo "No results".$sql;

?>
