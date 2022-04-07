<?php
######################
# script para borrar solicitudd, marcándola como borrador
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
#require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE."/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$sql="UPDATE alumnos SET fase_solicitud='borrador' WHERE token='".$_POST['token']."'";
$result=$conexion->query($sql);

$conexion->close();
if ($result)
	print("OK");
	else     
	echo "ERROR";

?>
