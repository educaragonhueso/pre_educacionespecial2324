<?php
#actualiza el cambio de estadod e EBO a TVA o viceversa en la matrícula
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

if($_POST['estado']=='Matricular')
   $sql="UPDATE alumnos_matricula_final set matricula='si' where id_alumno=".$_POST['id_alumno'];
else
   $sql="UPDATE alumnos_matricula_final set matricula='no' where id_alumno=".$_POST['id_alumno'];
   
   $result=$conexion->query($sql);
$conexion->close();
print_r($_POST);
if($result)
   echo 'OK';
else     
	echo "ERROR";

?>
