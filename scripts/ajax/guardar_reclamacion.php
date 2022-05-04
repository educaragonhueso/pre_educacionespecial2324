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

$conectar=new Conectar('../../config/config_database.php');
$conexion=$conectar->conexion();

$id_alumno=$_POST['id_alumno'];
$tiporec=$_POST['tiporec'];

$solicitud=new Solicitud($conexion);
$token=$solicitud->getTokenAlumno($id_alumno);
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
   $enlace_correo="https://".$_SERVER['SERVER_NAME']."/".CONVOCATORIA."/reclamaciones_$tiporec.php?token=".$token;
   $contenido_correo="\nSe ha generado una nueva reclamación del $tioporec, para verla puedes usar el enlace: $enlace_correo\n";
   $tipo_correo='RECLAMACIÓN BAREMO';
   $rescorreo=$notificacion->enviarCorreo('Reclamación de baremo',$correo,$contenido_correo,$tipo_correo);
   print(1);
}
else
   print(0);
?>
