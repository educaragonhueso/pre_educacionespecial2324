<?php
######################
# script para matricular
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/core/Notificacion.php';
require_once DIR_APP.'parametros.php';
require_once DIR_BASE."/config/config_soap.php";
require_once DIR_CLASES.'LOGGER.php';

$log_matricula=new logWriter('log_matricula',DIR_LOGS);
$fecha=date('d/m/Y');
$notificacion=new Notificacion(WSDL_CORREO,AP_ID,$fecha,0);

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();
$solicitud=new Solicitud($conexion);

if($_POST['estado']=='Matricular')
   $sql="UPDATE alumnos set matricula=1 where id_alumno=".$_POST['id_alumno'];
else
   $sql="UPDATE alumnos set matricula=0 where id_alumno=".$_POST['id_alumno'];
   
   $result=$conexion->query($sql);

$token=$_POST['token'];
$correo_alumno=$solicitud->getCorreo($token,$log_matricula);
$contenido_correo="\nTe has matriculado correctamente";
$tipo_correo='Correo confirmación matrícula correcta';
$rescorreo=$notificacion->enviarCorreo('Confirmación Matricula',$correo_alumno,$contenido_correo,$tipo_correo);
$log_matricula->warning("RES CORREO: ".$rescorreo);
$conexion->close();
if($result)
   echo 'OK';
else     
	echo "ERROR";

?>
