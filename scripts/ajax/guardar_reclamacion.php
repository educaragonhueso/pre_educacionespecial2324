<?php
######################
# script para modificar/editar y crear solicitudes
######################

//CARGAMOS CONFIGURACION GENERAL SCRIPTS AJAX
include('../../config/config_global.php');

require_once DIR_BASE."/config/config_soap.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Centro.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/clases/core/Notificacion.php';
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_CLASES.'LOGGER.php';

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$log_reclamaciones=new logWriter('log_reclamaciones',DIR_LOGS);
$id_alumno=$_POST['id_alumno'];
$tiporec=$_POST['tiporec'];

$solicitud=new Solicitud($conexion);
$token=$solicitud->getTokenAlumno($id_alumno);
$correo=$solicitud->getCorreo($token,$log_reclamaciones);
$id_centro_destino=$solicitud->getIdCentroFromtoken($token,$log_reclamaciones);
$correo_centro=$solicitud->getCorreoCentro($id_centro_destino);
$fecha=date('d/m/Y');
$notificacion=new Notificacion(WSDL_CORREO,AP_ID,$fecha,0);

######################################################################################
# script para modificar datos de reclamaciones
######################################################################################

$id_alumno=$_POST['id_alumno'];
$motivo=$conexion->real_escape_string($_POST['motivo']);

$dsql="DELETE FROM reclamaciones WHERE tipo='$tiporec' and id_alumno=$id_alumno";
$isql="INSERT INTO reclamaciones VALUES('$tiporec','$id_alumno','$motivo',now())";
print($isql);
if($conexion->query($dsql) and $conexion->query($isql))
{
   $enlace_correo="<a href='https://".$_SERVER['SERVER_NAME']."/".EDICION."/reclamaciones_$tiporec.php?token=".$token."'>ENLACE RECLAMACIÓN</a>";
   $contenido_correo="\nSe ha generado una nueva reclamación del $tioporec, para verla puedes usar el enlace: $enlace_correo\n";
   $tipo_reccorreo='RECLAMACIÓN BAREMO';
   if($tiporec=='provisional')
      $tipo_reccorreo='RECLAMACIÓN PROVISIONAL';
   $rescorreo=$notificacion->enviarCorreo($tipo_reccorreo,$correo,$contenido_correo,'Correo confirmación reclamación realizada');
   //correo para el centro, de momento ponemos el mio para pruebas
   $log_reclamaciones->warning("\nCORREO CENTRO ORIGINAL:  $correo_centro");
   $correo_centro='huesoluis@gmail.com';
   $rescorreo=$notificacion->enviarCorreo($tipo_reccorreo,$correo_centro,$contenido_correo,'Correo confirmación reclamación realizada');
   $log_reclamaciones->warning("\nGENERADO RECLAMACION $tipo_correo");
   $log_reclamaciones->warning("\nRESPUESTA CORREO:  $rescorreo");
   $log_reclamaciones->warning("\nCORREO CENTRO:  $correo_centro");
   print(1);
}
else
   print(0);
?>
