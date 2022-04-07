<?php
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
######################################################################################
# script para modificar datos de reclamaciones
######################################################################################

$id_alumno=$_POST['id_alumno'];
$motivo=$_POST['motivo'];

$dsql="DELETE FROM reclamaciones WHERE tipo='provisional' and id_alumno=$id_alumno";
$isql="INSERT INTO reclamaciones VALUES('provisional','$id_alumno','$motivo',now())";
print($dsql);
print($isql);
if($conexion->query($dsql) and $conexion->query($isql))
   print(1);
else
   print(0);
?>
