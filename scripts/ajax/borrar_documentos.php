<?php
//SECCION CARGA CLASES Y CONFIGURACIÓN
######################################################################################
require_once $_SERVER['CONTEXT_DOCUMENT_ROOT']."/educacionespecial/config/config_global.php";
require_once DIR_BASE.'/clases/core/Conectar.php';
require_once DIR_BASE.'/clases/models/Solicitud.php';
require_once DIR_BASE.'/scripts/ajax/form_alumnojs.php';
require_once DIR_BASE.'/scripts/ajax/form_alumno_doc_js.php';
require_once DIR_BASE.'/controllers/SolicitudController.php';
require_once DIR_CLASES.'LOGGER.php';
require_once DIR_APP.'parametros.php';
######################################################################################
$id_alumno=$_POST['id_alumno'];
$token=$_POST['token'];
$fichero=$_POST['fichero'];
$rol=$_POST['rol'];
if($rol=='centro') $token=$id_alumno;
elseif($id_alumno!=0) $token=$id_alumno;

$ruta="../fetch/uploads/$token/$fichero";

$log_borrar_documentacion=new logWriter('log_borrar_documentacion',DIR_LOGS);

######################################################################################
$log_borrar_documentacion->warning("BORRANDO DOCUMENTAICON CON ROL $rol DE $id_alumno EN: $ruta");
$log_borrar_documentacion->warning(print_r($_POST,true));
######################################################################################

if(unlink($ruta)) $msg="BORRADO DOCUMENTO";
else $msg="ERROR ELIMINANDO DOCUMENTO.$dir";

print($msg);
?>
